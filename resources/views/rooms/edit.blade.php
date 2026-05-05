@extends('layouts.app')
@section('title', 'Modifier chambre ' . $room->number)
@section('page_title', 'Modifier chambre')
@section('page_subtitle', 'Edition plus confortable des informations de la chambre')

@section('content')
<div class="page-shell">
    <section class="page-hero">
        <h2>Chambre {{ $room->number }}</h2>
        <p>La page d edition de chambre a ete harmonisee avec le reste du projet pour garder une experience visuelle plus riche et plus fluide.</p>
        <div class="hero-pills">
            <span class="hero-pill">{{ $room->roomType->name }}</span>
            <span class="hero-pill">Etage {{ $room->floor }}</span>
        </div>
    </section>

    <section class="card" style="padding:22px;max-width:900px">
        <form action="{{ route('rooms.update', $room) }}" method="POST" class="section-stack">
            @csrf @method('PUT')
            <div class="detail-grid" style="grid-template-columns:1fr 1fr">
                <div><label style="display:block;margin-bottom:8px;font-size:12px;font-weight:800;color:#64748b;text-transform:uppercase">Numero</label><input type="text" name="number" value="{{ old('number', $room->number) }}" required class="form-input"></div>
                <div><label style="display:block;margin-bottom:8px;font-size:12px;font-weight:800;color:#64748b;text-transform:uppercase">Etage</label><input type="number" name="floor" value="{{ old('floor', $room->floor) }}" min="0" max="50" required class="form-input"></div>
            </div>
            <div>
                <label style="display:block;margin-bottom:8px;font-size:12px;font-weight:800;color:#64748b;text-transform:uppercase">Type</label>
                <select name="room_type_id" required class="form-input">
                    @foreach($roomTypes as $type)
                        <option value="{{ $type->id }}" @selected(old('room_type_id', $room->room_type_id)==$type->id)>{{ $type->name }} · {{ number_format($type->base_price,0,',',' ') }} FCFA/nuit</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label style="display:block;margin-bottom:8px;font-size:12px;font-weight:800;color:#64748b;text-transform:uppercase">Statut</label>
                <select name="status" required class="form-input">
                    <option value="available" @selected(old('status',$room->status)==='available')>Disponible</option>
                    <option value="occupied" @selected(old('status',$room->status)==='occupied')>Occupee</option>
                    <option value="reserved" @selected(old('status',$room->status)==='reserved')>Reservee</option>
                    <option value="maintenance" @selected(old('status',$room->status)==='maintenance')>Maintenance</option>
                </select>
            </div>
            <div>
                <label style="display:block;margin-bottom:8px;font-size:12px;font-weight:800;color:#64748b;text-transform:uppercase">Notes</label>
                <textarea name="notes" rows="4" class="form-input">{{ old('notes', $room->notes) }}</textarea>
            </div>
            <div style="display:flex;gap:10px;flex-wrap:wrap">
                <button type="submit" class="btn-primary">Sauvegarder</button>
                <a href="{{ route('rooms.show', $room) }}" class="btn-secondary">Annuler</a>
            </div>
        </form>
    </section>
</div>
@endsection
