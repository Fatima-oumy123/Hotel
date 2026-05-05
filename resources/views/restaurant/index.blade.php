@extends('layouts.app')
@section('title', 'Restaurant')
@section('page_title', 'Restaurant')
@section('page_subtitle', 'Commandes, salle et facturation chambre')

@section('content')
<style>
    .restaurant-page{display:grid;gap:18px}
    .restaurant-main{
        display:grid;
        grid-template-columns:minmax(0,2fr) minmax(300px,.95fr);
        gap:16px;
    }
    .rest-toolbar{
        display:grid;
        grid-template-columns:auto 1fr;
        gap:12px;
        align-items:end;
    }
    .rest-filter{
        display:flex;
        gap:10px;
        flex-wrap:wrap;
        justify-content:flex-end;
    }
    .rest-filter input,.rest-filter select{min-width:180px}
    .orders-grid{
        display:grid;
        grid-template-columns:repeat(2,minmax(0,1fr));
        gap:12px;
    }
    .order-card{
        border:1px solid #ead9cb;
        background:#fcfaf8;
        padding:16px;
    }
    .order-card h4{
        margin:0;
        font-family:'Outfit',sans-serif;
        font-size:28px;
    }
    .plan-grid{
        display:grid;
        grid-template-columns:repeat(4,minmax(0,1fr));
        gap:10px;
    }
    .plan-cell{
        min-height:72px;
        border:2px solid #d8b08d;
        background:#fff8ef;
        display:flex;
        align-items:center;
        justify-content:center;
        font-weight:800;
        color:#b05f09;
    }
    .plan-cell.off{
        border-color:#d8dde6;
        background:#f4f5f7;
        color:#94a3b8;
    }
    .top-items{
        display:grid;
        grid-template-columns:repeat(3,minmax(0,1fr));
        gap:12px;
    }
    .top-item{
        background:#182131;
        color:#fff;
        padding:16px;
    }
    @media (max-width:1180px){
        .restaurant-main,.orders-grid,.top-items{grid-template-columns:1fr}
    }
    @media (max-width:760px){
        .rest-toolbar{grid-template-columns:1fr}
        .rest-filter{justify-content:flex-start}
    }
</style>

<div class="restaurant-page">
    <div class="screen-header">
        <div class="screen-heading">
            <h2>Gestion de la Restauration</h2>
            <p>Prise de commandes, occupation de salle et liaison des ventes avec les chambres.</p>
        </div>
        <div class="screen-actions">
            <a href="{{ route('restaurant.monthly') }}" class="btn-secondary">Rapport mensuel</a>
            <a href="{{ route('restaurant.create') }}" class="btn-primary">Nouvelle vente</a>
        </div>
    </div>

    <div class="metric-grid">
        <article class="metric-tile">
            <div class="metric-title">Commandes du jour</div>
            <div class="metric-figure">{{ $dailyStats['count'] }}</div>
            <div class="metric-caption">Salle et cuisine</div>
        </article>
        <article class="metric-tile">
            <div class="metric-title">Occupation salle</div>
            <div class="metric-figure">82%</div>
            <div class="metric-caption">Tables actives</div>
        </article>
        <article class="metric-tile">
            <div class="metric-title">Revenus du jour</div>
            <div class="metric-figure">{{ number_format($dailyStats['total'], 0, ',', ' ') }}</div>
            <div class="metric-caption">{{ config('hotel.currency') }} encaisses</div>
        </article>
        <article class="metric-tile">
            <div class="metric-title">Charge cuisine</div>
            <div class="metric-figure" style="color:#a55a00">Priorite</div>
            <div class="metric-caption danger">Surveiller les urgences</div>
        </article>
    </div>

    <section class="lx-panel">
        <div class="lx-panel-body">
            <div class="rest-toolbar">
                <div></div>
                <form method="GET" class="rest-filter">
                    <input class="form-input" type="date" name="date" value="{{ request('date', $date->format('Y-m-d')) }}">
                    <select name="category" class="form-input">
                        <option value="">Toutes les categories</option>
                        <option value="food" @selected(request('category') === 'food')>Cuisine</option>
                        <option value="drinks" @selected(request('category') === 'drinks')>Boissons</option>
                        <option value="bar" @selected(request('category') === 'bar')>Bar</option>
                        <option value="room_service" @selected(request('category') === 'room_service')>Room service</option>
                    </select>
                    <button class="btn-secondary" type="submit">Filtrer</button>
                </form>
            </div>
        </div>
    </section>

    <div class="restaurant-main">
        <section class="lx-panel">
            <div class="lx-panel-head">
                <h3>Workflow des Commandes</h3>
                <span class="link-accent">{{ $date->isoFormat('D MMM YYYY') }}</span>
            </div>
            <div class="lx-panel-body">
                <div class="orders-grid">
                    @forelse($sales->take(4) as $sale)
                        <article class="order-card">
                            <h4>Table {{ $sale->table_number ?: '00' }}</h4>
                            <div style="margin-top:8px">{{ $sale->quantity }} x {{ $sale->item_name }}</div>
                            <div style="margin-top:6px;color:#64748b;font-size:13px">
                                {{ ucfirst($sale->category) }} · {{ number_format($sale->total, 0, ',', ' ') }} {{ config('hotel.currency') }}
                            </div>
                            <div style="margin-top:12px;display:flex;justify-content:space-between;gap:10px;align-items:center;flex-wrap:wrap">
                                <span class="badge {{ $sale->status === 'completed' ? 'badge-success' : 'badge-warning' }}">{{ strtoupper($sale->status) }}</span>
                                @if($sale->status === 'completed')
                                    <form action="{{ route('restaurant.cancel', $sale) }}" method="POST">
                                        @csrf
                                        @method('PATCH')
                                        <button class="btn-secondary" type="submit">Annuler</button>
                                    </form>
                                @endif
                            </div>
                        </article>
                    @empty
                        <div style="grid-column:1/-1;text-align:center;color:#64748b">Aucune commande pour cette journee.</div>
                    @endforelse
                </div>
            </div>

            <div class="lx-panel-head">
                <h3>Articles les plus vendus</h3>
                <a href="{{ route('restaurant-menu.index') }}" class="link-accent">Modifier le menu</a>
            </div>
            <div class="lx-panel-body">
                <div class="top-items">
                    @foreach($monthlyStats['top_items']->take(3) as $item)
                        <div class="top-item">
                            <strong>{{ strtoupper($item->item_name) }}</strong>
                            <div style="margin-top:8px;color:#cbd5e1">{{ $item->qty }} vente(s)</div>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>

        <div class="section-stack">
            <section class="lx-panel">
                <div class="lx-panel-head">
                    <h3>Plan de salle</h3>
                </div>
                <div class="lx-panel-body">
                    <div class="plan-grid">
                        @for($i = 1; $i <= 8; $i++)
                            <div class="plan-cell {{ in_array($i, [3, 5, 6]) ? 'off' : '' }}">{{ str_pad($i, 2, '0', STR_PAD_LEFT) }}</div>
                        @endfor
                    </div>
                </div>
            </section>

            <section class="lx-panel">
                <div class="lx-panel-head">
                    <h3>Facturation chambre</h3>
                </div>
                <div class="lx-panel-body" style="display:grid;gap:10px">
                    <div style="color:#64748b;font-size:13px">Associer une commande restaurant a la facture d une chambre ou d un client loge.</div>
                    <input class="form-input" placeholder="Numero de chambre">
                    <input class="form-input" placeholder="Nom du client">
                    <button class="btn-primary" type="button">Confirmer le transfert</button>
                </div>
            </section>
        </div>
    </div>

    <section class="lx-panel">
        <div class="lx-panel-head">
            <h3>Journal des ventes</h3>
            <a href="{{ route('restaurant-orders.index') }}" class="link-accent">Ecran commandes</a>
        </div>
        <div style="overflow:auto">
            <table class="table">
                <thead>
                    <tr>
                        <th>Article</th>
                        <th>Categorie</th>
                        <th>Quantite</th>
                        <th>Total</th>
                        <th>Paiement</th>
                        <th>Statut</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($sales as $sale)
                        <tr>
                            <td>{{ $sale->item_name }}</td>
                            <td>{{ ucfirst($sale->category) }}</td>
                            <td>{{ $sale->quantity }}</td>
                            <td>{{ number_format($sale->total, 0, ',', ' ') }} {{ config('hotel.currency') }}</td>
                            <td>{{ ucfirst($sale->payment_method) }}</td>
                            <td><span class="badge {{ $sale->status === 'completed' ? 'badge-success' : 'badge-warning' }}">{{ strtoupper($sale->status) }}</span></td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" style="text-align:center">Aucune vente disponible.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div style="padding:16px 18px">{{ $sales->withQueryString()->links() }}</div>
    </section>
</div>
@endsection
