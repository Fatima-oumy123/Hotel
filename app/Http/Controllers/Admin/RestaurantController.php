<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\RestaurantSale;
use Illuminate\Http\Request;
use Carbon\Carbon;

class RestaurantController extends Controller
{
    public function index(Request $request)
    {
        $date = $request->date ? Carbon::parse($request->date) : Carbon::today();

        $sales = RestaurantSale::whereDate('sale_date', $date)
            ->when($request->category, fn($q) => $q->where('category', $request->category))
            ->latest()
            ->paginate(30);

        $dailyStats = [
            'total'      => RestaurantSale::whereDate('sale_date', $date)->where('status', 'completed')->sum('total'),
            'count'      => RestaurantSale::whereDate('sale_date', $date)->where('status', 'completed')->count(),
            'cancelled'  => RestaurantSale::whereDate('sale_date', $date)->where('status', 'cancelled')->count(),
            'by_category' => RestaurantSale::whereDate('sale_date', $date)
                ->where('status', 'completed')
                ->selectRaw('category, SUM(total) as total, COUNT(*) as count')
                ->groupBy('category')
                ->get(),
        ];

        $monthlyStats = [
            'revenue'  => RestaurantSale::whereMonth('sale_date', $date->month)->where('status', 'completed')->sum('total'),
            'top_items' => RestaurantSale::whereMonth('sale_date', $date->month)
                ->where('status', 'completed')
                ->selectRaw('item_name, SUM(quantity) as qty, SUM(total) as revenue')
                ->groupBy('item_name')
                ->orderByDesc('revenue')
                ->take(10)
                ->get(),
        ];

        return view('restaurant.index', compact('sales', 'dailyStats', 'monthlyStats', 'date'));
    }

    public function create()
    {
        return view('restaurant.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'item_name'   => 'required|string|max:200',
            'category'    => 'required|string|max:50',
            'quantity'    => 'required|integer|min:1',
            'unit_price'  => 'required|numeric|min:0',
            'payment_method' => 'required|in:cash,card,room_charge',
            'table_number'   => 'nullable|string|max:10',
            'notes'          => 'nullable|string|max:500',
        ]);

        RestaurantSale::create([
            ...$request->all(),
            'total'     => $request->quantity * $request->unit_price,
            'status'    => 'completed',
            'sale_date' => now(),
            'cashier_id' => auth()->id(),
        ]);

        return redirect()->route('restaurant.index')->with('success', 'Vente enregistrée.');
    }

    public function cancel(RestaurantSale $restaurant)
    {
        $request = request();
        $restaurant->update([
            'status'            => 'cancelled',
            'cancellation_reason' => $request->reason ?? 'Annulation manuelle',
        ]);
        return back()->with('success', 'Vente annulée.');
    }

    public function monthly(Request $request)
    {
        $month = $request->month ?? now()->month;
        $year  = $request->year ?? now()->year;

        $data = RestaurantSale::whereMonth('sale_date', $month)
            ->whereYear('sale_date', $year)
            ->where('status', 'completed')
            ->selectRaw('DATE(sale_date) as date, SUM(total) as revenue, COUNT(*) as count')
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        $inventory = [
            ['name' => 'Riz (kg)', 'stock' => 150, 'consumed' => 45, 'unit' => 'kg'],
            ['name' => 'Poulet (kg)', 'stock' => 80, 'consumed' => 30, 'unit' => 'kg'],
            ['name' => 'Bière (caisse)', 'stock' => 20, 'consumed' => 8, 'unit' => 'cse'],
        ];

        return view('restaurant.monthly', compact('data', 'inventory', 'month', 'year'));
    }
}
