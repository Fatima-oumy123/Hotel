@extends('layouts.app')
@section('title', 'Chambres')
@section('page_title', 'Chambres')
@section('page_subtitle', 'Suivi de l occupation et des disponibilites')

@section('content')
<style>
    .rooms-page{display:grid;gap:18px}
    .room-cards{
        display:grid;
        grid-template-columns:repeat(3,minmax(0,1fr));
        gap:14px;
    }
    .room-card{
        background:#fff;
        border:1px solid #ddbba0;
        padding:20px;
        min-height:170px;
    }
    .room-card.available{border-color:#22c55e;background:#f2fcf5}
    .room-card.occupied{border-color:#b05f09;background:#fff8f1}
    .room-card.maintenance{border-color:#1f2937;background:#f3f4f6}
    .room-card h3{
        margin:0;
        font-family:'Outfit',sans-serif;
        font-size:34px;
    }
    .room-card small{
        display:block;
        margin-top:6px;
        color:#6b7280;
        text-transform:uppercase;
        letter-spacing:.08em;
        font-weight:800;
    }
    .room-card .price{
        margin-top:14px;
        font-size:26px;
        font-weight:800;
    }
    .room-card .meta{
        margin-top:10px;
        color:#625c56;
        font-size:14px;
        line-height:1.6;
    }
    .room-card .actions{
        margin-top:16px;
        display:flex;
        gap:8px;
        flex-wrap:wrap;
    }
    .room-side{
        display:grid;
        gap:16px;
    }
    .room-list{
        display:grid;
        gap:10px;
    }
    .room-list-item{
        display:flex;
        align-items:center;
        justify-content:space-between;
        gap:12px;
        border:1px solid #ead9cb;
        padding:12px 14px;
        background:#fcfaf8;
    }
    .trend-chart{
        height:220px;
        display:flex;
        align-items:flex-end;
        gap:10px;
    }
    .trend-col{
        flex:1;
        background:#eceff3;
        position:relative;
    }
    .trend-col span{
        position:absolute;
        left:0;
        right:0;
        bottom:0;
        background:#a55a00;
    }
    .trend-labels{
        display:flex;
        justify-content:space-between;
        margin-top:10px;
        color:#7b746d;
        font-size:12px;
        text-transform:uppercase;
        font-weight:700;
    }
    .filter-form{
        display:grid;
        grid-template-columns:1.3fr 1.3fr auto auto;
        gap:12px;
        align-items:end;
    }
    .filter-form label{
        display:block;
        margin-bottom:8px;
        font-size:12px;
        font-weight:800;
        color:#5d544b;
        text-transform:uppercase;
        letter-spacing:.08em;
    }
    @media (max-width:1180px){
        .room-cards,.lx-grid-main{grid-template-columns:1fr}
    }
    @media (max-width:760px){
        .filter-form{grid-template-columns:1fr}
    }
</style>

<div class="rooms-page">
    <div class="screen-header">
        <div class="screen-heading">
            <h2>Gestion des Chambres</h2>
            <p>Controle visuel des chambres disponibles, occupees, reservees et en maintenance.</p>
        </div>
        <div class="screen-actions">
            @can('rooms.create')
                <a href="{{ route('rooms.create') }}" class="btn-primary">Nouvelle chambre</a>
            @endcan
            <a href="{{ route('reservations.index') }}" class="btn-secondary">Reservations</a>
        </div>
    </div>

    <div class="metric-grid">
        <article class="metric-tile">
            <div class="metric-title">Disponibles</div>
            <div class="metric-figure">{{ $stats['available'] }}</div>
            <div class="metric-caption success">Pretes pour reservation</div>
        </article>
        <article class="metric-tile">
            <div class="metric-title">Occupees</div>
            <div class="metric-figure">{{ $stats['occupied'] }}</div>
            <div class="metric-caption">Clients deja installes</div>
        </article>
        <article class="metric-tile">
            <div class="metric-title">Reservees</div>
            <div class="metric-figure">{{ $stats['reserved'] }}</div>
            <div class="metric-caption">Arrivees en attente</div>
        </article>
        <article class="metric-tile">
            <div class="metric-title">Maintenance</div>
            <div class="metric-figure" style="color:#a55a00">{{ $stats['maintenance'] }}</div>
            <div class="metric-caption danger">Interventions a suivre</div>
        </article>
    </div>

    <section class="lx-panel">
        <div class="lx-panel-body">
            <form method="GET" class="filter-form">
                <div>
                    <label>Statut</label>
                    <select name="status" class="form-input">
                        <option value="">Tous les statuts</option>
                        <option value="available" @selected(request('status') === 'available')>Disponible</option>
                        <option value="occupied" @selected(request('status') === 'occupied')>Occupee</option>
                        <option value="reserved" @selected(request('status') === 'reserved')>Reservee</option>
                        <option value="maintenance" @selected(request('status') === 'maintenance')>Maintenance</option>
                    </select>
                </div>
                <div>
                    <label>Categorie</label>
                    <select name="room_type_id" class="form-input">
                        <option value="">Toutes les categories</option>
                        @foreach($roomTypes as $type)
                            <option value="{{ $type->id }}" @selected(request('room_type_id') == $type->id)>{{ $type->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div><button class="btn-primary" type="submit">Filtrer</button></div>
                <div><a href="{{ route('rooms.index') }}" class="btn-secondary">Reinitialiser</a></div>
            </form>
        </div>
    </section>

    <div class="lx-grid-main">
        <section class="lx-panel">
            <div class="lx-panel-head">
                <h3>Parc de Chambres</h3>
                <span class="link-accent">{{ $rooms->total() }} chambre(s)</span>
            </div>
            <div class="lx-panel-body">
                <div class="room-cards">
                    @forelse($rooms as $room)
                        @php
                            $cls = $room->status === 'available'
                                ? 'available'
                                : (($room->status === 'occupied' || $room->status === 'reserved') ? 'occupied' : 'maintenance');
                        @endphp
                        <article class="room-card {{ $cls }}">
                            <h3>{{ $room->number }}</h3>
                            <small>{{ $room->roomType->name }}</small>
                            <div class="price">{{ number_format((float) $room->roomType->base_price, 0, ',', ' ') }} {{ config('hotel.currency') }}</div>
                            <div class="meta">
                                Etage {{ $room->floor }}<br>
                                Statut: {{ ucfirst($room->status) }}
                            </div>
                            <div class="actions">
                                <a class="btn-secondary" href="{{ route('rooms.show', $room) }}">Voir</a>
                                @can('rooms.edit')
                                    <a class="btn-secondary" href="{{ route('rooms.edit', $room) }}">Modifier</a>
                                @endcan
                            </div>
                        </article>
                    @empty
                        <div style="grid-column:1/-1;text-align:center;color:#64748b">Aucune chambre trouvee.</div>
                    @endforelse
                </div>
                <div style="margin-top:16px">{{ $rooms->withQueryString()->links() }}</div>
            </div>
        </section>

        <div class="room-side">
            <section class="lx-panel">
                <div class="lx-panel-head">
                    <h3>Lecture Rapide</h3>
                </div>
                <div class="lx-panel-body room-list">
                    <div class="room-list-item"><span>Disponible</span><strong>{{ $stats['available'] }}</strong></div>
                    <div class="room-list-item"><span>Occupee</span><strong>{{ $stats['occupied'] }}</strong></div>
                    <div class="room-list-item"><span>Reservee</span><strong>{{ $stats['reserved'] }}</strong></div>
                    <div class="room-list-item"><span>Maintenance</span><strong>{{ $stats['maintenance'] }}</strong></div>
                </div>
            </section>

            <section class="lx-panel">
                <div class="lx-panel-head">
                    <h3>Tendance d Occupation</h3>
                </div>
                <div class="lx-panel-body">
                    <div class="trend-chart">
                        @foreach([54, 68, 61, 84, 96, 73, 48] as $height)
                            <div class="trend-col"><span style="height:{{ $height }}%"></span></div>
                        @endforeach
                    </div>
                    <div class="trend-labels">
                        <span>Lun</span><span>Mar</span><span>Mer</span><span>Jeu</span><span>Ven</span><span>Sam</span><span>Dim</span>
                    </div>
                </div>
            </section>
        </div>
    </div>
</div>
@endsection
