<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\{Customer, Reservation, Room};
use App\Services\{AvailabilityService, PricingService, InvoiceService};
use Illuminate\Http\Request;

class ReservationController extends Controller
{
    public function __construct(
        private AvailabilityService $availabilityService,
        private PricingService $pricingService,
        private InvoiceService $invoiceService
    ) {}

    public function index(Request $request)
    {
        $reservations = Reservation::with(['room.roomType'])
            ->when($request->status, fn($q) => $q->where('status', $request->status))
            ->when($request->search, fn($q) => $q->where(function ($q2) use ($request) {
                $q2->where('booking_number', 'like', "%{$request->search}%")
                   ->orWhere('guest_last_name', 'like', "%{$request->search}%")
                   ->orWhere('guest_phone', 'like', "%{$request->search}%");
            }))
            ->latest()
            ->paginate(20);

        $rooms = Room::with('roomType')->where('status', 'available')->orderBy('number')->get();

        $kpis = [
            'checkins_today' => Reservation::where('status', 'checked_in')
                ->whereDate('check_in', today())->count(),
            'pending' => Reservation::where('status', 'pending')->count(),
            'available_rooms' => Room::where('status', 'available')->count(),
        ];

        return view('reservations.index', compact('reservations', 'rooms', 'kpis'));
    }

    public function create(Request $request)
    {
        $rooms = Room::with('roomType')->where('status', 'available')->get();
        return view('reservations.create', compact('rooms'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'room_id'          => 'required|exists:rooms,id',
            'guest_first_name' => 'required|string|max:100',
            'guest_last_name'  => 'required|string|max:100',
            'guest_phone'      => 'required|string|max:20',
            'guest_email'      => 'nullable|email',
            'check_in'         => 'required|date|after_or_equal:today',
            'check_out'        => 'required|date|after:check_in',
            'adults'           => 'required|integer|min:1',
            'children'         => 'nullable|integer|min:0',
        ]);

        if (!$this->availabilityService->checkRoomAvailability(
            $request->room_id, $request->check_in, $request->check_out
        )) {
            return back()->withErrors(['room_id' => 'Cette chambre n\'est pas disponible pour ces dates.']);
        }

        $room    = Room::findOrFail($request->room_id);
        $pricing = $this->pricingService->calculatePrice($room->room_type_id, $request->check_in, $request->check_out);
        $customer = Customer::firstOrCreate(
            ['phone' => $request->guest_phone],
            [
                'first_name' => $request->guest_first_name,
                'last_name' => $request->guest_last_name,
                'email' => $request->guest_email,
            ]
        );

        $reservation = Reservation::create([
            ...$request->only([
                'room_id', 'guest_first_name', 'guest_last_name', 'guest_dob',
                'guest_id_number', 'guest_phone', 'guest_email',
                'check_in', 'check_out', 'adults', 'children', 'special_requests',
            ]),
            'customer_id'     => $customer->id,
            'user_id'         => auth()->id(),
            'price_per_night' => $pricing['price_per_night'],
            'total_amount'    => $pricing['subtotal'],
            'discount'        => $pricing['discount'],
            'tax_amount'      => $pricing['tax_amount'],
            'final_amount'    => $pricing['final_amount'],
            'status'          => 'confirmed',
        ]);

        $room->update(['status' => 'reserved']);

        return redirect()->route('reservations.show', $reservation)
            ->with('success', "Réservation {$reservation->booking_number} créée avec succès.");
    }

    public function show(Reservation $reservation)
    {
        $reservation->load(['room.roomType', 'payment', 'invoice', 'user']);
        return view('reservations.show', compact('reservation'));
    }

    public function edit(Reservation $reservation)
    {
        $rooms = Room::with('roomType')->get();
        return view('reservations.edit', compact('reservation', 'rooms'));
    }

    public function update(Request $request, Reservation $reservation)
    {
        $request->validate([
            'guest_first_name' => 'required|string|max:100',
            'guest_last_name'  => 'required|string|max:100',
            'guest_phone'      => 'required|string|max:20',
            'special_requests' => 'nullable|string|max:500',
        ]);

        $reservation->update($request->only([
            'guest_first_name', 'guest_last_name', 'guest_phone',
            'guest_email', 'special_requests', 'adults', 'children',
        ]));

        if ($reservation->customer) {
            $reservation->customer->update([
                'first_name' => $request->guest_first_name,
                'last_name' => $request->guest_last_name,
                'phone' => $request->guest_phone,
                'email' => $request->guest_email,
            ]);
        }

        return redirect()->route('reservations.show', $reservation)
            ->with('success', 'Réservation mise à jour.');
    }

    public function destroy(Reservation $reservation)
    {
        if (in_array($reservation->status, ['checked_in'])) {
            return back()->withErrors(['error' => 'Impossible de supprimer une réservation en cours.']);
        }
        $reservation->room->update(['status' => 'available']);
        $reservation->delete();
        return redirect()->route('reservations.index')->with('success', 'Réservation supprimée.');
    }

    public function checkIn(int $id)
    {
        $reservation = Reservation::findOrFail($id);

        if ($reservation->status !== 'confirmed') {
            return back()->withErrors(['error' => 'Seules les réservations confirmées peuvent faire l\'objet d\'un check-in.']);
        }

        $reservation->update([
            'status'           => 'checked_in',
            'actual_check_in'  => now()->format('H:i:s'),
        ]);
        $reservation->room->update(['status' => 'occupied']);

        return back()->with('success', "Check-in effectué pour {$reservation->guest_full_name}.");
    }

    public function checkOut(int $id)
    {
        $reservation = Reservation::findOrFail($id);

        if ($reservation->status !== 'checked_in') {
            return back()->withErrors(['error' => 'Seules les réservations en cours peuvent faire l\'objet d\'un check-out.']);
        }

        $reservation->update([
            'status'            => 'checked_out',
            'actual_check_out'  => now()->format('H:i:s'),
        ]);
        $reservation->room->update(['status' => 'available']);

        // Générer la facture automatiquement au check-out
        $this->invoiceService->generateInvoice($reservation);

        return back()->with('success', "Check-out effectué. Facture générée automatiquement.");
    }

    public function calendar()
    {
        $reservations = Reservation::with('room')
            ->whereIn('status', ['confirmed', 'checked_in'])
            ->whereDate('check_out', '>=', now())
            ->get()
            ->map(fn($r) => [
                'id'    => $r->id,
                'title' => $r->guest_full_name . ' — Ch.' . $r->room->number,
                'start' => $r->check_in->format('Y-m-d'),
                'end'   => $r->check_out->format('Y-m-d'),
                'color' => $r->status === 'checked_in' ? '#10b981' : '#3b82f6',
            ]);

        return view('reservations.calendar', ['reservations' => $reservations->toJson()]);
    }
}
