@extends('layouts.app')
@section('title', 'Chambre ' . $room->number)
@section('page_title', 'Chambre')
@section('page_subtitle', 'Fiche detaillee et historique')

@section('content')
<div class="screen-page">
    <div class="screen-header">
        <div class="screen-heading">
            <h2>Chambre {{ $room->number }}</h2>
            <p>Fiche complete de la chambre avec informations, reservations recentes et suivi maintenance.</p>
        </div>
        <div class="screen-actions">
            @can('rooms.edit')
                <a href="{{ route('rooms.edit', $room) }}" class="btn-primary">Modifier</a>
            @endcan
            <a href="{{ route('reservations.create', ['room_id' => $room->id]) }}" class="btn-secondary">Nouvelle reservation</a>
            <a href="{{ route('rooms.index') }}" class="btn-secondary">Retour liste</a>
        </div>
    </div>

    <div class="metric-grid" style="grid-template-columns:repeat(3,minmax(0,1fr))">
        <article class="metric-tile">
            <div class="metric-title">Type</div>
            <div class="metric-figure">{{ $room->roomType->name }}</div>
            <div class="metric-caption">Etage {{ $room->floor }}</div>
        </article>
        <article class="metric-tile">
            <div class="metric-title">Prix / nuit</div>
            <div class="metric-figure">{{ number_format($room->roomType->base_price,0,',',' ') }}</div>
            <div class="metric-caption">{{ config('hotel.currency', 'FCFA') }}</div>
        </article>
        <article class="metric-tile">
            <div class="metric-title">Statut</div>
            <div class="metric-figure">{{ ucfirst($room->status) }}</div>
            <div class="metric-caption">{{ $room->roomType->capacity }} personne(s)</div>
        </article>
    </div>

    <div class="lx-grid-two">
        <section class="lx-panel">
            <div class="lx-panel-head">
                <h3>Informations</h3>
            </div>
            <div class="lx-panel-body">
                <div style="display:grid;gap:10px;font-size:14px">
                    <div style="display:flex;justify-content:space-between"><span style="color:#64748b">Numero</span><strong>{{ $room->number }}</strong></div>
                    <div style="display:flex;justify-content:space-between"><span style="color:#64748b">Type</span><span>{{ $room->roomType->name }}</span></div>
                    <div style="display:flex;justify-content:space-between"><span style="color:#64748b">Capacite</span><span>{{ $room->roomType->capacity }} pers.</span></div>
                    <div style="display:flex;justify-content:space-between"><span style="color:#64748b">Prix/nuit</span><strong style="color:#9a5210">{{ number_format($room->roomType->base_price,0,',',' ') }} {{ config('hotel.currency', 'FCFA') }}</strong></div>
                    <div style="display:flex;justify-content:space-between"><span style="color:#64748b">Statut</span><span class="badge {{ match($room->status) { 'available' => 'badge-success', 'occupied' => 'badge-info', 'reserved' => 'badge-warning', default => 'badge-danger', } }}">{{ $room->status }}</span></div>
                </div>
                @if($room->roomType->amenities)
                    <div style="margin-top:16px;display:flex;gap:8px;flex-wrap:wrap">
                        @foreach($room->roomType->amenities as $amenity)
                            <span class="soft-tag gray">{{ $amenity }}</span>
                        @endforeach
                    </div>
                @endif
            </div>
        </section>

        <section class="lx-panel">
            <div class="lx-panel-head">
                <h3>Reservations recentes</h3>
            </div>
            <div class="lx-panel-body" style="display:grid;gap:10px">
                @forelse($room->reservations as $r)
                    <div style="padding:14px;background:#fcfaf8;border:1px solid #ead9cb">
                        <div style="display:flex;justify-content:space-between;gap:10px;align-items:center">
                            <div>
                                <div style="font-weight:800">{{ $r->guest_full_name }}</div>
                                <div style="margin-top:4px;color:#64748b;font-size:13px">{{ $r->check_in->format('d/m/Y') }} → {{ $r->check_out->format('d/m/Y') }} · {{ $r->nights }} nuit(s)</div>
                            </div>
                            <span class="badge {{ $r->status_badge }}">{{ $r->status }}</span>
                        </div>
                    </div>
                @empty
                    <div style="color:#64748b">Aucune reservation recente.</div>
                @endforelse
            </div>
        </section>
    </div>

    <section class="lx-panel">
        <div class="lx-panel-head">
            <h3>Maintenance</h3>
        </div>
        <div class="lx-panel-body" style="display:grid;gap:10px">
            @forelse($room->maintenanceTickets as $ticket)
                <div style="padding:14px;background:#fcfaf8;border:1px solid #ead9cb">
                    <div style="font-weight:800">{{ $ticket->title }}</div>
                    <div style="margin-top:4px;color:#64748b;font-size:13px">{{ $ticket->created_at->format('d/m/Y') }}</div>
                </div>
            @empty
                <div style="color:#64748b">Aucun ticket de maintenance.</div>
            @endforelse
        </div>
    </section>
</div>
@endsection
