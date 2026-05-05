<?php
namespace App\Http\Controllers\Guest;

use App\Http\Controllers\Controller;
use App\Models\{Reservation, Room, RoomType};
use App\Services\{AvailabilityService, PricingService, InvoiceService};
use App\Jobs\SendReservationEmail;
use Illuminate\Http\Request;

class GuestReservationController extends Controller
{
    public function __construct(
        private AvailabilityService $availability,
        private PricingService $pricing,
        private InvoiceService $invoice
    ) {}

    /**
     * Page unique de réservation (1 étape).
     * On accepte aussi des paramètres GET (depuis la home) pour pré-remplir les dates.
     */
    public function oneStep(Request $request)
    {
        $roomTypes = RoomType::orderBy('name')->get();
        $defaults = [
            'check_in'     => $request->query('check_in', now()->addDay()->toDateString()),
            'check_out'    => $request->query('check_out', now()->addDays(2)->toDateString()),
            'adults'       => (int) $request->query('adults', 2),
            'children'     => (int) $request->query('children', 0),
            'room_type_id' => $request->query('room_type_id'),
        ];

        return view('guest.step2', compact('roomTypes', 'defaults'));
    }

    /**
     * Création de réservation en 1 étape.
     * Choisit automatiquement la première chambre disponible selon la recherche.
     */
    public function book(Request $request)
    {
        $request->validate([
            'check_in'        => 'required|date|after_or_equal:today',
            'check_out'       => 'required|date|after:check_in',
            'adults'          => 'required|integer|min:1|max:10',
            'children'        => 'nullable|integer|min:0|max:10',
            'room_type_id'    => 'nullable|exists:room_types,id',

            'guest_first_name' => 'required|string|max:100',
            'guest_last_name'  => 'required|string|max:100',
            'guest_phone'      => 'required|string|max:20',
            'guest_email'      => 'nullable|email|max:150',
            'guest_dob'        => 'nullable|date',
            'guest_id_number'  => 'nullable|string|max:50',
            'special_requests' => 'nullable|string|max:500',
        ]);

        $rooms = $this->availability->getAvailableRooms(
            $request->check_in,
            $request->check_out,
            $request->room_type_id
        );

        $room = $rooms->first();
        if (!$room) {
            return back()
                ->withInput()
                ->withErrors(['check_out' => 'Aucune chambre n’est disponible pour ces dates. Essayez d’autres dates ou un autre type.']);
        }

        $pricing = $this->pricing->calculatePrice($room->room_type_id, $request->check_in, $request->check_out);

        // Double-check (sécurité concurrentielle)
        if (!$this->availability->checkRoomAvailability($room->id, $request->check_in, $request->check_out)) {
            return back()
                ->withInput()
                ->withErrors(['check_out' => 'Cette chambre vient d’être réservée. Merci de relancer votre demande.']);
        }

        $reservation = Reservation::create([
            'room_id'          => $room->id,
            'guest_first_name' => $request->guest_first_name,
            'guest_last_name'  => $request->guest_last_name,
            'guest_dob'        => $request->guest_dob,
            'guest_id_number'  => $request->guest_id_number,
            'guest_phone'      => $request->guest_phone,
            'guest_email'      => $request->guest_email,
            'check_in'         => $request->check_in,
            'check_out'        => $request->check_out,
            'adults'           => $request->adults,
            'children'         => $request->children ?? 0,
            'special_requests' => $request->special_requests,
            'price_per_night'  => $pricing['price_per_night'],
            'total_amount'     => $pricing['subtotal'],
            'discount'         => $pricing['discount'],
            'tax_amount'       => $pricing['tax_amount'],
            'final_amount'     => $pricing['final_amount'],
            'status'           => 'pending',
        ]);

        $room->update(['status' => 'reserved']);

        if ($reservation->guest_email) {
            SendReservationEmail::dispatch($reservation)->onQueue('emails');
        }

        return redirect()->route('guest.payment', $reservation->guest_token);
    }

    public function search(Request $request)
    {
        $request->validate([
            'check_in'     => 'required|date|after_or_equal:today',
            'check_out'    => 'required|date|after:check_in',
            'adults'       => 'required|integer|min:1|max:10',
            'room_type_id' => 'nullable|exists:room_types,id',
        ]);

        $rooms = $this->availability->getAvailableRooms(
            $request->check_in, $request->check_out, $request->room_type_id
        );

        $roomsWithPricing = $rooms->map(function($room) use ($request) {
            $p = $this->pricing->calculatePrice($room->room_type_id, $request->check_in, $request->check_out);
            return array_merge($room->toArray(), ['pricing' => $p, 'room_model' => $room]);
        });

        session(['guest_search' => [
            'check_in'  => $request->check_in,
            'check_out' => $request->check_out,
            'adults'    => $request->adults,
            'children'  => $request->children ?? 0,
        ]]);

        return view('guest.results', compact('roomsWithPricing', 'request'));
    }

    public function step2(Request $request)
    {
        $request->validate(['room_id' => 'required|exists:rooms,id']);
        $search = session('guest_search');
        if (!$search) return redirect()->route('guest.step1');

        $room    = Room::with('roomType')->findOrFail($request->room_id);
        $pricing = $this->pricing->calculatePrice($room->room_type_id, $search['check_in'], $search['check_out']);

        session(['guest_room_id' => $request->room_id, 'guest_pricing' => $pricing]);

        return view('guest.step2', compact('room', 'pricing', 'search'));
    }

    public function confirm(Request $request)
    {
        $request->validate([
            'guest_first_name' => 'required|string|max:100',
            'guest_last_name'  => 'required|string|max:100',
            'guest_phone'      => 'required|string|max:20',
            'guest_email'      => 'nullable|email|max:150',
            'adults'           => 'required|integer|min:1',
        ]);

        $search  = session('guest_search');
        $roomId  = session('guest_room_id');
        $pricing = session('guest_pricing');

        if (!$search || !$roomId || !$pricing) return redirect()->route('guest.step1');

        if (!$this->availability->checkRoomAvailability($roomId, $search['check_in'], $search['check_out'])) {
            return redirect()->route('guest.step1')->withErrors(['error' => 'Chambre plus disponible.']);
        }

        $reservation = Reservation::create([
            'room_id'          => $roomId,
            'guest_first_name' => $request->guest_first_name,
            'guest_last_name'  => $request->guest_last_name,
            'guest_dob'        => $request->guest_dob,
            'guest_id_number'  => $request->guest_id_number,
            'guest_phone'      => $request->guest_phone,
            'guest_email'      => $request->guest_email,
            'check_in'         => $search['check_in'],
            'check_out'        => $search['check_out'],
            'adults'           => $request->adults,
            'children'         => $request->children ?? 0,
            'special_requests' => $request->special_requests,
            'price_per_night'  => $pricing['price_per_night'],
            'total_amount'     => $pricing['subtotal'],
            'discount'         => $pricing['discount'],
            'tax_amount'       => $pricing['tax_amount'],
            'final_amount'     => $pricing['final_amount'],
            'status'           => 'pending',
        ]);

        Room::find($roomId)->update(['status' => 'reserved']);

        if ($reservation->guest_email) {
            SendReservationEmail::dispatch($reservation)->onQueue('emails');
        }

        return redirect()->route('guest.payment', $reservation->guest_token);
    }

    public function payment(string $token)
    {
        $reservation = Reservation::where('guest_token', $token)->firstOrFail();
        if (!in_array($reservation->status, ['pending', 'confirmed'])) {
            return redirect()->route('guest.step1');
        }
        return view('guest.payment', compact('reservation'));
    }

    public function processPayment(Request $request, string $token)
    {
        $request->validate([
            'payment_method' => 'required|in:card,cash',
            'payment_intent' => 'nullable|string|max:255',
        ]);

        $reservation = Reservation::where('guest_token', $token)->firstOrFail();

        if (!in_array($reservation->status, ['pending', 'confirmed'])) {
            return redirect()->route('guest.step1')->withErrors(['error' => 'Cette réservation ne peut plus être payée.']);
        }

        $paymentStatus = $request->payment_method === 'card' ? 'completed' : 'pending';

        $reservation->payment()->updateOrCreate(
            [],
            [
                'amount'         => $reservation->final_amount,
                'method'         => $request->payment_method,
                'status'         => $paymentStatus,
                'transaction_id' => $request->payment_intent,
                'paid_at'        => $paymentStatus === 'completed' ? now() : null,
            ]
        );

        $reservation->update(['status' => 'confirmed']);

        if ($paymentStatus === 'completed') {
            $this->invoice->generateInvoice($reservation->fresh());
        }

        return redirect()->route('guest.success', $token);
    }

    public function success(string $token)
    {
        $reservation = Reservation::where('guest_token', $token)
            ->with('room.roomType', 'invoice')->firstOrFail();
        return view('guest.success', compact('reservation'));
    }

    public function showCancel() { return view('guest.cancel'); }

    public function processCancel(Request $request)
    {
        $request->validate([
            'booking_number' => 'required|string',
            'guest_phone'    => 'required|string',
        ]);

        $reservation = Reservation::where('booking_number', $request->booking_number)
            ->where('guest_phone', $request->guest_phone)->first();

        if (!$reservation) {
            return back()->withErrors(['booking_number' => 'Aucune réservation trouvée.']);
        }

        if (!in_array($reservation->status, ['pending', 'confirmed'])) {
            return back()->withErrors(['booking_number' => 'Cette réservation ne peut plus être annulée.']);
        }

        $hoursUntilArrival = now()->diffInHours($reservation->check_in, false);
        if ($hoursUntilArrival < config('hotel.cancellation_hours', 48)) {
            return back()->withErrors(['booking_number' => 'Annulation gratuite expirée (moins de 48h).']);
        }

        $reservation->update(['status' => 'cancelled', 'cancellation_reason' => 'Annulation client']);
        $reservation->room->update(['status' => 'available']);

        return back()->with('success', "Réservation {$reservation->booking_number} annulée.");
    }
}
