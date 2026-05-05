<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Expense;
use App\Models\Payment;
use App\Models\Reservation;
use App\Models\Room;
use App\Services\AvailabilityService;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index(AvailabilityService $availability)
    {
        $today = Carbon::today();

        $stats = [
            'occupancy_rate' => $availability->getOccupancyRate(),
            'arrivals_today' => Reservation::whereDate('check_in', $today)->whereIn('status', ['confirmed'])->count(),
            'departures_today' => Reservation::whereDate('check_out', $today)->where('status', 'checked_in')->count(),
            'total_rooms' => Room::count(),
            'available_rooms' => Room::where('status', 'available')->count(),
            'occupied_rooms' => Room::where('status', 'occupied')->count(),
            'maintenance_rooms' => Room::where('status', 'maintenance')->count(),
            'revenue_today' => Payment::whereDate('paid_at', $today)->where('status', 'completed')->sum('amount'),
            'revenue_month' => Payment::whereMonth('paid_at', $today->month)->where('status', 'completed')->sum('amount'),
            'expenses_month' => Expense::whereMonth('expense_date', $today->month)->sum('amount'),
            'pending_reservations' => Reservation::where('status', 'pending')->count(),
        ];

        $revenueChart = collect(range(0, 29))->map(fn ($d) => [
            'date' => Carbon::today()->subDays($d)->format('d/m'),
            'revenue' => Payment::whereDate('paid_at', Carbon::today()->subDays($d))
                ->where('status', 'completed')->sum('amount'),
        ]);

        $recentReservations = Reservation::with('room.roomType')->latest()->take(10)->get();
        $roomPreview = Room::orderBy('number')->take(5)->get();

        return view('dashboard.index', compact('stats', 'revenueChart', 'recentReservations', 'roomPreview'));
    }
}
