@extends('layouts.app')
@section('title','Dépenses')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Dépenses</h1>
            <p class="text-sm text-gray-500">Ce mois : <span class="font-semibold text-red-600">{{ number_format($stats['month_total'],0,',',' ') }} FCFA</span></p>
        </div>
        <a href="{{ route('expenses.create') }}" class="btn-primary flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Nouvelle dépense
        </a>
    </div>

    {{-- Stats par catégorie --}}
    @if($stats['by_category']->count() > 0)
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-3">
        @foreach($stats['by_category'] as $cat)
        <div class="card p-4">
            <p class="text-xs text-gray-500 font-medium truncate">{{ ucfirst($cat->category) }}</p>
            <p class="text-xl font-bold text-red-600 mt-1">{{ number_format($cat->total,0,',',' ') }}</p>
            <p class="text-xs text-gray-400">FCFA ce mois</p>
        </div>
        @endforeach
    </div>
    @endif

    {{-- Filtres --}}
    <div class="card p-4">
        <form method="GET" class="flex flex-wrap gap-3 items-end">
            <div class="flex-1 min-w-40">
                <label class="block text-xs font-medium text-gray-600 mb-1">Recherche</label>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Titre..." class="form-input text-sm">
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-600 mb-1">Catégorie</label>
                <select name="category" class="form-input text-sm">
                    <option value="">Toutes</option>
                    @foreach($categories as $cat)
                    <option value="{{ $cat }}" @selected(request('category')===$cat)>{{ ucfirst($cat) }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-600 mb-1">Mois</label>
                <input type="month" name="month" value="{{ request('month') }}" class="form-input text-sm">
            </div>
            <button type="submit" class="btn-primary">Filtrer</button>
            <a href="{{ route('expenses.index') }}" class="btn-secondary">Réinitialiser</a>
        </form>
    </div>

    <div class="card overflow-hidden">
        <table class="w-full">
            <thead>
                <tr>
                    <th class="table-th">Titre</th>
                    <th class="table-th">Catégorie</th>
                    <th class="table-th">Fournisseur</th>
                    <th class="table-th text-right">Montant</th>
                    <th class="table-th">Date</th>
                    <th class="table-th">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($expenses as $expense)
                <tr class="hover:bg-gray-50">
                    <td class="table-td">
                        <p class="font-medium text-sm text-gray-900">{{ $expense->title }}</p>
                        @if($expense->description)
                        <p class="text-xs text-gray-400 truncate max-w-xs">{{ $expense->description }}</p>
                        @endif
                    </td>
                    <td class="table-td">
                        <span class="text-xs bg-gray-100 text-gray-600 px-2 py-1 rounded-full font-medium">{{ ucfirst($expense->category) }}</span>
                    </td>
                    <td class="table-td text-sm text-gray-500">{{ $expense->supplier ?? '—' }}</td>
                    <td class="table-td text-right font-bold text-red-600">{{ $expense->formatted_amount }}</td>
                    <td class="table-td text-sm text-gray-500">{{ $expense->expense_date->format('d/m/Y') }}</td>
                    <td class="table-td">
                        <div class="flex gap-2">
                            <a href="{{ route('expenses.show', $expense) }}" class="text-xs text-gray-500 hover:text-gray-700 font-medium">Voir</a>
                            <span class="text-gray-300">|</span>
                            <a href="{{ route('expenses.edit', $expense) }}" class="text-xs text-amber-600 hover:text-amber-700 font-medium">Modifier</a>
                            <span class="text-gray-300">|</span>
                            <form action="{{ route('expenses.destroy', $expense) }}" method="POST" class="inline"
                                  onsubmit="return confirm('Supprimer cette dépense ?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="text-xs text-red-600 hover:text-red-700 font-medium">Supprimer</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" class="py-12 text-center text-gray-400 text-sm">Aucune dépense enregistrée</td></tr>
                @endforelse
            </tbody>
        </table>
        <div class="px-4 py-3 border-t border-gray-100">{{ $expenses->withQueryString()->links() }}</div>
    </div>
</div>
@endsection
