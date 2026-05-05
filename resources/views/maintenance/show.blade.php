@extends('layouts.app')
@section('title', 'Ticket #' . $maintenance->id)
@section('page_title', 'Ticket maintenance')
@section('page_subtitle', 'Fiche d intervention')

@section('content')
<div class="screen-page">
    <div class="screen-header">
        <div class="screen-heading">
            <h2>{{ $maintenance->title }}</h2>
            <p>Fiche d incident avec chambre concernee, priorite, equipe assignee et notes de resolution.</p>
        </div>
        <div class="screen-actions">
            <a href="{{ route('maintenance.edit', $maintenance) }}" class="btn-primary">Modifier</a>
            <a href="{{ route('maintenance.index') }}" class="btn-secondary">Retour liste</a>
        </div>
    </div>

    <div class="metric-grid" style="grid-template-columns:repeat(3,minmax(0,1fr))">
        <article class="metric-tile">
            <div class="metric-title">Ticket</div>
            <div class="metric-figure">#{{ $maintenance->id }}</div>
            <div class="metric-caption">Dossier technique</div>
        </article>
        <article class="metric-tile">
            <div class="metric-title">Chambre</div>
            <div class="metric-figure">{{ $maintenance->room->number }}</div>
            <div class="metric-caption">Zone concernee</div>
        </article>
        <article class="metric-tile">
            <div class="metric-title">Priorite</div>
            <div class="metric-figure">{{ ucfirst($maintenance->priority) }}</div>
            <div class="metric-caption">Niveau d urgence</div>
        </article>
    </div>

    <div class="lx-grid-two">
        <section class="lx-panel">
            <div class="lx-panel-head">
                <h3>Details du ticket</h3>
            </div>
            <div class="lx-panel-body">
                <div style="display:grid;gap:10px;font-size:14px">
                    <div style="display:flex;justify-content:space-between"><span style="color:#64748b">Chambre</span><a href="{{ route('rooms.show', $maintenance->room) }}" style="font-weight:800;color:#9a5210">Ch.{{ $maintenance->room->number }}</a></div>
                    <div style="display:flex;justify-content:space-between"><span style="color:#64748b">Priorite</span><span>{{ ucfirst($maintenance->priority) }}</span></div>
                    <div style="display:flex;justify-content:space-between"><span style="color:#64748b">Statut</span><span class="badge {{ $maintenance->status === 'completed' ? 'badge-success' : ($maintenance->status === 'in_progress' ? 'badge-info' : 'badge-warning') }}">{{ match($maintenance->status) { 'pending' => 'En attente', 'in_progress' => 'En cours', 'completed' => 'Termine', default => $maintenance->status } }}</span></div>
                    <div style="display:flex;justify-content:space-between"><span style="color:#64748b">Signale par</span><span>{{ $maintenance->reporter->name }}</span></div>
                    @if($maintenance->assignee)<div style="display:flex;justify-content:space-between"><span style="color:#64748b">Assigne a</span><span>{{ $maintenance->assignee->name }}</span></div>@endif
                    <div style="display:flex;justify-content:space-between"><span style="color:#64748b">Cree le</span><span>{{ $maintenance->created_at->format('d/m/Y H:i') }}</span></div>
                    @if($maintenance->resolved_at)<div style="display:flex;justify-content:space-between"><span style="color:#64748b">Resolue le</span><span>{{ $maintenance->resolved_at->format('d/m/Y H:i') }}</span></div>@endif
                </div>
            </div>
        </section>

        <section class="lx-panel">
            <div class="lx-panel-head">
                <h3>Description</h3>
            </div>
            <div class="lx-panel-body">
                <div style="color:#475569;line-height:1.8;font-size:14px">{{ $maintenance->description }}</div>
                @if($maintenance->resolution_notes)
                    <div style="margin-top:16px;padding:16px;background:#f8fafc;border:1px solid #eceef2">
                        <strong>Notes de resolution</strong>
                        <div style="margin-top:8px;color:#475569;font-size:14px;line-height:1.7">{{ $maintenance->resolution_notes }}</div>
                    </div>
                @endif
            </div>
        </section>
    </div>
</div>
@endsection
