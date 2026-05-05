@extends('layouts.app')
@section('title', 'Paiement #' . $payment->id)
@section('page_title', 'Paiement')
@section('page_subtitle', 'Lecture detaillee de l encaissement')

@section('content')
<div class="screen-page">
    <div class="screen-header">
        <div class="screen-heading">
            <h2>{{ number_format($payment->amount,0,',',' ') }} {{ config('hotel.currency', 'FCFA') }}</h2>
            <p>Dossier d encaissement lie a une reservation, avec methode de paiement, reference et etat de traitement.</p>
        </div>
        <div class="screen-actions">
            <a href="{{ route('reservations.show', $payment->reservation) }}" class="btn-secondary">Voir reservation</a>
            @if($payment->status === 'completed')
                <form action="{{ route('payments.refund', $payment) }}" method="POST" onsubmit="return confirm('Confirmer le remboursement ?')">
                    @csrf @method('PATCH')
                    <button type="submit" class="btn-primary">Rembourser</button>
                </form>
            @endif
        </div>
    </div>

    <div class="metric-grid" style="grid-template-columns:repeat(3,minmax(0,1fr))">
        <article class="metric-tile">
            <div class="metric-title">Methode</div>
            <div class="metric-figure">{{ $payment->method_label }}</div>
            <div class="metric-caption">Canal de paiement</div>
        </article>
        <article class="metric-tile">
            <div class="metric-title">Statut</div>
            <div class="metric-figure">{{ ucfirst($payment->status) }}</div>
            <div class="metric-caption">Traitement courant</div>
        </article>
        <article class="metric-tile">
            <div class="metric-title">Reservation</div>
            <div class="metric-figure">{{ $payment->reservation->booking_number }}</div>
            <div class="metric-caption">Dossier lie</div>
        </article>
    </div>

    <div class="lx-grid-two">
        <section class="lx-panel">
            <div class="lx-panel-head">
                <h3>Encaissement</h3>
            </div>
            <div class="lx-panel-body">
                <div style="display:grid;gap:10px;font-size:14px">
                    <div style="display:flex;justify-content:space-between"><span style="color:#64748b">Montant</span><strong>{{ number_format($payment->amount,0,',',' ') }} {{ config('hotel.currency', 'FCFA') }}</strong></div>
                    <div style="display:flex;justify-content:space-between"><span style="color:#64748b">Methode</span><span>{{ $payment->method_label }}</span></div>
                    <div style="display:flex;justify-content:space-between"><span style="color:#64748b">Statut</span><span class="badge {{ $payment->status_badge }}">{{ ucfirst($payment->status) }}</span></div>
                    <div style="display:flex;justify-content:space-between"><span style="color:#64748b">Date</span><span>{{ $payment->paid_at?->format('d/m/Y à H:i') ?? 'Non defini' }}</span></div>
                    @if($payment->transaction_id)<div style="display:flex;justify-content:space-between"><span style="color:#64748b">Transaction</span><span style="font-family:monospace;font-size:12px">{{ $payment->transaction_id }}</span></div>@endif
                    @if($payment->reference)<div style="display:flex;justify-content:space-between"><span style="color:#64748b">Reference</span><span>{{ $payment->reference }}</span></div>@endif
                </div>
            </div>
        </section>

        <section class="lx-panel">
            <div class="lx-panel-head">
                <h3>Reservation liee</h3>
            </div>
            <div class="lx-panel-body">
                <div style="display:grid;gap:10px;font-size:14px">
                    <div style="display:flex;justify-content:space-between"><span style="color:#64748b">Numero</span><a href="{{ route('reservations.show', $payment->reservation) }}" style="color:#9a5210;font-weight:800">{{ $payment->reservation->booking_number }}</a></div>
                    <div style="display:flex;justify-content:space-between"><span style="color:#64748b">Client</span><span>{{ $payment->reservation->guest_full_name }}</span></div>
                    <div style="display:flex;justify-content:space-between"><span style="color:#64748b">Sejour</span><span>{{ $payment->reservation->check_in->format('d/m/Y') }} - {{ $payment->reservation->check_out->format('d/m/Y') }}</span></div>
                    @if($payment->notes)
                        <div style="margin-top:10px;padding:14px;background:#f8fafc;border:1px solid #eceef2">
                            <strong>Notes</strong>
                            <div style="margin-top:8px;color:#475569">{{ $payment->notes }}</div>
                        </div>
                    @endif
                </div>
            </div>
        </section>
    </div>
</div>
@endsection
