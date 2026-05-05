@extends('layouts.app')
@section('title', 'Modifier ticket #' . $maintenance->id)
@section('page_title', 'Modifier ticket maintenance')
@section('page_subtitle', 'Suivi plus confortable des actions correctives')

@section('content')
<div class="page-shell">
    <section class="page-hero">
        <h2>{{ $maintenance->title }}</h2>
        <p>La mise a jour d un ticket maintenance suit maintenant le meme niveau de finition que les autres formulaires de l application.</p>
        <div class="hero-pills">
            <span class="hero-pill">Ticket #{{ $maintenance->id }}</span>
            <span class="hero-pill">{{ ucfirst($maintenance->priority) }}</span>
            <span class="hero-pill">{{ ucfirst($maintenance->status) }}</span>
        </div>
    </section>

    <section class="card" style="padding:22px;max-width:900px">
        <form action="{{ route('maintenance.update', $maintenance) }}" method="POST" class="section-stack">
            @csrf @method('PUT')
            <div class="detail-grid" style="grid-template-columns:1fr 1fr">
                <div>
                    <label style="display:block;margin-bottom:8px;font-size:12px;font-weight:800;color:#64748b;text-transform:uppercase">Statut</label>
                    <select name="status" required class="form-input">
                        <option value="pending" @selected(old('status',$maintenance->status)==='pending')>En attente</option>
                        <option value="in_progress" @selected(old('status',$maintenance->status)==='in_progress')>En cours</option>
                        <option value="completed" @selected(old('status',$maintenance->status)==='completed')>Termine</option>
                    </select>
                </div>
                <div>
                    <label style="display:block;margin-bottom:8px;font-size:12px;font-weight:800;color:#64748b;text-transform:uppercase">Priorite</label>
                    <select name="priority" required class="form-input">
                        <option value="low" @selected(old('priority',$maintenance->priority)==='low')>Basse</option>
                        <option value="medium" @selected(old('priority',$maintenance->priority)==='medium')>Moyenne</option>
                        <option value="high" @selected(old('priority',$maintenance->priority)==='high')>Haute</option>
                        <option value="urgent" @selected(old('priority',$maintenance->priority)==='urgent')>Urgente</option>
                    </select>
                </div>
            </div>
            <div>
                <label style="display:block;margin-bottom:8px;font-size:12px;font-weight:800;color:#64748b;text-transform:uppercase">Assigne a</label>
                <select name="assigned_to" class="form-input">
                    <option value="">Non assigne</option>
                    @foreach($technicians as $tech)
                        <option value="{{ $tech->id }}" @selected(old('assigned_to',$maintenance->assigned_to)==$tech->id)>{{ $tech->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label style="display:block;margin-bottom:8px;font-size:12px;font-weight:800;color:#64748b;text-transform:uppercase">Notes de resolution</label>
                <textarea name="resolution_notes" rows="5" class="form-input" placeholder="Actions effectuees, pieces remplacees, observations...">{{ old('resolution_notes', $maintenance->resolution_notes) }}</textarea>
            </div>
            <div style="display:flex;gap:10px;flex-wrap:wrap">
                <button type="submit" class="btn-primary">Mettre a jour</button>
                <a href="{{ route('maintenance.show', $maintenance) }}" class="btn-secondary">Annuler</a>
            </div>
        </form>
    </section>
</div>
@endsection
