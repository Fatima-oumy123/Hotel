@extends('layouts.app')
@section('title', 'Nouvelle chambre')
@section('page_title', 'Nouvelle chambre')
@section('page_subtitle', 'Ajouter une chambre avec un formulaire plus clair et plus elegant')

@section('content')
<div class="page-shell">
    <section class="page-hero">
        <h2>Ajouter une chambre au parc hotelier</h2>
        <p>La page de creation est maintenant plus illustree et moins seche, avec une structure plus confortable pour la saisie reception ou administration.</p>
        <div class="hero-pills">
            <span class="hero-pill">Numero</span>
            <span class="hero-pill">Etage</span>
            <span class="hero-pill">Type</span>
        </div>
    </section>

    <section class="card" style="padding:22px;max-width:860px">
        <form action="{{ route('rooms.store') }}" method="POST" class="section-stack">
            @csrf
            <div class="detail-grid" style="grid-template-columns:1fr 1fr">
                <div>
                    <label style="display:block;margin-bottom:8px;font-size:12px;font-weight:800;color:#64748b;text-transform:uppercase">Numero de chambre</label>
                    <input type="text" name="number" value="{{ old('number') }}" required class="form-input" placeholder="101, 202, 301...">
                    @error('number')<p style="margin-top:6px;color:#dc2626;font-size:12px">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label style="display:block;margin-bottom:8px;font-size:12px;font-weight:800;color:#64748b;text-transform:uppercase">Etage</label>
                    <input type="number" name="floor" value="{{ old('floor', 1) }}" min="0" max="50" required class="form-input">
                </div>
            </div>
            <div>
                <label style="display:block;margin-bottom:8px;font-size:12px;font-weight:800;color:#64748b;text-transform:uppercase">Type de chambre</label>
                <select name="room_type_id" required class="form-input">
                    <option value="">Selectionnez un type...</option>
                    @foreach($roomTypes as $type)
                        <option value="{{ $type->id }}" @selected(old('room_type_id')==$type->id)>{{ $type->name }} · {{ number_format($type->base_price,0,',',' ') }} FCFA/nuit · {{ $type->capacity }} pers.</option>
                    @endforeach
                </select>
                @error('room_type_id')<p style="margin-top:6px;color:#dc2626;font-size:12px">{{ $message }}</p>@enderror
            </div>
            <div>
                <label style="display:block;margin-bottom:8px;font-size:12px;font-weight:800;color:#64748b;text-transform:uppercase">Statut initial</label>
                <select name="status" required class="form-input">
                    <option value="available" @selected(old('status')==='available')>Disponible</option>
                    <option value="maintenance" @selected(old('status')==='maintenance')>En maintenance</option>
                </select>
            </div>
            <div>
                <label style="display:block;margin-bottom:8px;font-size:12px;font-weight:800;color:#64748b;text-transform:uppercase">Notes</label>
                <textarea name="notes" rows="4" class="form-input" placeholder="Informations complementaires...">{{ old('notes') }}</textarea>
            </div>
            <div style="display:flex;gap:10px;flex-wrap:wrap">
                <button type="submit" class="btn-primary">Creer la chambre</button>
                <a href="{{ route('rooms.index') }}" class="btn-secondary">Annuler</a>
            </div>
        </form>
    </section>
</div>
@endsection
