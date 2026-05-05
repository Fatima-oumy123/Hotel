@extends('layouts.app')
@section('title','Nouvelle vente')

@section('content')
<div class="max-w-2xl mx-auto space-y-6">
    <div class="flex items-center gap-3">
        <a href="{{ route('restaurant.index') }}" class="text-gray-400 hover:text-gray-600">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
        </a>
        <h1 class="text-2xl font-bold text-gray-900">Nouvelle vente</h1>
    </div>

    <div class="card p-6" x-data="{ qty: 1, price: 0 }">
        <form action="{{ route('restaurant.store') }}" method="POST" class="space-y-5">
            @csrf
            <div class="grid grid-cols-2 gap-4">
                <div class="col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Article / Plat *</label>
                    <input type="text" name="item_name" value="{{ old('item_name') }}" required
                           class="form-input" placeholder="Ex: Thiéboudienne, Yassa, Coca-Cola...">
                    @error('item_name')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Catégorie *</label>
                    <select name="category" required class="form-input">
                        <option value="food"        @selected(old('category')==='food')>🍽️ Restauration</option>
                        <option value="drinks"      @selected(old('category')==='drinks')>🥤 Boissons</option>
                        <option value="bar"         @selected(old('category')==='bar')>🍺 Bar</option>
                        <option value="room_service" @selected(old('category')==='room_service')>🛎️ Room service</option>
                        <option value="other"       @selected(old('category')==='other')>Autre</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Méthode de paiement *</label>
                    <select name="payment_method" required class="form-input">
                        <option value="cash"        @selected(old('payment_method')==='cash')>💵 Espèces</option>
                        <option value="card"        @selected(old('payment_method')==='card')>💳 Carte</option>
                        <option value="room_charge" @selected(old('payment_method')==='room_charge')>🏨 Chambre</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Quantité *</label>
                    <input type="number" name="quantity" value="{{ old('quantity', 1) }}" min="1" required
                           class="form-input" x-model.number="qty">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Prix unitaire (FCFA) *</label>
                    <input type="number" name="unit_price" value="{{ old('unit_price') }}" min="0" step="50" required
                           class="form-input" x-model.number="price">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">N° Table</label>
                    <input type="text" name="table_number" value="{{ old('table_number') }}"
                           class="form-input" placeholder="T1, T2, Bar...">
                </div>
                <div class="flex items-end">
                    <div class="bg-amber-50 border border-amber-200 rounded-lg p-3 w-full text-center">
                        <p class="text-xs text-amber-600 font-medium">Total</p>
                        <p class="text-xl font-bold text-amber-800" x-text="(qty * price).toLocaleString('fr') + ' FCFA'"></p>
                    </div>
                </div>
                <div class="col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Notes</label>
                    <textarea name="notes" rows="2" class="form-input" placeholder="Instructions spéciales...">{{ old('notes') }}</textarea>
                </div>
            </div>

            <div class="flex gap-3">
                <button type="submit" class="btn-primary px-6">Enregistrer la vente</button>
                <a href="{{ route('restaurant.index') }}" class="btn-secondary px-6">Annuler</a>
            </div>
        </form>
    </div>
</div>
@endsection
