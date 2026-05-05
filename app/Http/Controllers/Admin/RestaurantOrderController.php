<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Reservation;
use App\Models\RestaurantMenuItem;
use App\Models\RestaurantOrder;
use App\Models\RestaurantOrderItem;
use App\Models\Room;
use Illuminate\Http\Request;

class RestaurantOrderController extends Controller
{
    public function index(Request $request)
    {
        $orders = RestaurantOrder::with(['items', 'room'])
            ->when($request->status, fn ($q) => $q->where('status', $request->status))
            ->latest()
            ->paginate(20);

        $menuItems = RestaurantMenuItem::where('is_available', true)->orderBy('name')->get();
        $rooms = Room::orderBy('number')->get();
        $activeReservations = Reservation::whereIn('status', ['confirmed', 'checked_in'])->get();

        return view('restaurant_orders.index', compact('orders', 'menuItems', 'rooms', 'activeReservations'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'reservation_id' => 'nullable|exists:reservations,id',
            'room_id' => 'nullable|exists:rooms,id',
            'customer_name' => 'nullable|string|max:120',
            'notes' => 'nullable|string|max:500',
            'items' => 'required|array|min:1',
            'items.*.menu_item_id' => 'nullable|exists:restaurant_menu_items,id',
            'items.*.quantity' => 'nullable|integer|min:1',
        ]);

        $order = RestaurantOrder::create([
            'reservation_id' => $data['reservation_id'] ?? null,
            'room_id' => $data['room_id'] ?? null,
            'customer_name' => $data['customer_name'] ?? null,
            'notes' => $data['notes'] ?? null,
            'status' => 'pending',
            'payment_status' => 'unpaid',
            'ordered_at' => now(),
        ]);

        $total = 0;

        foreach ($data['items'] as $entry) {
            if (empty($entry['menu_item_id'])) {
                continue;
            }
            $menuItem = RestaurantMenuItem::findOrFail($entry['menu_item_id']);
            $quantity = (int) ($entry['quantity'] ?? 1);
            $lineTotal = $quantity * (float) $menuItem->price;
            $total += $lineTotal;

            RestaurantOrderItem::create([
                'restaurant_order_id' => $order->id,
                'menu_item_id' => $menuItem->id,
                'item_name' => $menuItem->name,
                'quantity' => $quantity,
                'unit_price' => $menuItem->price,
                'total' => $lineTotal,
            ]);
        }

        if ($total <= 0) {
            $order->delete();
            return back()->withErrors(['items' => 'Selectionnez au moins un article valide.']);
        }

        $order->update(['total_amount' => $total]);

        return back()->with('success', 'Commande restaurant creee.');
    }

    public function updateStatus(Request $request, RestaurantOrder $restaurantOrder)
    {
        $data = $request->validate([
            'status' => 'required|in:pending,preparing,served,paid,cancelled',
            'payment_status' => 'nullable|in:unpaid,paid',
        ]);

        if (($data['status'] ?? null) === 'served') {
            $restaurantOrder->served_at = now();
        }

        if (($data['status'] ?? null) === 'paid') {
            $data['payment_status'] = 'paid';
        }

        $restaurantOrder->update($data);

        return back()->with('success', 'Statut de commande mis a jour.');
    }
}
