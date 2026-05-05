@extends('layouts.app')
@section('title', 'Nouveau ticket maintenance')
@section('page_title', 'Nouveau ticket maintenance')
@section('page_subtitle', 'Signalement plus clair des incidents sur chambres et equipements')

@section('content')
<div class="page-shell">
    <section class="page-hero">
        <h2>Declarer un incident de maintenance</h2>
        <p>La creation de ticket adopte une presentation plus douce et plus guidee pour aider la reception ou la maintenance a remonter un probleme rapidement.</p>
        <div class="hero-pills">
            <span class="hero-pill">Chambre</span>
            <span class="hero-pill">Priorite</span>
            <span class="hero-pill">Assignation</span>
        </div>
    </section>

    <section class="card" style="padding:22px;max-width:900px">
        <form action="{{ route('maintenance.store') }}" method="POST" class="section-stack">
            @csrf
            <div class="detail-grid" style="grid-template-columns:1fr 1fr">
                <div>
                    <label style="display:block;margin-bottom:8px;font-size:12px;font-weight:800;color:#64748b;text-transform:uppercase">Chambre</label>
                    <select name="room_id" required class="form-input">
                        <option value="">Selectionnez...</option>
                        @foreach($rooms as $room)
                            <option value="{{ $room->id }}" @selected(request('room_id')==$room->id || old('room_id')==$room->id)>
                                Ch.{{ $room->number }} · {{ $room->roomType->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('room_id')<p style="margin-top:6px;color:#dc2626;font-size:12px">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label style="display:block;margin-bottom:8px;font-size:12px;font-weight:800;color:#64748b;text-transform:uppercase">Priorite</label>
                    <select name="priority" required class="form-input">
                        <option value="low" @selected(old('priority')==='low')>Basse</option>
                        <option value="medium" @selected(old('priority','medium')==='medium')>Moyenne</option>
                        <option value="high" @selected(old('priority')==='high')>Haute</option>
                        <option value="urgent" @selected(old('priority')==='urgent')>Urgente</option>
                    </select>
                </div>
            </div>
            <div>
                <label style="display:block;margin-bottom:8px;font-size:12px;font-weight:800;color:#64748b;text-transform:uppercase">Titre du probleme</label>
                <input type="text" name="title" value="{{ old('title') }}" required class="form-input" placeholder="Climatiseur en panne, fuite d eau...">
                @error('title')<p style="margin-top:6px;color:#dc2626;font-size:12px">{{ $message }}</p>@enderror
            </div>
            <div>
                <label style="display:block;margin-bottom:8px;font-size:12px;font-weight:800;color:#64748b;text-transform:uppercase">Description</label>
                <textarea name="description" rows="5" required class="form-input" placeholder="Decrivez le probleme en detail...">{{ old('description') }}</textarea>
                @error('description')<p style="margin-top:6px;color:#dc2626;font-size:12px">{{ $message }}</p>@enderror
            </div>
            <div>
                <label style="display:block;margin-bottom:8px;font-size:12px;font-weight:800;color:#64748b;text-transform:uppercase">Assigne a</label>
                <select name="assigned_to" class="form-input">
                    <option value="">Non assigne</option>
                    @foreach($technicians as $tech)
                        <option value="{{ $tech->id }}" @selected(old('assigned_to')==$tech->id)>{{ $tech->name }}</option>
                    @endforeach
                </select>
            </div>
            <div style="display:flex;gap:10px;flex-wrap:wrap">
                <button type="submit" class="btn-primary">Creer le ticket</button>
                <a href="{{ route('maintenance.index') }}" class="btn-secondary">Annuler</a>
            </div>
        </form>
    </section>
</div>
@endsection
