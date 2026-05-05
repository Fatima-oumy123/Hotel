@extends('layouts.guest')
@section('title', 'Reservation confirmee')
@section('hero_eyebrow', 'Confirmation')
@section('hero_title', 'Votre reservation est confirmee.')
@section('hero_copy', 'Merci. Conservez votre numero de reservation : il vous servira pour toute demande. A tres bientot a Rufisque.')
@section('card_title', 'Numero de reservation')
@section('card_copy', $reservation->booking_number)
@section('hero_stats')
    <div class="box"><strong>{{ $reservation->nights }}</strong><span>nuit(s)</span></div>
    <div class="box"><strong>{{ $reservation->room->number }}</strong><span>chambre</span></div>
    <div class="box"><strong>{{ number_format($reservation->final_amount, 0, ',', ' ') }}</strong><span>{{ config('hotel.currency') }}</span></div>
@endsection

@section('content')
<div class="panel">
    <div class="panel-body" style="display:grid;grid-template-columns:1fr .9fr;gap:16px;align-items:start">
        <div>
            <div class="alert success" style="font-size:15px;margin-bottom:16px">Merci {{ $reservation->guest_first_name }}, votre sejour est bien enregistre et votre dossier a ete confirme.</div>
            <div style="display:grid;gap:12px;font-size:14px">
                <div style="display:flex;justify-content:space-between"><span style="color:#64748b">Numero</span><strong>{{ $reservation->booking_number }}</strong></div>
                <div style="display:flex;justify-content:space-between"><span style="color:#64748b">Chambre</span><strong>{{ $reservation->room->number }} · {{ $reservation->room->roomType->name }}</strong></div>
                <div style="display:flex;justify-content:space-between"><span style="color:#64748b">Arrivee</span><strong>{{ $reservation->check_in->format('d/m/Y') }}</strong></div>
                <div style="display:flex;justify-content:space-between"><span style="color:#64748b">Depart</span><strong>{{ $reservation->check_out->format('d/m/Y') }}</strong></div>
                <div style="display:flex;justify-content:space-between"><span style="color:#64748b">Montant total</span><strong style="color:#9a5210">{{ number_format($reservation->final_amount,0,',',' ') }} {{ config('hotel.currency') }}</strong></div>
            </div>

            <div style="display:flex;gap:10px;flex-wrap:wrap;margin-top:22px">
                @if($reservation->invoice && $reservation->invoice->pdf_path)
                    <a href="{{ asset('storage/'.$reservation->invoice->pdf_path) }}" target="_blank" class="btn-secondary">Telecharger la facture</a>
                @endif
                <a href="{{ route('guest.cancel') }}" class="btn-secondary">Gerer ma reservation</a>
                <a href="{{ route('guest.step1') }}" class="btn-primary">Nouvelle reservation</a>
            </div>
        </div>

        <div style="padding:20px;border-radius:20px;background:#f8f3ed;border:1px solid #eddcc7">
            <h3 style="margin:0;font-family:'Outfit',sans-serif;font-size:30px">Prochaines etapes</h3>
            <div style="margin-top:14px;display:grid;gap:12px">
                <div style="padding:14px;border-radius:14px;background:#fff;border:1px solid #eceef2">
                    <strong>Conservez votre numero</strong>
                    <div style="margin-top:6px;color:#64748b;font-size:13px">Gardez {{ $reservation->booking_number }} pour toute modification ou verification.</div>
                </div>
                <div style="padding:14px;border-radius:14px;background:#fff;border:1px solid #eceef2">
                    <strong>Preparation de l arrivee</strong>
                    <div style="margin-top:6px;color:#64748b;font-size:13px">Une piece d identite et votre contact de reservation seront utiles a l accueil.</div>
                </div>
                @if($reservation->guest_email)
                    <div style="padding:14px;border-radius:14px;background:#fff;border:1px solid #eceef2">
                        <strong>Confirmation envoyee</strong>
                        <div style="margin-top:6px;color:#64748b;font-size:13px">Un email de confirmation a ete envoye a {{ $reservation->guest_email }}.</div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
