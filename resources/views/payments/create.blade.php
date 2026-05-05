@extends('layouts.app')
@section('title', 'Nouveau paiement')
@section('page_title', 'Enregistrer un paiement')
@section('page_subtitle', 'Saisie assistee pour especes, carte, virement ou Mobile Money')

@section('content')
<div class="page-shell">
    <section class="page-hero">
        <h2>Encaisser avec une interface plus chaleureuse</h2>
        <p>Le formulaire de paiement suit maintenant la meme logique visuelle que le reste de l application, avec une meilleure mise en scene des informations de reservation et des methodes locales.</p>
        <div class="hero-pills">
            <span class="hero-pill">Especes</span>
            <span class="hero-pill">Carte</span>
            <span class="hero-pill">Mobile Money</span>
        </div>
    </section>

    @if($reservation)
        <section class="detail-card" style="max-width:860px">
            <h3>Reservation liee</h3>
            <div style="display:flex;justify-content:space-between;gap:16px;flex-wrap:wrap">
                <div>
                    <div style="font-weight:800">{{ $reservation->booking_number }}</div>
                    <div style="margin-top:6px;color:#64748b;font-size:13px">{{ $reservation->guest_full_name }} · Ch.{{ $reservation->room->number }}</div>
                </div>
                <div style="text-align:right">
                    <div style="font-size:12px;color:#64748b;text-transform:uppercase;font-weight:800">Total a payer</div>
                    <div style="margin-top:6px;font-family:'Outfit',sans-serif;font-size:32px;color:#9a5210">{{ number_format($reservation->final_amount,0,',',' ') }} FCFA</div>
                </div>
            </div>
        </section>
    @endif

    <section class="card" style="padding:22px;max-width:860px">
        <form action="{{ route('payments.store') }}" method="POST" class="section-stack">
            @csrf
            <div>
                <label style="display:block;margin-bottom:8px;font-size:12px;font-weight:800;color:#64748b;text-transform:uppercase">Reservation</label>
                <select name="reservation_id" required class="form-input">
                    <option value="">Selectionnez une reservation...</option>
                    @foreach($reservations as $r)
                        <option value="{{ $r->id }}" @selected(($reservation && $reservation->id == $r->id) || old('reservation_id') == $r->id)>
                            {{ $r->booking_number }} · {{ $r->guest_full_name }} · {{ number_format($r->final_amount,0,',',' ') }} FCFA
                        </option>
                    @endforeach
                </select>
                @error('reservation_id')<p style="margin-top:6px;color:#dc2626;font-size:12px">{{ $message }}</p>@enderror
            </div>
            <div class="detail-grid" style="grid-template-columns:1fr 1fr">
                <div>
                    <label style="display:block;margin-bottom:8px;font-size:12px;font-weight:800;color:#64748b;text-transform:uppercase">Montant</label>
                    <input type="number" name="amount" step="0.01" min="0" value="{{ old('amount', $reservation?->final_amount) }}" required class="form-input" placeholder="0">
                    @error('amount')<p style="margin-top:6px;color:#dc2626;font-size:12px">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label style="display:block;margin-bottom:8px;font-size:12px;font-weight:800;color:#64748b;text-transform:uppercase">Methode</label>
                    <select name="method" required class="form-input">
                        <option value="cash" @selected(old('method')==='cash')>Especes</option>
                        <option value="card" @selected(old('method')==='card')>Carte bancaire</option>
                        <option value="check" @selected(old('method')==='check')>Cheque</option>
                        <option value="transfer" @selected(old('method')==='transfer')>Virement</option>
                        <option value="mobile_money" @selected(old('method')==='mobile_money')>Mobile Money</option>
                    </select>
                </div>
            </div>
            <div>
                <label style="display:block;margin-bottom:8px;font-size:12px;font-weight:800;color:#64748b;text-transform:uppercase">Telephone payeur</label>
                <input type="text" name="payer_phone" value="{{ old('payer_phone') }}" class="form-input" placeholder="+221 77 000 00 00">
            </div>
            <div>
                <label style="display:block;margin-bottom:8px;font-size:12px;font-weight:800;color:#64748b;text-transform:uppercase">Reference</label>
                <input type="text" name="reference" value="{{ old('reference') }}" class="form-input" placeholder="Numero de recu ou reference transaction">
            </div>
            <div>
                <label style="display:block;margin-bottom:8px;font-size:12px;font-weight:800;color:#64748b;text-transform:uppercase">Notes</label>
                <textarea name="notes" rows="3" class="form-input" placeholder="Remarques...">{{ old('notes') }}</textarea>
            </div>
            <div style="display:flex;gap:10px;flex-wrap:wrap">
                <button type="submit" class="btn-primary">Enregistrer le paiement</button>
                <a href="{{ route('payments.index') }}" class="btn-secondary">Annuler</a>
            </div>
        </form>
    </section>
</div>
@endsection
