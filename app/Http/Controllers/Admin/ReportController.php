<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\{Reservation, Payment, Room, Expense, Employee};
use App\Services\AvailabilityService;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $month = $request->month ?? now()->month;
        $year  = $request->year  ?? now()->year;

        $from = Carbon::create($year, $month, 1)->startOfMonth();
        $to   = Carbon::create($year, $month, 1)->endOfMonth();

        // Réservations du mois
        $reservations = Reservation::whereBetween('check_in', [$from, $to])->get();

        // Revenus par jour
        $revenueByDay = Payment::whereBetween('paid_at', [$from, $to])
            ->where('status', 'completed')
            ->selectRaw('DATE(paid_at) as date, SUM(amount) as total')
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Taux occupation par jour
        $totalRooms = Room::count() ?: 1;
        $occupancyByDay = collect(range(0, $from->diffInDays($to)))->map(function($d) use ($from, $totalRooms) {
            $date = $from->copy()->addDays($d);
            $occupied = Reservation::where('check_in', '<=', $date)
                ->where('check_out', '>', $date)
                ->whereIn('status', ['confirmed', 'checked_in', 'checked_out'])
                ->count();
            return [
                'date' => $date->format('d/m'),
                'rate' => round(($occupied / $totalRooms) * 100, 1),
            ];
        });

        $stats = [
            'total_reservations'  => $reservations->count(),
            'total_revenue'       => Payment::whereBetween('paid_at', [$from, $to])->where('status', 'completed')->sum('amount'),
            'total_expenses'      => Expense::whereBetween('expense_date', [$from, $to])->sum('amount'),
            'total_nights'        => $reservations->sum('nights'),
            'avg_occupancy'       => $occupancyByDay->avg('rate'),
            'cancelled'           => $reservations->where('status', 'cancelled')->count(),
            'arrivals'            => $reservations->where('status', '!=', 'cancelled')->count(),
            'revenue_per_room'    => $totalRooms > 0 ? round(Payment::whereBetween('paid_at', [$from, $to])->where('status', 'completed')->sum('amount') / $totalRooms, 0) : 0,
        ];

        $topRooms = Reservation::with('room.roomType')
            ->whereBetween('check_in', [$from, $to])
            ->selectRaw('room_id, COUNT(*) as bookings, SUM(final_amount) as revenue')
            ->groupBy('room_id')
            ->orderByDesc('revenue')
            ->take(5)
            ->get();

        $paymentJournal = Payment::with('reservation')
            ->whereBetween('paid_at', [$from, $to])
            ->latest('paid_at')
            ->take(6)
            ->get();

        $paymentDistribution = Payment::whereBetween('paid_at', [$from, $to])
            ->where('status', 'completed')
            ->get()
            ->groupBy(fn (Payment $payment) => $payment->method_label)
            ->map(fn ($items, $label) => [
                'label' => $label,
                'amount' => (float) $items->sum('amount'),
            ])
            ->values();

        $distributionTotal = $paymentDistribution->sum('amount');
        $paymentDistribution = $paymentDistribution->map(function ($item) use ($distributionTotal) {
            $item['percent'] = $distributionTotal > 0 ? round(($item['amount'] / $distributionTotal) * 100) : 0;
            return $item;
        })->sortByDesc('amount')->values();

        $yearlyRevenue = collect(range(1, 8))->map(function ($monthIndex) use ($year) {
            $monthStart = Carbon::create($year, $monthIndex, 1)->startOfMonth();
            $monthEnd = $monthStart->copy()->endOfMonth();
            $total = Payment::whereBetween('paid_at', [$monthStart, $monthEnd])
                ->where('status', 'completed')
                ->sum('amount');

            return [
                'label' => strtoupper($monthStart->isoFormat('MMM')),
                'amount' => (float) $total,
                'tax' => round($total * 0.18, 0),
            ];
        });

        return view('reports.index', compact(
            'stats',
            'revenueByDay',
            'occupancyByDay',
            'topRooms',
            'month',
            'year',
            'paymentJournal',
            'paymentDistribution',
            'yearlyRevenue'
        ));
    }

    public function export(Request $request)
    {
        $month = $request->month ?? now()->month;
        $year  = $request->year  ?? now()->year;
        $type  = $request->type  ?? 'pdf';

        $from = Carbon::create($year, $month, 1)->startOfMonth();
        $to   = Carbon::create($year, $month, 1)->endOfMonth();

        $reservations = Reservation::with('room.roomType')
            ->whereBetween('check_in', [$from, $to])
            ->get();

        if ($type === 'pdf') {
            $pdf = Pdf::loadView('pdf.report', compact('reservations', 'from', 'to', 'month', 'year'))
                ->setPaper('a4', 'landscape');
            return $pdf->download("rapport-{$year}-{$month}.pdf");
        }

        // Export CSV
        $headers = ['Content-Type' => 'text/csv', 'Content-Disposition' => "attachment; filename=rapport-{$year}-{$month}.csv"];
        $callback = function() use ($reservations) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, ['N° Réservation','Client','Chambre','Arrivée','Départ','Nuits','Montant','Statut']);
            foreach ($reservations as $r) {
                fputcsv($handle, [
                    $r->booking_number, $r->guest_full_name,
                    $r->room->number, $r->check_in->format('d/m/Y'),
                    $r->check_out->format('d/m/Y'), $r->nights,
                    $r->final_amount, $r->status,
                ]);
            }
            fclose($handle);
        };
        return response()->stream($callback, 200, $headers);
    }
}
