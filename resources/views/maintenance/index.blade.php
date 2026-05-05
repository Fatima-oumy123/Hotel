@extends('layouts.app')
@section('title','Maintenance')
@section('page_title', 'Maintenance')
@section('page_subtitle', 'Tickets d intervention et urgences techniques')

@section('content')
<style>
    .maintenance-page{display:grid;gap:18px}
    .maintenance-filters{
        display:grid;
        grid-template-columns:1fr 1fr 1fr auto auto;
        gap:12px;
        align-items:end;
    }
    .maintenance-filters label{
        display:block;
        margin-bottom:8px;
        font-size:12px;
        font-weight:800;
        color:#5d544b;
        text-transform:uppercase;
        letter-spacing:.08em;
    }
    .ticket-list{display:grid;gap:12px}
    .ticket-card{
        background:#fff;
        border:1px solid #ddbba0;
        padding:18px 20px;
    }
    .ticket-card-head{
        display:flex;
        align-items:flex-start;
        justify-content:space-between;
        gap:16px;
    }
    .priority-dot{
        width:12px;
        height:12px;
        border-radius:999px;
        margin-top:6px;
        flex-shrink:0;
    }
    .priority-dot.urgent{background:#ef4444}
    .priority-dot.high{background:#f97316}
    .priority-dot.medium{background:#f59e0b}
    .priority-dot.low{background:#22c55e}
    @media (max-width:1180px){
        .maintenance-filters{grid-template-columns:1fr}
    }
</style>

<div class="maintenance-page">
    <div class="screen-header">
        <div class="screen-heading">
            <h2>Maintenance</h2>
            <p>Suivi des tickets techniques, des priorites critiques et des interventions par chambre.</p>
        </div>
        <div class="screen-actions">
            <a href="{{ route('maintenance.create') }}" class="btn-primary">Nouveau ticket</a>
        </div>
    </div>

    <div class="metric-grid">
        <article class="metric-tile">
            <div class="metric-title">En attente</div>
            <div class="metric-figure" style="color:#a55a00">{{ $stats['pending'] }}</div>
            <div class="metric-caption">Tickets non traites</div>
        </article>
        <article class="metric-tile">
            <div class="metric-title">En cours</div>
            <div class="metric-figure" style="color:#2563eb">{{ $stats['in_progress'] }}</div>
            <div class="metric-caption">Interventions lancees</div>
        </article>
        <article class="metric-tile">
            <div class="metric-title">Termines</div>
            <div class="metric-figure" style="color:#16a34a">{{ $stats['completed'] }}</div>
            <div class="metric-caption">Tickets clos</div>
        </article>
        <article class="metric-tile">
            <div class="metric-title">Urgents actifs</div>
            <div class="metric-figure" style="color:#dc2626">{{ $stats['urgent'] }}</div>
            <div class="metric-caption danger">Suivi immediat</div>
        </article>
    </div>

    <section class="lx-panel">
        <div class="lx-panel-body">
            <form method="GET" class="maintenance-filters">
                <div>
                    <label>Statut</label>
                    <select name="status" class="form-input">
                        <option value="">Tous</option>
                        <option value="pending" @selected(request('status')==='pending')>En attente</option>
                        <option value="in_progress" @selected(request('status')==='in_progress')>En cours</option>
                        <option value="completed" @selected(request('status')==='completed')>Termine</option>
                    </select>
                </div>
                <div>
                    <label>Priorite</label>
                    <select name="priority" class="form-input">
                        <option value="">Toutes</option>
                        <option value="urgent" @selected(request('priority')==='urgent')>Urgent</option>
                        <option value="high" @selected(request('priority')==='high')>Haute</option>
                        <option value="medium" @selected(request('priority')==='medium')>Moyenne</option>
                        <option value="low" @selected(request('priority')==='low')>Basse</option>
                    </select>
                </div>
                <div>
                    <label>Chambre</label>
                    <select name="room_id" class="form-input">
                        <option value="">Toutes</option>
                        @foreach($rooms as $room)
                            <option value="{{ $room->id }}" @selected(request('room_id')==$room->id)>Ch. {{ $room->number }}</option>
                        @endforeach
                    </select>
                </div>
                <div><button type="submit" class="btn-primary">Filtrer</button></div>
                <div><a href="{{ route('maintenance.index') }}" class="btn-secondary">Reinitialiser</a></div>
            </form>
        </div>
    </section>

    <div class="ticket-list">
        @forelse($tickets as $ticket)
            <article class="ticket-card">
                <div class="ticket-card-head">
                    <div style="display:flex;gap:14px;align-items:flex-start;flex:1">
                        <div class="priority-dot {{ $ticket->priority }}"></div>
                        <div>
                            <div style="display:flex;align-items:center;gap:8px;flex-wrap:wrap">
                                <h3 style="margin:0;font-family:'Outfit',sans-serif;font-size:24px">{{ $ticket->title }}</h3>
                                <span style="font-size:13px;color:#8a8179">Ch.{{ $ticket->room->number }}</span>
                            </div>
                            <p style="margin:8px 0 0;color:#564f49;line-height:1.6">{{ $ticket->description }}</p>
                            <div style="display:flex;gap:14px;flex-wrap:wrap;margin-top:12px;font-size:12px;color:#8a8179">
                                <span>Par {{ $ticket->reporter->name }}</span>
                                <span>{{ $ticket->created_at->diffForHumans() }}</span>
                                @if($ticket->assignee)
                                    <span>Assigne a {{ $ticket->assignee->name }}</span>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div style="display:flex;align-items:center;gap:12px;flex-wrap:wrap;justify-content:flex-end">
                        <span class="badge {{ $ticket->status === 'completed' ? 'badge-success' : ($ticket->status === 'in_progress' ? 'badge-info' : 'badge-warning') }}">
                            {{ match($ticket->status) {
                                'pending' => 'En attente',
                                'in_progress' => 'En cours',
                                'completed' => 'Termine',
                                default => $ticket->status,
                            } }}
                        </span>
                        <a href="{{ route('maintenance.show', $ticket) }}" class="link-accent">Voir</a>
                        <a href="{{ route('maintenance.edit', $ticket) }}" class="link-accent" style="color:#5d5f63">Modifier</a>
                    </div>
                </div>
            </article>
        @empty
            <div class="lx-panel">
                <div class="lx-panel-body" style="text-align:center;color:#8a8179;padding:36px 20px">Aucun ticket de maintenance.</div>
            </div>
        @endforelse
    </div>

    <div>{{ $tickets->withQueryString()->links() }}</div>
</div>
@endsection
