<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\Reservation;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function index(Request $request)
    {
        $payments = Payment::with('reservation')
            ->when($request->method, function ($q) use ($request) {
                if ($request->method === 'mobile_money') {
                    return $q->where('method', 'transfer')->where('local_channel', 'mobile_money');
                }
                return $q->where('method', $request->method);
            })
            ->when($request->status, fn($q) => $q->where('status', $request->status))
            ->when($request->date_from, fn($q) => $q->whereDate('paid_at', '>=', $request->date_from))
            ->when($request->date_to, fn($q) => $q->whereDate('paid_at', '<=', $request->date_to))
            ->latest()
            ->paginate(20);

        $stats = [
            'total_today'    => Payment::whereDate('paid_at', today())->where('status', 'completed')->sum('amount'),
            'total_month'    => Payment::whereMonth('paid_at', now()->month)->where('status', 'completed')->sum('amount'),
            'pending_count'  => Payment::where('status', 'pending')->count(),
            'total_count'    => Payment::where('status', 'completed')->count(),
        ];

        return view('payments.index', compact('payments', 'stats'));
    }

    public function create(Request $request)
    {
        $reservation = null;
        if ($request->reservation_id) {
            $reservation = Reservation::with('room.roomType')->findOrFail($request->reservation_id);
        }
        $reservations = Reservation::with('room')
            ->whereIn('status', ['confirmed', 'checked_in'])
            ->whereDoesntHave('payment', fn($q) => $q->where('status', 'completed'))
            ->get();
        return view('payments.create', compact('reservations', 'reservation'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'reservation_id' => 'required|exists:reservations,id',
            'amount'         => 'required|numeric|min:0.01',
            'method'         => 'required|in:card,cash,check,transfer,mobile_money',
            'payer_phone'    => 'nullable|string|max:30',
            'reference'      => 'nullable|string|max:100',
            'notes'          => 'nullable|string|max:500',
        ]);

        $reservation = Reservation::findOrFail($request->reservation_id);

        $method = $request->method === 'mobile_money' ? 'transfer' : $request->method;
        $localChannel = $request->method === 'mobile_money' ? 'mobile_money' : null;

        $payment = Payment::create([
            'reservation_id' => $request->reservation_id,
            'amount'         => $request->amount,
            'method'         => $method,
            'local_channel'  => $localChannel,
            'payer_phone'    => $request->payer_phone,
            'status'         => 'completed',
            'reference'      => $request->reference,
            'notes'          => $request->notes,
            'paid_at'        => now(),
        ]);

        return redirect()->route('payments.show', $payment)->with('success', 'Paiement enregistré.');
    }

    public function show(Payment $payment)
    {
        $payment->load('reservation.room.roomType');
        return view('payments.show', compact('payment'));
    }

    public function refund(Payment $payment)
    {
        if ($payment->status !== 'completed') {
            return back()->withErrors(['error' => 'Ce paiement ne peut pas être remboursé.']);
        }
        $payment->update(['status' => 'refunded']);
        return back()->with('success', 'Paiement remboursé.');
    }

    public function createStripeIntent(Request $request)
    {
        $request->validate(['amount' => 'required|numeric|min:100', 'reservation_id' => 'required|exists:reservations,id']);

        if (!config('services.stripe.key') || !config('services.stripe.secret')) {
            return response()->json([
                'message' => 'Le paiement par carte n\'est pas configuré.',
            ], 422);
        }

        if (!class_exists(\Stripe\Stripe::class) || !class_exists(\Stripe\PaymentIntent::class)) {
            return response()->json([
                'message' => 'La bibliothèque Stripe n\'est pas installée sur le serveur.',
            ], 500);
        }

        \Stripe\Stripe::setApiKey(config('services.stripe.secret'));

        $intent = \Stripe\PaymentIntent::create([
            'amount'   => (int)($request->amount * 100),
            'currency' => 'xof',
            'metadata' => ['reservation_id' => $request->reservation_id],
        ]);

        return response()->json(['client_secret' => $intent->client_secret]);
    }
}
