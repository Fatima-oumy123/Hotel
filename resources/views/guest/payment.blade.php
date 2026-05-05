@extends('layouts.guest')
@section('title', 'Paiement')
@section('hero_eyebrow', 'Finalisation du dossier')
@section('hero_title', 'Choisissez votre mode de paiement.')
@section('hero_copy', 'Vous y etes presque. Validez votre mode de paiement et profitez de votre sejour a Rufisque, en toute serenite.')
@section('card_title', 'Reservation ' . $reservation->booking_number)
@section('card_copy', 'Chambre ' . $reservation->room->number . ' · ' . $reservation->room->roomType->name . ' · total ' . number_format($reservation->final_amount, 0, ',', ' ') . ' ' . config('hotel.currency') . '.')
@section('hero_stats')
    <div class="box"><strong>{{ $reservation->nights }}</strong><span>nuit(s)</span></div>
    <div class="box"><strong>{{ strtoupper($reservation->status) }}</strong><span>statut</span></div>
    <div class="box"><strong>{{ config('hotel.currency') }}</strong><span>paiement local</span></div>
@endsection

@section('content')
<div style="display:grid;grid-template-columns:.95fr 1.15fr;gap:16px">
    <aside class="panel">
        <div class="panel-head"><h3>Recapitulatif</h3></div>
        <div class="panel-body">
            <div style="display:grid;gap:12px;font-size:14px">
                <div style="display:flex;justify-content:space-between"><span style="color:#64748b">Client</span><strong>{{ $reservation->guest_full_name }}</strong></div>
                <div style="display:flex;justify-content:space-between"><span style="color:#64748b">Chambre</span><strong>{{ $reservation->room->number }} · {{ $reservation->room->roomType->name }}</strong></div>
                <div style="display:flex;justify-content:space-between"><span style="color:#64748b">Arrivee</span><strong>{{ $reservation->check_in->format('d/m/Y') }}</strong></div>
                <div style="display:flex;justify-content:space-between"><span style="color:#64748b">Depart</span><strong>{{ $reservation->check_out->format('d/m/Y') }}</strong></div>
                <div style="display:flex;justify-content:space-between;border-top:1px solid #eceef2;padding-top:12px;font-size:18px"><span style="font-weight:800">Total a payer</span><strong style="color:#9a5210">{{ number_format($reservation->final_amount, 0, ',', ' ') }} {{ config('hotel.currency') }}</strong></div>
            </div>
        </div>
    </aside>

    <section class="panel" x-data="{ method: 'cash' }">
        <div class="panel-head"><h3>Mode de paiement</h3></div>
        <div class="panel-body">
            <div style="display:grid;grid-template-columns:repeat(2,minmax(0,1fr));gap:12px;margin-bottom:18px">
                <label @click="method='cash'" style="cursor:pointer">
                    <div :style="method === 'cash' ? 'border-color:#b56518;background:#f8f3ed' : ''" style="padding:18px;border:2px solid #e5e7eb;border-radius:18px;text-align:center">
                        <div style="font-size:28px">💵</div>
                        <strong style="display:block;margin-top:8px">Especes</strong>
                        <div style="margin-top:4px;color:#64748b;font-size:13px">Paiement a la reception</div>
                    </div>
                </label>
                <label @click="method='card'" style="cursor:pointer">
                    <div :style="method === 'card' ? 'border-color:#b56518;background:#f8f3ed' : ''" style="padding:18px;border:2px solid #e5e7eb;border-radius:18px;text-align:center">
                        <div style="font-size:28px">💳</div>
                        <strong style="display:block;margin-top:8px">Carte</strong>
                        <div style="margin-top:4px;color:#64748b;font-size:13px">Paiement securise</div>
                    </div>
                </label>
            </div>

            <div x-show="method === 'cash'" x-transition>
                <div class="alert info" style="margin-bottom:16px">
                    Votre reservation sera confirmee. Presentez le numero <strong>{{ $reservation->booking_number }}</strong> a votre arrivee pour regler a la reception.
                </div>
                <form action="{{ route('guest.payment.process', $reservation->guest_token) }}" method="POST">
                    @csrf
                    <input type="hidden" name="payment_method" value="cash">
                    <button type="submit" class="btn-primary" style="width:100%;padding:16px 18px">Confirmer avec paiement a l arrivee</button>
                </form>
            </div>

            <div x-show="method === 'card'" x-transition>
                <div class="alert success" style="margin-bottom:16px">Paiement securise en ligne. Vos donnees bancaires ne sont pas stockees.</div>
                @if(config('services.stripe.key'))
                    <div id="stripe-form" style="display:grid;gap:14px">
                        <div class="field">
                            <label>Carte bancaire</label>
                            <div id="card-element" style="padding:14px 15px;border:1px solid #d5dbe5;border-radius:14px;background:#f8fafc"></div>
                        </div>
                        <div id="card-errors" class="alert error" style="display:none"></div>
                        <button id="stripe-pay-btn" class="btn-primary" style="width:100%;padding:16px 18px">
                            Payer {{ number_format($reservation->final_amount,0,',',' ') }} {{ config('hotel.currency') }}
                        </button>
                    </div>
                @else
                    <div class="alert info">Le paiement par carte n est pas configure actuellement. Vous pouvez utiliser le paiement a l arrivee.</div>
                @endif
            </div>
        </div>
    </section>
</div>
@endsection

@push('scripts')
@if(config('services.stripe.key'))
<script src="https://js.stripe.com/v3/"></script>
<script>
const stripe = Stripe('{{ config("services.stripe.key") }}');
const elements = stripe.elements();
const card = elements.create('card', { style: { base: { fontSize: '16px' } } });
card.mount('#card-element');

document.getElementById('stripe-pay-btn')?.addEventListener('click', async () => {
    const btn = document.getElementById('stripe-pay-btn');
    const errors = document.getElementById('card-errors');
    btn.disabled = true;
    btn.textContent = 'Traitement...';

    const resp = await fetch('{{ route("stripe.intent") }}', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
        body: JSON.stringify({ amount: {{ $reservation->final_amount }}, reservation_id: {{ $reservation->id }} })
    });
    const payload = await resp.json();

    if (!resp.ok || !payload.client_secret) {
        errors.textContent = payload.message ?? 'Impossible d initialiser le paiement.';
        errors.style.display = 'block';
        btn.disabled = false;
        btn.textContent = 'Payer {{ number_format($reservation->final_amount,0,","," ") }} {{ config("hotel.currency") }}';
        return;
    }

    const { paymentIntent, error } = await stripe.confirmCardPayment(payload.client_secret, {
        payment_method: { card }
    });

    if (error) {
        errors.textContent = error.message;
        errors.style.display = 'block';
        btn.disabled = false;
        btn.textContent = 'Payer {{ number_format($reservation->final_amount,0,","," ") }} {{ config("hotel.currency") }}';
    } else {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '{{ route("guest.payment.process", $reservation->guest_token) }}';
        form.innerHTML = `
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <input type="hidden" name="payment_method" value="card">
            <input type="hidden" name="payment_intent" value="${paymentIntent.id}">
        `;
        document.body.appendChild(form);
        form.submit();
    }
});
</script>
@endif
@endpush
