@extends('layouts.app')
@section('title','Modifier — '.$roomType->name)

@section('content')
<div class="max-w-2xl mx-auto space-y-6">

    <div class="flex items-center gap-3">
        <a href="{{ route('room-types.index') }}" class="text-gray-400 hover:text-gray-600">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
        </a>
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Modifier — {{ $roomType->name }}</h1>
            <p class="text-sm text-gray-500">
                {{ $roomType->rooms()->count() }} chambre(s) associée(s) à ce type
            </p>
        </div>
    </div>

    <div class="card p-6">
        <form action="{{ route('room-types.update', $roomType) }}" method="POST" class="space-y-5">
            @csrf @method('PUT')

            <div class="grid md:grid-cols-2 gap-4">
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Nom du type *</label>
                    <input type="text" name="name"
                           value="{{ old('name', $roomType->name) }}"
                           required class="form-input">
                    @error('name')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Capacité *</label>
                    <input type="number" name="capacity"
                           value="{{ old('capacity', $roomType->capacity) }}"
                           min="1" max="20" required class="form-input">
                    @error('capacity')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Prix de base (FCFA/nuit) *</label>
                    <input type="number" name="base_price"
                           value="{{ old('base_price', $roomType->base_price) }}"
                           min="0" step="500" required class="form-input">
                    @error('base_price')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>

                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Description</label>
                    <textarea name="description" rows="3" class="form-input">{{ old('description', $roomType->description) }}</textarea>
                </div>
            </div>

            {{-- Équipements --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Équipements</label>
                @php
                    $currentAmenities = old('amenities', $roomType->amenities ?? []);
                    $presets = [
                        'WiFi gratuit', 'TV écran plat', 'Climatisation',
                        'Salle de bain privée', 'Mini-bar', 'Coffre-fort',
                        'Baignoire', 'Douche', 'Jacuzzi',
                        'Peignoir', 'Service butler', 'Terrasse privée',
                        'Vue mer', 'Vue jardin', 'Kitchenette',
                    ];
                    $customAmenities = array_diff($currentAmenities, $presets);
                @endphp

                <div class="grid grid-cols-2 md:grid-cols-3 gap-2 mb-3">
                    @foreach($presets as $preset)
                    <label class="flex items-center gap-2 cursor-pointer group">
                        <input type="checkbox" name="amenities[]" value="{{ $preset }}"
                               class="w-4 h-4 rounded border-gray-300 text-amber-500 focus:ring-amber-500"
                               @checked(in_array($preset, $currentAmenities))>
                        <span class="text-sm text-gray-700 group-hover:text-amber-600 transition-colors">
                            {{ $preset }}
                        </span>
                    </label>
                    @endforeach
                </div>

                {{-- Équipements personnalisés existants --}}
                @if(count($customAmenities) > 0)
                <div class="mt-2 mb-2">
                    <p class="text-xs text-gray-500 mb-1">Équipements personnalisés :</p>
                    <div class="flex flex-wrap gap-1">
                        @foreach($customAmenities as $custom)
                        <label class="flex items-center gap-1 cursor-pointer">
                            <input type="checkbox" name="amenities[]" value="{{ $custom }}"
                                   class="w-3 h-3 rounded border-gray-300 text-amber-500"
                                   checked>
                            <span class="text-xs bg-amber-100 text-amber-700 px-2 py-0.5 rounded-full">
                                {{ $custom }}
                            </span>
                        </label>
                        @endforeach
                    </div>
                </div>
                @endif

                {{-- Ajouter équipement personnalisé --}}
                <div class="flex gap-2 mt-2" x-data="{ custom: '' }">
                    <input type="text" x-model="custom"
                           placeholder="Ajouter un équipement..."
                           class="form-input flex-1 text-sm"
                           @keydown.enter.prevent="
                               if(custom.trim()) {
                                   const cb = document.createElement('input');
                                   cb.type = 'checkbox';
                                   cb.name = 'amenities[]';
                                   cb.value = custom.trim();
                                   cb.checked = true;
                                   cb.classList.add('hidden');
                                   document.getElementById('extra-amenities').appendChild(cb);
                                   const tag = document.createElement('span');
                                   tag.className = 'inline-flex text-xs bg-amber-100 text-amber-700 px-2 py-0.5 rounded-full';
                                   tag.textContent = custom.trim();
                                   document.getElementById('extra-tags').appendChild(tag);
                                   custom = '';
                               }
                           ">
                    <button type="button" class="btn-secondary text-sm px-4"
                            @click="
                                if(custom.trim()) {
                                    const cb = document.createElement('input');
                                    cb.type = 'checkbox';
                                    cb.name = 'amenities[]';
                                    cb.value = custom.trim();
                                    cb.checked = true;
                                    cb.classList.add('hidden');
                                    document.getElementById('extra-amenities').appendChild(cb);
                                    const tag = document.createElement('span');
                                    tag.className = 'inline-flex text-xs bg-amber-100 text-amber-700 px-2 py-0.5 rounded-full';
                                    tag.textContent = custom.trim();
                                    document.getElementById('extra-tags').appendChild(tag);
                                    custom = '';
                                }
                            ">
                        Ajouter
                    </button>
                </div>
                <div id="extra-amenities"></div>
                <div id="extra-tags" class="flex flex-wrap gap-1 mt-2"></div>
            </div>

            {{-- Info tarification --}}
            <div class="p-4 bg-blue-50 border border-blue-200 rounded-xl">
                <p class="text-xs text-blue-700 flex items-start gap-1.5">
                    <svg class="w-4 h-4 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    La modification du prix de base s'appliquera aux nouvelles réservations uniquement.
                    Les réservations existantes conservent leur prix d'origine.
                    Pour des tarifs saisonniers, configurez les <strong>tarifs saisonniers</strong> séparément.
                </p>
            </div>

            <div class="flex gap-3 pt-2">
                <button type="submit" class="btn-primary px-6">Sauvegarder</button>
                <a href="{{ route('room-types.index') }}" class="btn-secondary px-6">Annuler</a>
            </div>
        </form>
    </div>
</div>
@endsection
