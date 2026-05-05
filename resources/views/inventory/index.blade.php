@extends('layouts.app')
@section('title', 'Stocks')
@section('page_title', 'Stocks')
@section('page_subtitle', 'Articles, mouvements et seuils critiques')

@section('content')
<style>
    .inventory-page{display:grid;gap:18px}
    .inventory-forms{
        display:grid;
        grid-template-columns:1fr 1fr;
        gap:16px;
    }
    .inventory-form-grid{
        display:grid;
        grid-template-columns:repeat(2,minmax(0,1fr));
        gap:12px;
    }
    .inventory-form-grid .full{grid-column:1/-1}
    .inventory-form-grid label{
        display:block;
        margin-bottom:8px;
        font-size:12px;
        font-weight:800;
        color:#5d544b;
        text-transform:uppercase;
        letter-spacing:.08em;
    }
    .inventory-table th{background:#faf1ea}
    .inventory-table td{font-size:15px}
    @media (max-width:1180px){
        .inventory-forms,.inventory-form-grid{grid-template-columns:1fr}
    }
</style>

<div class="inventory-page">
    <div class="screen-header">
        <div class="screen-heading">
            <h2>Gestion des Stocks</h2>
            <p>Suivi des produits cuisine et entretien, mouvements d entree et alertes de seuil.</p>
        </div>
    </div>

    <div class="metric-grid" style="grid-template-columns:repeat(3,minmax(0,1fr))">
        <article class="metric-tile">
            <div class="metric-title">Articles suivis</div>
            <div class="metric-figure">{{ $stats['items'] }}</div>
            <div class="metric-caption">Produits actifs</div>
        </article>
        <article class="metric-tile">
            <div class="metric-title">Alertes de seuil</div>
            <div class="metric-figure" style="color:#a55a00">{{ $stats['low_stock'] }}</div>
            <div class="metric-caption danger">A reapprovisionner</div>
        </article>
        <article class="metric-tile">
            <div class="metric-title">Valeur du stock</div>
            <div class="metric-figure">{{ number_format($stats['stock_value'], 0, ',', ' ') }}</div>
            <div class="metric-caption">{{ config('hotel.currency') }} immobilises</div>
        </article>
    </div>

    <div class="inventory-forms">
        <section class="lx-panel">
            <div class="lx-panel-head">
                <h3>Ajouter un Article</h3>
            </div>
            <div class="lx-panel-body">
                <form method="POST" action="{{ route('inventory.store') }}" class="inventory-form-grid">
                    @csrf
                    <div><label>Nom</label><input class="form-input" name="name" placeholder="Nom de l article" required></div>
                    <div><label>Categorie</label><input class="form-input" name="category" placeholder="Categorie" required></div>
                    <div><label>Unite</label><input class="form-input" name="unit" placeholder="Unite" value="unite" required></div>
                    <div><label>Stock initial</label><input class="form-input" type="number" step="0.01" min="0" name="current_stock" required></div>
                    <div><label>Seuil minimum</label><input class="form-input" type="number" step="0.01" min="0" name="min_stock" required></div>
                    <div><label>Cout unitaire</label><input class="form-input" type="number" step="0.01" min="0" name="unit_cost" required></div>
                    <div class="full">
                        <label>Statut</label>
                        <select name="status" class="form-input">
                            <option value="active">Actif</option>
                            <option value="inactive">Inactif</option>
                        </select>
                    </div>
                    <div class="full"><button class="btn-primary" type="submit">Ajouter</button></div>
                </form>
            </div>
        </section>

        <section class="lx-panel">
            <div class="lx-panel-head">
                <h3>Mouvement de Stock</h3>
            </div>
            <div class="lx-panel-body">
                <form method="POST" action="{{ route('inventory.movement') }}" class="inventory-form-grid">
                    @csrf
                    <div class="full">
                        <label>Article</label>
                        <select name="inventory_item_id" class="form-input" required>
                            @foreach($items as $item)
                                <option value="{{ $item->id }}">{{ $item->name }} ({{ $item->current_stock }} {{ $item->unit }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label>Type</label>
                        <select name="type" class="form-input" required>
                            <option value="in">Entree</option>
                            <option value="out">Sortie</option>
                            <option value="adjustment">Ajustement</option>
                        </select>
                    </div>
                    <div>
                        <label>Quantite</label>
                        <input class="form-input" type="number" step="0.01" min="0.01" name="quantity" required>
                    </div>
                    <div class="full">
                        <label>Reference</label>
                        <input class="form-input" name="reference" placeholder="Reference">
                    </div>
                    <div class="full"><button class="btn-primary" type="submit">Valider</button></div>
                </form>
            </div>
        </section>
    </div>

    <section class="lx-panel">
        <div class="lx-panel-head">
            <h3>Articles en Stock</h3>
        </div>
        <div style="overflow:auto">
            <table class="table inventory-table">
                <thead>
                    <tr>
                        <th>Article</th>
                        <th>Categorie</th>
                        <th>Stock actuel</th>
                        <th>Seuil</th>
                        <th>Etat</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($items as $item)
                        <tr>
                            <td>{{ $item->name }}</td>
                            <td>{{ $item->category }}</td>
                            <td>{{ $item->current_stock }} {{ $item->unit }}</td>
                            <td>{{ $item->min_stock }} {{ $item->unit }}</td>
                            <td>{!! $item->is_low_stock ? '<span class="badge badge-danger">Alerte</span>' : '<span class="badge badge-success">OK</span>' !!}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" style="text-align:center">Aucun article disponible.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div style="padding:16px 18px">{{ $items->withQueryString()->links() }}</div>
    </section>

    <section class="lx-panel">
        <div class="lx-panel-head">
            <h3>Derniers Mouvements</h3>
        </div>
        <div style="overflow:auto">
            <table class="table inventory-table">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Article</th>
                        <th>Type</th>
                        <th>Quantite</th>
                        <th>Utilisateur</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($movements as $movement)
                        <tr>
                            <td>{{ $movement->moved_at?->format('d/m/Y H:i') }}</td>
                            <td>{{ $movement->item?->name }}</td>
                            <td><span class="badge {{ $movement->type === 'out' ? 'badge-danger' : 'badge-info' }}">{{ strtoupper($movement->type) }}</span></td>
                            <td>{{ $movement->quantity }}</td>
                            <td>{{ $movement->user?->name ?? 'Systeme' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" style="text-align:center">Aucun mouvement enregistre.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>
</div>
@endsection
