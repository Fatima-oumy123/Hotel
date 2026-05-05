@extends('layouts.app')
@section('title','Nouveau type de chambre')

@section('content')
<div class="max-w-2xl mx-auto space-y-6">

    <div class="flex items-center gap-3">
        <a href="{{ route('room-types.index') }}" class="text-gray-400 hover:text-gray-600">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
        </a>
        <h1 class="text-2xl font-bold text-gray-900">Nouveau type de chambre</h1>
    </div>

    <div class="card p-6">
        <form action="{{ route('room-types.store') }}" method="POST" class="space-y-5"
              x-data="{ amenities: [], newAmenity: '' }">
            @csrf

            <div class="grid md:grid-cols-2 gap-4">
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Nom du type *</label>
                    <input type="text" name="name" value="{{ old('name') }}" required
                           class="form-input" placeholder="Ex: Chambre Simple, Suite Junior...">
                    @error('name')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Capacité (personnes) *</label>
                    <input type="number" name="capacity" value="{{ old('capacity', 1) }}"
                           min="1" max="20" required class="form-input">
                    @error('capacity')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Prix de base (FCFA/nuit) *</label>
                    <input type="number" name="base_price" value="{{ old('base_price') }}"
                           min="0" step="500" required class="form-input" placeholder="25000">
                    @error('base_price')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>

                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Description</label>
                    <textarea name="description" rows="3" class="form-input"
                              placeholder="Décrivez ce type de chambre...">{{ old('description') }}</textarea>
                </div>
            </div>

            {{-- Équipements dynamiques --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Équipements</label>

                {{-- Équipements pré-définis --}}
                <div class="grid grid-cols-2 md:grid-cols-3 gap-2 mb-3">
                    @foreach([
                        'WiFi gratuit', 'TV écran plat', 'Climatisation',
                        'Salle de bain privée', 'Mini-bar', 'Coffre-fort',
                        'Baignoire', 'Douche', 'Jacuzzi',
                        'Peignoir', 'Service butler', 'Terrasse privée',
                        'Vue mer', 'Vue jardin', 'Kitchenette',
                    ] as $preset)
                    <label class="flex items-center gap-2 cursor-pointer group">
                        <input type="checkbox" name="amenities[]" value="{{ $preset }}"
                               class="w-4 h-4 rounded border-gray-300 text-amber-500 focus:ring-amber-500"
                               @checked(in_array($preset, old('amenities', [])))>
                        <span class="text-sm text-gray-700 group-hover:text-amber-600 transition-colors">
                            {{ $preset }}
                        </span>
                    </label>
                    @endforeach
                </div>

                {{-- Équipement personnalisé --}}
                <div class="flex gap-2 mt-2" x-data="{ custom: '' }">
                    <input type="text" x-model="custom" placeholder="Ajouter un équipement personnalisé..."
                           class="form-input flex-1 text-sm"
                           @keydown.enter.prevent="
                               if(custom.trim()) {
                                   const cb = document.createElement('input');
                                   cb.type = 'checkbox';
                                   cb.name = 'amenities[]';
                                   cb.value = custom.trim();
                                   cb.checked = true;
                                   cb.classList.add('hidden');
                                   document.getElementById('custom-amenities').appendChild(cb);
                                   const tag = document.createElement('span');
                                   tag.className = 'inline-flex items-center gap-1 text-xs bg-amber-100 text-amber-700 px-2 py-1 rounded-full';
                                   tag.textContent = custom.trim();
                                   document.getElementById('custom-tags').appendChild(tag);
                                   custom = '';
                               }
                           ">
                    <button type="button"
                            class="btn-secondary text-sm px-4"
                            @click="
                                if(custom.trim()) {
                                    const cb = document.createElement('input');
                                    cb.type = 'checkbox';
                                    cb.name = 'amenities[]';
                                    cb.value = custom.trim();
                                    cb.checked = true;
                                    cb.classList.add('hidden');
                                    document.getElementById('custom-amenities').appendChild(cb);
                                    const tag = document.createElement('span');
                                    tag.className = 'inline-flex items-center gap-1 text-xs bg-amber-100 text-amber-700 px-2 py-1 rounded-full';
                                    tag.textContent = custom.trim();
                                    document.getElementById('custom-tags').appendChild(tag);
                                    custom = '';
                                }
                            ">
                        Ajouter
                    </button>
                </div>
                <div id="custom-amenities"></div>
                <div id="custom-tags" class="flex flex-wrap gap-1 mt-2"></div>
            </div>

            <div class="flex gap-3 pt-2">
                <button type="submit" class="btn-primary px-6">Créer le type</button>
                <a href="{{ route('room-types.index') }}" class="btn-secondary px-6">Annuler</a>
            </div>
        </form>
    </div>
</div>
@endsection
