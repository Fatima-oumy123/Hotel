@extends('layouts.app')
@section('title','Menu Restaurant')
@section('page_title','Menu <span>restaurant</span>')
@section('page_subtitle','Mise a jour rapide des plats et boissons')

@section('content')
<div style="display:grid;gap:20px">
    <section class="panel">
        <div class="panel-head"><h3>Nouvel article menu</h3></div>
        <div class="panel-body">
            <form method="POST" action="{{ route('restaurant-menu.store') }}" style="display:grid;grid-template-columns:repeat(5,minmax(0,1fr));gap:10px">
                @csrf
                <input class="top-search" style="max-width:none;width:100%" name="name" placeholder="Nom" required>
                <input class="top-search" style="max-width:none;width:100%" name="category" placeholder="Categorie" required>
                <input class="top-search" style="max-width:none;width:100%" type="number" step="0.01" min="0" name="price" placeholder="Prix" required>
                <input class="top-search" style="max-width:none;width:100%" name="description" placeholder="Description">
                <button class="btn-gold" type="submit">Ajouter</button>
            </form>
        </div>
    </section>

    <section class="panel">
        <div class="panel-head"><h3>Articles du menu</h3></div>
        <div class="panel-body" style="padding:0">
            <table class="table">
                <thead><tr><th>Nom</th><th>Categorie</th><th>Prix</th><th>Disponibilite</th><th>Actions</th></tr></thead>
                <tbody>
                    @forelse($menuItems as $item)
                    <tr>
                        <td>{{ $item->name }}</td>
                        <td>{{ $item->category }}</td>
                        <td>{{ number_format($item->price, 0, ',', ' ') }} {{ config('hotel.currency') }}</td>
                        <td>{!! $item->is_available ? '<span class="badge badge-success">Disponible</span>' : '<span class="badge badge-danger">Indisponible</span>' !!}</td>
                        <td style="display:flex;gap:6px">
                            <form method="POST" action="{{ route('restaurant-menu.update', $item) }}">
                                @csrf @method('PUT')
                                <input type="hidden" name="name" value="{{ $item->name }}">
                                <input type="hidden" name="category" value="{{ $item->category }}">
                                <input type="hidden" name="price" value="{{ $item->price }}">
                                <input type="hidden" name="description" value="{{ $item->description }}">
                                <input type="hidden" name="is_available" value="{{ $item->is_available ? 0 : 1 }}">
                                <button class="btn-outline" type="submit">{{ $item->is_available ? 'Desactiver' : 'Activer' }}</button>
                            </form>
                            <form method="POST" action="{{ route('restaurant-menu.destroy', $item) }}" onsubmit="return confirm('Supprimer cet article ?')">
                                @csrf @method('DELETE')
                                <button class="btn-outline" type="submit">Supprimer</button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="5" style="text-align:center">Aucun article menu</td></tr>
                    @endforelse
                </tbody>
            </table>
            <div style="padding:16px">{{ $menuItems->links() }}</div>
        </div>
    </section>
</div>
@endsection
