@extends('layouts.app')
@section('title', $reservation->booking_number)
@section('page_title', 'Reservation')
@section('page_subtitle', 'Dossier client et facturation')

@section('content')
<div class="screen-page">
    <div class="screen-header">
        <div class="screen-heading">
            <h2>{{ $reservation->booking_number }}</h2>
            <p>Fiche complete du sejour avec informations client, chambre et situation de paiement.</p>
        </div>
        <div class="screen-actions">
            @if($reservation->status === 'confirmed')
                <form action="{{ route('reservations.checkin', $reservation->id) }}" method="POST">@csrf<button type="submit" class="btn-primary">Check-in</button></form>
            @endif
            @if($reservation->status === 'checked_in')
                <form action="{{ route('reservations.checkout', $reservation->id) }}" method="POST">@csrf<button type="submit" class="btn-secondary">Check-out</button></form>
            @endif
            @if($reservation->invoice)
                <a href="{{ route('invoices.download', $reservation->invoice) }}" class="btn-secondary">Facture PDF</a>
            @endif
            @if(!$reservation->invoice && in_array($reservation->status, ['checked_out']))
                <a href="{{ route('invoices.generate', $reservation->id) }}" class="btn-primary">Generer facture</a>
            @endif
        </div>
    </div>

    <div class="metric-grid" style="grid-template-columns:repeat(3,minmax(0,1fr))">
        <article class="metric-tile">
            <div class="metric-title">Statut</div>
            <div class="metric-figure">{{ ucfirst($reservation->status_label) }}</div>
            <div class="metric-caption">{{ $reservation->nights }} nuit(s)</div>
        </article>
        <article class="metric-tile">
            <div class="metric-title">Chambre</div>
            <div class="metric-figure">{{ $reservation->room->number }}</div>
            <div class="metric-caption">{{ $reservation->room->roomType->name }}</div>
        </article>
        <article class="metric-tile">
            <div class="metric-title">Total TTC</div>
            <div class="metric-figure">{{ number_format($reservation->final_amount,0,',',' ') }}</div>
            <div class="metric-caption">{{ config('hotel.currency', 'FCFA') }}</div>
        </article>
    </div>

    <div class="lx-grid-two">
        <section class="lx-panel">
            <div class="lx-panel-head">
                <h3>Client</h3>
            </div>
            <div class="lx-panel-body">
                <div style="display:flex;align-items:center;gap:12px;margin-bottom:16px">
                    <div style="width:54px;height:54px;border-radius:999px;background:#f8e9d8;color:#9a5210;display:flex;align-items:center;justify-content:center;font-weight:800">
                        {{ strtoupper(substr($reservation->guest_first_name,0,1) . substr($reservation->guest_last_name,0,1)) }}
                    </div>
                    <div>
                        <div style="font-weight:800">{{ $reservation->guest_full_name }}</div>
                        <div style="color:#64748b;font-size:13px">{{ $reservation->guest_phone }}</div>
                    </div>
                </div>
                <div style="display:grid;gap:10px;font-size:14px">
                    @if($reservation->guest_email)<div style="display:flex;justify-content:space-between"><span style="color:#64748b">Email</span><span>{{ $reservation->guest_email }}</span></div>@endif
                    @if($reservation->guest_dob)<div style="display:flex;justify-content:space-between"><span style="color:#64748b">Naissance</span><span>{{ \Carbon\Carbon::parse($reservation->guest_dob)->format('d/m/Y') }}</span></div>@endif
                    @if($reservation->guest_id_number)<div style="display:flex;justify-content:space-between"><span style="color:#64748b">Piece</span><span>{{ $reservation->guest_id_number }}</span></div>@endif
                    <div style="display:flex;justify-content:space-between"><span style="color:#64748b">Adultes</span><span>{{ $reservation->adults }}</span></div>
                    @if($reservation->children > 0)<div style="display:flex;justify-content:space-between"><span style="color:#64748b">Enfants</span><span>{{ $reservation->children }}</span></div>@endif
                </div>
            </div>
        </section>

        <section class="lx-panel">
            <div class="lx-panel-head">
                <h3>Sejour</h3>
            </div>
            <div class="lx-panel-body">
                <div style="padding:16px;background:#f8fafc;border:1px solid #eceef2">
                    <div style="display:flex;justify-content:space-between"><span style="color:#64748b">Arrivee</span><strong>{{ $reservation->check_in->format('d/m/Y') }}</strong></div>
                    <div style="display:flex;justify-content:space-between;margin-top:10px"><span style="color:#64748b">Depart</span><strong>{{ $reservation->check_out->format('d/m/Y') }}</strong></div>
                    <div style="display:flex;justify-content:space-between;margin-top:10px"><span style="color:#64748b">Duree</span><strong>{{ $reservation->nights }} nuit(s)</strong></div>
                </div>
                <div style="margin-top:14px;padding:16px;background:#f8f3ed;border:1px solid #eddcc7">
                    <div style="font-weight:800">Chambre {{ $reservation->room->number }}</div>
                    <div style="margin-top:6px;color:#64748b;font-size:13px">{{ $reservation->room->roomType->name }} · Etage {{ $reservation->room->floor }}</div>
                </div>
                @if($reservation->special_requests)
                    <div style="margin-top:14px;padding:16px;background:#eef6ff;border:1px solid #dbeafe">
                        <div style="font-weight:800">Demandes speciales</div>
                        <div style="margin-top:6px;color:#475569;font-size:13px">{{ $reservation->special_requests }}</div>
                    </div>
                @endif
            </div>
        </section>
    </div>

    <section class="lx-panel">
        <div class="lx-panel-head">
            <h3>Facturation</h3>
        </div>
        <div class="lx-panel-body">
            <div style="display:grid;gap:10px;font-size:14px;max-width:520px">
                <div style="display:flex;justify-content:space-between"><span style="color:#64748b">Prix/nuit</span><span>{{ number_format($reservation->price_per_night,0,',',' ') }} {{ config('hotel.currency', 'FCFA') }}</span></div>
                <div style="display:flex;justify-content:space-between"><span style="color:#64748b">{{ $reservation->nights }} nuit(s)</span><span>{{ number_format($reservation->total_amount,0,',',' ') }} {{ config('hotel.currency', 'FCFA') }}</span></div>
                @if($reservation->discount > 0)<div style="display:flex;justify-content:space-between;color:#16a34a"><span>Reduction</span><span>-{{ number_format($reservation->discount,0,',',' ') }} {{ config('hotel.currency', 'FCFA') }}</span></div>@endif
                <div style="display:flex;justify-content:space-between"><span style="color:#64748b">TVA</span><span>{{ number_format($reservation->tax_amount,0,',',' ') }} {{ config('hotel.currency', 'FCFA') }}</span></div>
                <div style="display:flex;justify-content:space-between;border-top:1px solid #eceef2;padding-top:10px;font-size:18px"><strong>Total TTC</strong><strong style="color:#9a5210">{{ number_format($reservation->final_amount,0,',',' ') }} {{ config('hotel.currency', 'FCFA') }}</strong></div>
            </div>
            @if($reservation->payment)
                <div style="margin-top:14px;padding:16px;background:#f0fdf4;border:1px solid #bbf7d0;max-width:520px">
                    <div style="display:flex;justify-content:space-between;align-items:center">
                        <strong>Paiement enregistre</strong>
                        <span class="badge badge-success">Paye</span>
                    </div>
                    <div style="margin-top:6px;color:#166534;font-size:13px">{{ $reservation->payment->method_label }} · {{ $reservation->payment->paid_at?->format('d/m/Y H:i') }}</div>
                </div>
            @else
                <div style="margin-top:14px">
                    <a href="{{ route('payments.create', ['reservation_id' => $reservation->id]) }}" class="btn-primary">Enregistrer un paiement</a>
                </div>
            @endif
        </div>
    </section>
</div>
@endsection
