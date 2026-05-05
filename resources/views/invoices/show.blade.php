@extends('layouts.app')
@section('title', $invoice->invoice_number)
@section('page_title', 'Facture client')
@section('page_subtitle', 'Presentation plus premium du document de facturation')

@section('content')
<div class="page-shell">
    <section class="page-hero">
        <h2>{{ $invoice->invoice_number }}</h2>
        <p>La lecture de facture a ete retravaillee pour rappeler une vraie experience de document hotelier premium, sans l aspect sec d un simple tableau brut.</p>
        <div class="hero-pills">
            <span class="hero-pill">{{ ucfirst($invoice->status) }}</span>
            <span class="hero-pill">{{ $invoice->reservation->guest_full_name }}</span>
            <span class="hero-pill">{{ number_format($invoice->total,0,',',' ') }} FCFA</span>
        </div>
    </section>

    <div style="display:flex;gap:10px;flex-wrap:wrap">
        <a href="{{ route('invoices.download', $invoice) }}" class="btn-primary">Telecharger PDF</a>
        <a href="{{ route('invoices.index') }}" class="btn-secondary">Retour liste</a>
    </div>

    <section class="card" style="padding:26px">
        <div style="display:flex;justify-content:space-between;gap:18px;flex-wrap:wrap;padding-bottom:20px;border-bottom:1px solid #e5e7eb">
            <div>
                <div style="font-family:'Outfit',sans-serif;font-size:26px;font-weight:800">{{ config('hotel.name', config('app.name')) }}</div>
                <div style="margin-top:6px;color:#64748b;font-size:13px">{{ config('hotel.address', 'Dakar, Senegal') }}</div>
            </div>
            <div style="text-align:right">
                <div style="font-family:'Outfit',sans-serif;font-size:34px">FACTURE</div>
                <div style="margin-top:6px;color:#9a5210;font-weight:800">{{ $invoice->invoice_number }}</div>
                <div style="margin-top:6px;color:#64748b;font-size:13px">Emise le {{ $invoice->issued_at?->format('d/m/Y') }}</div>
            </div>
        </div>

        <div class="detail-grid" style="grid-template-columns:1fr 1fr;margin-top:20px">
            <div class="detail-card" style="box-shadow:none">
                <h3>Facture a</h3>
                <div style="display:grid;gap:6px;color:#475569;font-size:14px">
                    <strong>{{ $invoice->reservation->guest_full_name }}</strong>
                    <span>{{ $invoice->reservation->guest_phone }}</span>
                    @if($invoice->reservation->guest_email)<span>{{ $invoice->reservation->guest_email }}</span>@endif
                </div>
            </div>
            <div class="detail-card" style="box-shadow:none">
                <h3>Sejour</h3>
                <div style="display:grid;gap:6px;color:#475569;font-size:14px">
                    <span>Reservation : <strong>{{ $invoice->reservation->booking_number }}</strong></span>
                    <span>Chambre : <strong>{{ $invoice->reservation->room->number }} · {{ $invoice->reservation->room->roomType->name }}</strong></span>
                    <span>Arrivee : {{ $invoice->reservation->check_in->format('d/m/Y') }}</span>
                    <span>Depart : {{ $invoice->reservation->check_out->format('d/m/Y') }}</span>
                </div>
            </div>
        </div>

        <div style="margin-top:20px;overflow:auto">
            <table class="table">
                <thead>
                    <tr>
                        <th>Description</th>
                        <th>Nuits</th>
                        <th>Prix/nuit</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Hebergement · {{ $invoice->reservation->room->roomType->name }} (Ch.{{ $invoice->reservation->room->number }})</td>
                        <td>{{ $invoice->reservation->nights }}</td>
                        <td>{{ number_format($invoice->reservation->price_per_night,0,',',' ') }} FCFA</td>
                        <td>{{ number_format($invoice->subtotal,0,',',' ') }} FCFA</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div style="display:flex;justify-content:flex-end;margin-top:18px">
            <div style="width:min(360px,100%);display:grid;gap:10px;font-size:14px">
                <div style="display:flex;justify-content:space-between"><span style="color:#64748b">Sous total</span><span>{{ number_format($invoice->subtotal,0,',',' ') }} FCFA</span></div>
                @if($invoice->reservation->discount > 0)<div style="display:flex;justify-content:space-between;color:#16a34a"><span>Reduction</span><span>-{{ number_format($invoice->reservation->discount,0,',',' ') }} FCFA</span></div>@endif
                <div style="display:flex;justify-content:space-between"><span style="color:#64748b">TVA ({{ $invoice->tax_rate }}%)</span><span>{{ number_format($invoice->tax_amount,0,',',' ') }} FCFA</span></div>
                @if($invoice->stay_tax > 0)<div style="display:flex;justify-content:space-between"><span style="color:#64748b">Taxe de sejour</span><span>{{ number_format($invoice->stay_tax,0,',',' ') }} FCFA</span></div>@endif
                <div style="display:flex;justify-content:space-between;border-top:1px solid #e5e7eb;padding-top:10px;font-size:18px"><strong>Total TTC</strong><strong style="color:#9a5210">{{ number_format($invoice->total,0,',',' ') }} FCFA</strong></div>
            </div>
        </div>
    </section>
</div>
@endsection
