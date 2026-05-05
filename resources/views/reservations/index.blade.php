@extends('layouts.app')
@section('title', 'Reservations')
@section('page_title', 'Reservations')
@section('page_subtitle', 'Suivi en temps reel des arrivees et departs de l hotel.')

@section('content')
@php
    $confirmed = $reservations->where('status', 'confirmed')->count();
    $inHouse = $reservations->where('status', 'checked_in')->count();
    $completed = $reservations->where('status', 'checked_out')->count();
    $cancelled = $reservations->where('status', 'cancelled')->count();
@endphp

<style>
    .reservations-page{display:grid;gap:18px}
    .reservations-layout{
        display:grid;
        grid-template-columns:360px 1fr;
        gap:16px;
        align-items:start;
    }
    .side-card{
        background:#f7efe8;
        border:1px solid var(--line-strong);
        padding:18px;
    }
    .side-card h3{
        margin:0;
        font-family:'Outfit',sans-serif;
        font-size:22px;
    }
    .side-sub{margin-top:6px;color:#6c625a;font-size:13px}
    .mm-box{
        margin-top:14px;
        border:1px solid #decfc3;
        background:#fff;
        padding:14px;
    }
    .mm-title{
        font-size:12px;
        font-weight:800;
        letter-spacing:.12em;
        text-transform:uppercase;
        color:#5d544b;
        display:flex;
        align-items:center;
        gap:8px;
    }
    .mm-ops{display:grid;grid-template-columns:repeat(3,1fr);gap:8px;margin-top:12px}
    .mm-op{
        border:1px solid #e5e7eb;
        background:#f6f7f9;
        border-radius:8px;
        padding:10px 8px;
        font-size:12px;
        font-weight:800;
        color:#344054;
        cursor:pointer;
        text-align:center;
    }
    .mm-op.active{background:#fff3e8;border-color:#f4b47a;color:#9a5210}
    .mm-grid{display:grid;grid-template-columns:64px 1fr;gap:8px;margin-top:12px}
    .mm-prefix{
        border:1px solid #decfc3;
        background:#fceee3;
        border-radius:8px;
        display:flex;
        align-items:center;
        justify-content:center;
        font-weight:800;
        color:#6c625a;
    }
    .mm-actions{margin-top:12px}
    .mm-actions .btn-primary{width:100%;justify-content:center}

    .reservation-filters{
        display:grid;
        grid-template-columns:2fr 1fr auto auto;
        gap:12px;
        align-items:end;
    }
    .reservation-filters label{
        display:block;
        margin-bottom:8px;
        font-size:12px;
        font-weight:800;
        color:#5d544b;
        text-transform:uppercase;
        letter-spacing:.08em;
    }
    .reservation-table th{background:#faf1ea}
    .reservation-table td{font-size:15px}
    .cell-meta{margin-top:4px;color:#8a8179;font-size:12px}
    .inline-actions{display:flex;gap:8px;flex-wrap:wrap}
    .kpi-row{
        display:grid;
        grid-template-columns:repeat(3,minmax(0,1fr));
        gap:12px;
        margin-top:12px;
    }
    .kpi-tile{
        background:#fff;
        border:1px solid var(--line-strong);
        padding:16px 18px;
        display:flex;
        align-items:center;
        justify-content:space-between;
        gap:12px;
    }
    .kpi-tile strong{
        font-family:'Outfit',sans-serif;
        font-size:22px;
    }
    .kpi-tile span{
        font-size:11px;
        letter-spacing:.12em;
        text-transform:uppercase;
        color:#6c625a;
        font-weight:800;
    }
    .top-actions{display:flex;gap:10px;flex-wrap:wrap}
    @media (max-width:980px){
        .reservation-filters{grid-template-columns:1fr}
        .reservations-layout{grid-template-columns:1fr}
    }
</style>

<div class="reservations-page">
    <div class="screen-header">
        <div class="screen-heading">
            <h2>Gestion des Reservations</h2>
            <p>Suivi en temps reel des arrivees et departs, avec creation rapide depuis la reception.</p>
        </div>
        <div class="screen-actions top-actions">
            <form method="GET" style="display:flex;gap:10px;flex-wrap:wrap;align-items:center">
                <select name="status" class="form-input" style="min-width:220px">
                    <option value="">Filtrer par statut</option>
                    <option value="pending" @selected(request('status') === 'pending')>En attente</option>
                    <option value="confirmed" @selected(request('status') === 'confirmed')>Confirmée</option>
                    <option value="checked_in" @selected(request('status') === 'checked_in')>En cours</option>
                    <option value="checked_out" @selected(request('status') === 'checked_out')>Terminée</option>
                    <option value="cancelled" @selected(request('status') === 'cancelled')>Annulée</option>
                </select>
                <input type="text" name="search" value="{{ request('search') }}" class="form-input" style="min-width:240px" placeholder="Rechercher...">
                <button class="btn-secondary" type="submit" style="background:#5f6064;border-color:#5f6064">Filtrer</button>
            </form>
            <a href="{{ route('reports.export', ['month' => now()->month, 'year' => now()->year, 'type' => 'csv']) }}" class="btn-secondary">Exporter rapport</a>
        </div>
    </div>

    <div class="kpi-row">
        <div class="kpi-tile">
            <div>
                <span>Check-ins aujourd hui</span>
                <div><strong>{{ $kpis['checkins_today'] ?? 0 }}</strong></div>
            </div>
            <div class="soft-tag gray">Aujourd hui</div>
        </div>
        <div class="kpi-tile">
            <div>
                <span>En attente</span>
                <div><strong>{{ $kpis['pending'] ?? 0 }}</strong></div>
            </div>
            <div class="soft-tag">Dossiers</div>
        </div>
        <div class="kpi-tile">
            <div>
                <span>Chambres libres</span>
                <div><strong>{{ $kpis['available_rooms'] ?? 0 }}</strong></div>
            </div>
            <div class="soft-tag green">Disponibles</div>
        </div>
    </div>

    <div class="reservations-layout">
        <aside class="panel side-card">
            <h3>Nouvelle Reservation</h3>
            <div class="side-sub">Creation rapide depuis la reception</div>

            <form action="{{ route('reservations.store') }}" method="POST" style="margin-top:14px;display:grid;gap:12px">
                @csrf
                <div class="field">
                    <label>Client</label>
                    <input class="form-input" type="text" name="guest_first_name" placeholder="Prenom" required value="{{ old('guest_first_name') }}">
                </div>
                <div class="field">
                    <label>&nbsp;</label>
                    <input class="form-input" type="text" name="guest_last_name" placeholder="Nom" required value="{{ old('guest_last_name') }}">
                </div>

                <div class="field">
                    <label>Telephone</label>
                    <input class="form-input" type="text" name="guest_phone" placeholder="+221 77 000 00 00" required value="{{ old('guest_phone') }}">
                </div>

                <div class="field">
                    <label>Chambre</label>
                    <select class="form-input" name="room_id" required>
                        <option value="">Select chambre</option>
                        @foreach($rooms as $room)
                            <option value="{{ $room->id }}" @selected((string) old('room_id') === (string) $room->id)>
                                {{ $room->number }} — {{ $room->roomType->name ?? 'Type' }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="lx-grid-two">
                    <div class="field">
                        <label>Arrivee</label>
                        <input class="form-input" type="date" name="check_in" required value="{{ old('check_in', now()->toDateString()) }}">
                    </div>
                    <div class="field">
                        <label>Depart</label>
                        <input class="form-input" type="date" name="check_out" required value="{{ old('check_out', now()->addDay()->toDateString()) }}">
                    </div>
                </div>

                <div class="lx-grid-two">
                    <div class="field">
                        <label>Adultes</label>
                        <select class="form-input" name="adults" required>
                            @for($i=1; $i<=6; $i++)
                                <option value="{{ $i }}" @selected((int) old('adults', 2) === $i)>{{ $i }}</option>
                            @endfor
                        </select>
                    </div>
                    <div class="field">
                        <label>Enfants</label>
                        <select class="form-input" name="children">
                            @for($i=0; $i<=4; $i++)
                                <option value="{{ $i }}" @selected((int) old('children', 0) === $i)>{{ $i }}</option>
                            @endfor
                        </select>
                    </div>
                </div>

                <div class="mm-box" x-data="{ op: 'Orange Money' }">
                    <div class="mm-title">◉ Paiement Mobile Money</div>
                    <div class="mm-ops">
                        <button type="button" class="mm-op" :class="op==='M-Pesa' ? 'active' : ''" @click="op='M-Pesa'">M-Pesa</button>
                        <button type="button" class="mm-op" :class="op==='Orange Money' ? 'active' : ''" @click="op='Orange Money'">Orange Money</button>
                        <button type="button" class="mm-op" :class="op==='MTN' ? 'active' : ''" @click="op='MTN'">MTN</button>
                    </div>
                    <div class="mm-grid">
                        <div class="mm-prefix">+221</div>
                        <input type="text" class="form-input" placeholder="77 000 00 00">
                    </div>
                    <div class="mm-actions">
                        <button class="btn-primary" type="button" style="background:#ff6b00;border-color:#ff6b00">Declencher push USSD</button>
                    </div>
                </div>

                <button class="btn-secondary" type="submit" style="width:100%;justify-content:center;padding:14px 16px;background:#4b4d52;border-color:#4b4d52">
                    Enregistrer reservation
                </button>
            </form>
        </aside>

        <section class="lx-panel">
            <div class="lx-panel-head">
                <h3>Liste des Reservations</h3>
                <span class="link-accent">Affichage de {{ $reservations->firstItem() ?? 0 }} a {{ $reservations->lastItem() ?? 0 }} sur {{ $reservations->total() }} reservations</span>
            </div>
            <div style="overflow:auto">
                <table class="table reservation-table">
                    <thead>
                        <tr>
                            <th>Client & Chambre</th>
                            <th>Sejour</th>
                            <th>Statut</th>
                            <th>Paiement</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($reservations as $reservation)
                            <tr>
                                <td>
                                    <div style="display:flex;gap:12px;align-items:center">
                                        <div class="avatar" style="width:38px;height:38px;background:#ff8608">{{ strtoupper(substr($reservation->guest_first_name,0,1).substr($reservation->guest_last_name,0,1)) }}</div>
                                        <div>
                                            <strong>{{ $reservation->guest_full_name }}</strong>
                                            <div class="cell-meta">Chambre {{ $reservation->room->number }} · {{ $reservation->room->roomType->name }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    {{ $reservation->check_in->format('d M') }} - {{ $reservation->check_out->format('d M') }}
                                    <div class="cell-meta">{{ $reservation->nights }} nuit(s)</div>
                                </td>
                                <td><span class="badge {{ $reservation->status_badge }}">{{ strtoupper($reservation->status_label) }}</span></td>
                                <td>{{ $reservation->payment?->method_label ?? '—' }}</td>
                                <td>
                                    <div class="inline-actions">
                                        @if($reservation->status === 'confirmed')
                                            <form action="{{ route('reservations.checkin', $reservation->id) }}" method="POST">
                                                @csrf
                                                <button class="btn-primary" type="submit" style="background:#8b4b10;border-color:#8b4b10">Check-in</button>
                                            </form>
                                        @endif
                                        @if($reservation->status === 'checked_in')
                                            <form action="{{ route('reservations.checkout', $reservation->id) }}" method="POST">
                                                @csrf
                                                <button class="btn-secondary" type="submit">Check-out</button>
                                            </form>
                                        @endif
                                        <a href="{{ route('reservations.show', $reservation) }}" class="btn-secondary">Voir</a>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" style="text-align:center">Aucune reservation trouvee.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div style="padding:16px 18px">{{ $reservations->withQueryString()->links() }}</div>
        </section>
    </div>
</div>
@endsection
