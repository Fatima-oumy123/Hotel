<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Reservation;
use App\Models\Room;
use App\Models\RoomType;
use App\Services\AvailabilityService;
use App\Services\PricingService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ApiReservationController extends Controller
{
    public function __construct(
        private AvailabilityService $availability,
        private PricingService $pricing
    ) {}

    public function index(Request $request): JsonResponse
    {
        $query = Reservation::with(['room.roomType', 'payment']);

        if ($request->status) {
            $query->where('status', $request->status);
        }

        if ($request->from_date) {
            $query->whereDate('check_in', '>=', $request->from_date);
        }

        if ($request->to_date) {
            $query->whereDate('check_out', '<=', $request->to_date);
        }

        $reservations = $query->latest()->paginate($request->per_page ?? 20);

        return response()->json($reservations);
    }

    public function show(Reservation $reservation): JsonResponse
    {
        $reservation->load(['room.roomType', 'payment', 'invoice', 'user']);

        return response()->json($reservation);
    }

    public function checkAvailability(Request $request): JsonResponse
    {
        $request->validate([
            'check_in' => 'required|date|after_or_equal:today',
            'check_out' => 'required|date|after:check_in',
            'room_type_id' => 'nullable|exists:room_types,id',
        ]);

        $rooms = $this->availability->getAvailableRooms(
            $request->check_in,
            $request->check_out,
            $request->room_type_id
        );

        $result = $rooms->map(function ($room) use ($request) {
            $pricing = $this->pricing->calculatePrice(
                $room->room_type_id,
                $request->check_in,
                $request->check_out
            );

            return [
                'id' => $room->id,
                'number' => $room->number,
                'floor' => $room->floor,
                'room_type' => $room->roomType,
                'pricing' => $pricing,
            ];
        });

        return response()->json([
            'available' => $result->isNotEmpty(),
            'rooms' => $result->values(),
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'room_id' => 'required|exists:rooms,id',
            'guest_first_name' => 'required|string|max:100',
            'guest_last_name' => 'required|string|max:100',
            'guest_phone' => 'required|string|max:30',
            'guest_email' => 'nullable|email|max:150',
            'check_in' => 'required|date|after_or_equal:today',
            'check_out' => 'required|date|after:check_in',
            'adults' => 'nullable|integer|min:1|max:10',
            'children' => 'nullable|integer|min:0',
            'special_requests' => 'nullable|string|max:1000',
        ]);

        if (! $this->availability->checkRoomAvailability(
            $request->room_id,
            $request->check_in,
            $request->check_out
        )) {
            return response()->json([
                'success' => false,
                'message' => 'La chambre n\'est pas disponible pour ces dates.',
            ], 422);
        }

        $room = Room::findOrFail($request->room_id);
        $pricing = $this->pricing->calculatePrice(
            $room->room_type_id,
            $request->check_in,
            $request->check_out
        );

        $year = date('Y');
        $count = Reservation::whereYear('created_at', $year)->count() + 1;
        $bookingNumber = 'REZ-'.$year.'-'.str_pad($count, 5, '0', STR_PAD_LEFT);

        $reservation = Reservation::create([
            'booking_number' => $bookingNumber,
            'room_id' => $request->room_id,
            'guest_first_name' => $request->guest_first_name,
            'guest_last_name' => $request->guest_last_name,
            'guest_phone' => $request->guest_phone,
            'guest_email' => $request->guest_email,
            'check_in' => $request->check_in,
            'check_out' => $request->check_out,
            'adults' => $request->adults ?? 1,
            'children' => $request->children ?? 0,
            'special_requests' => $request->special_requests,
            'price_per_night' => $pricing['price_per_night'],
            'total_amount' => $pricing['subtotal'],
            'discount' => $pricing['discount'],
            'tax_amount' => $pricing['tax_amount'],
            'final_amount' => $pricing['final_amount'],
            'status' => 'pending',
            'guest_token' => Str::random(32),
        ]);

        $room->update(['status' => 'reserved']);

        return response()->json([
            'success' => true,
            'message' => 'Réservation créée avec succès.',
            'data' => $reservation->load('room.roomType'),
        ], 201);
    }

    public function cancel(Reservation $reservation): JsonResponse
    {
        if (! in_array($reservation->status, ['pending', 'confirmed'])) {
            return response()->json([
                'success' => false,
                'message' => 'Cette réservation ne peut pas être annulée.',
            ], 422);
        }

        $hoursUntilArrival = now()->diffInHours($reservation->check_in, false);

        if ($hoursUntilArrival < config('hotel.cancellation_hours', 48)) {
            return response()->json([
                'success' => false,
                'message' => 'Le délai d\'annulation gratuite est expiré.',
            ], 422);
        }

        $reservation->update([
            'status' => 'cancelled',
            'cancellation_reason' => 'Annulation client via API',
        ]);

        $reservation->room->update(['status' => 'available']);

        return response()->json([
            'success' => true,
            'message' => 'Réservation annulée avec succès.',
        ]);
    }

    public function roomTypes(): JsonResponse
    {
        $roomTypes = RoomType::withCount('rooms')->orderBy('name')->get();

        return response()->json($roomTypes);
    }
}
