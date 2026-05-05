@extends('layouts.app')
@section('title', 'Modifier réservation — ' . $reservation->booking_number)

@section('content')
<div class="max-w-4xl mx-auto space-y-6" x-data="reservationEditForm()">

    {{-- En-tête --}}
    <div class="flex items-center gap-3">
        <a href="{{ route('reservations.show', $reservation) }}" class="text-gray-400 hover:text-gray-600 transition-colors">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
        </a>
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Modifier la réservation</h1>
            <p class="text-sm text-gray-500 font-mono">{{ $reservation->booking_number }}</p>
        </div>
        <span class="badge {{ $reservation->status_badge }} ml-2">
            {{ match($reservation->status) {
                'pending'     => 'En attente',
                'confirmed'   => 'Confirmée',
                'checked_in'  => 'En cours',
                'checked_out' => 'Terminée',
                'cancelled'   => 'Annulée',
                default       => $reservation->status,
            } }}
        </span>
    </div>

    <form action="{{ route('reservations.update', $reservation) }}" method="POST" class="space-y-6">
        @csrf
        @method('PUT')

        {{-- Informations client --}}
        <div class="card p-6">
            <h2 class="font-semibold text-gray-900 mb-4 flex items-center gap-2">
                <span class="w-6 h-6 bg-amber-500 text-white rounded-full text-xs flex items-center justify-center font-bold">1</span>
                Informations du client
            </h2>
            <div class="grid md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Prénom *</label>
                    <input type="text" name="guest_first_name"
                           value="{{ old('guest_first_name', $reservation->guest_first_name) }}"
                           required class="form-input" placeholder="Prénom">
                    @error('guest_first_name')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Nom *</label>
                    <input type="text" name="guest_last_name"
                           value="{{ old('guest_last_name', $reservation->guest_last_name) }}"
                           required class="form-input" placeholder="Nom de famille">
                    @error('guest_last_name')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Téléphone *</label>
                    <input type="text" name="guest_phone"
                           value="{{ old('guest_phone', $reservation->guest_phone) }}"
                           required class="form-input" placeholder="+221 77 000 00 00">
                    @error('guest_phone')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Email</label>
                    <input type="email" name="guest_email"
                           value="{{ old('guest_email', $reservation->guest_email) }}"
                           class="form-input" placeholder="email@exemple.com">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Date de naissance</label>
                    <input type="date" name="guest_dob"
                           value="{{ old('guest_dob', $reservation->guest_dob?->format('Y-m-d')) }}"
                           class="form-input">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">N° pièce d'identité</label>
                    <input type="text" name="guest_id_number"
                           value="{{ old('guest_id_number', $reservation->guest_id_number) }}"
                           class="form-input" placeholder="CNI, Passeport...">
                </div>
            </div>
        </div>

        {{-- Chambre & Dates (lecture seule si checked_in) --}}
        <div class="card p-6">
            <h2 class="font-semibold text-gray-900 mb-4 flex items-center gap-2">
                <span class="w-6 h-6 bg-amber-500 text-white rounded-full text-xs flex items-center justify-center font-bold">2</span>
                Chambre & Séjour
            </h2>

            @if(in_array($reservation->status, ['checked_in', 'checked_out']))
            <div class="mb-4 p-3 bg-amber-50 border border-amber-200 rounded-lg">
                <p class="text-xs text-amber-700 flex items-center gap-1.5">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.07 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                    </svg>
                    La chambre et les dates ne peuvent pas être modifiées pour une réservation en cours ou terminée.
                </p>
            </div>
            @endif

            <div class="grid md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Chambre</label>
                    @if(!in_array($reservation->status, ['checked_in', 'checked_out']))
                    <select name="room_id" class="form-input">
                        @foreach($rooms as $room)
                        <option value="{{ $room->id }}"
                                @selected(old('room_id', $reservation->room_id) == $room->id)
                                {{ ($room->status !== 'available' && $room->id !== $reservation->room_id) ? 'disabled' : '' }}>
                            Ch.{{ $room->number }} — {{ $room->roomType->name }}
                            ({{ number_format($room->roomType->base_price,0,',',' ') }} FCFA/nuit)
                            {{ $room->status !== 'available' && $room->id !== $reservation->room_id ? '[Indisponible]' : '' }}
                        </option>
                        @endforeach
                    </select>
                    @else
                    <div class="form-input bg-gray-50 text-gray-600 cursor-not-allowed">
                        Ch.{{ $reservation->room->number }} — {{ $reservation->room->roomType->name }}
                    </div>
                    <input type="hidden" name="room_id" value="{{ $reservation->room_id }}">
                    @endif
                </div>
                <div></div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Date d'arrivée</label>
                    @if(!in_array($reservation->status, ['checked_in', 'checked_out']))
                    <input type="date" name="check_in"
                           value="{{ old('check_in', $reservation->check_in->format('Y-m-d')) }}"
                           class="form-input">
                    @else
                    <div class="form-input bg-gray-50 text-gray-600 cursor-not-allowed">
                        {{ $reservation->check_in->format('d/m/Y') }}
                    </div>
                    @endif
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Date de départ</label>
                    @if(!in_array($reservation->status, ['checked_in', 'checked_out']))
                    <input type="date" name="check_out"
                           value="{{ old('check_out', $reservation->check_out->format('Y-m-d')) }}"
                           class="form-input">
                    @else
                    <div class="form-input bg-gray-50 text-gray-600 cursor-not-allowed">
                        {{ $reservation->check_out->format('d/m/Y') }}
                    </div>
                    @endif
                </div>
            </div>

            {{-- Récap tarifaire --}}
            <div class="mt-4 p-4 bg-gray-50 border border-gray-200 rounded-xl">
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4 text-center">
                    <div>
                        <p class="text-xs text-gray-500 font-medium">Nuits</p>
                        <p class="text-xl font-bold text-gray-900">{{ $reservation->nights }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 font-medium">Prix/nuit</p>
                        <p class="text-xl font-bold text-gray-900">{{ number_format($reservation->price_per_night,0,',',' ') }} F</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 font-medium">Sous-total</p>
                        <p class="text-xl font-bold text-gray-900">{{ number_format($reservation->total_amount,0,',',' ') }} F</p>
                    </div>
                    <div>
                        <p class="text-xs text-amber-600 font-medium">Total TTC</p>
                        <p class="text-xl font-bold text-amber-600">{{ number_format($reservation->final_amount,0,',',' ') }} F</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Occupation & Demandes --}}
        <div class="card p-6">
            <h2 class="font-semibold text-gray-900 mb-4 flex items-center gap-2">
                <span class="w-6 h-6 bg-amber-500 text-white rounded-full text-xs flex items-center justify-center font-bold">3</span>
                Occupation & Demandes spéciales
            </h2>
            <div class="grid md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Adultes *</label>
                    <select name="adults" class="form-input">
                        @for($i = 1; $i <= 6; $i++)
                        <option value="{{ $i }}" @selected(old('adults', $reservation->adults) == $i)>
                            {{ $i }} adulte{{ $i > 1 ? 's' : '' }}
                        </option>
                        @endfor
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Enfants</label>
                    <select name="children" class="form-input">
                        @for($i = 0; $i <= 4; $i++)
                        <option value="{{ $i }}" @selected(old('children', $reservation->children) == $i)>
                            {{ $i }} enfant{{ $i > 1 ? 's' : '' }}
                        </option>
                        @endfor
                    </select>
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Demandes spéciales</label>
                    <textarea name="special_requests" rows="3" class="form-input"
                              placeholder="Chambre haute, lit bébé, régime alimentaire...">{{ old('special_requests', $reservation->special_requests) }}</textarea>
                </div>
            </div>
        </div>

        {{-- Statut (Manager seulement) --}}
        @can('reservations.delete')
        <div class="card p-6">
            <h2 class="font-semibold text-gray-900 mb-4 flex items-center gap-2">
                <span class="w-6 h-6 bg-red-500 text-white rounded-full text-xs flex items-center justify-center font-bold">4</span>
                Statut de la réservation
                <span class="text-xs text-gray-400 font-normal">(Manager uniquement)</span>
            </h2>
            <div class="grid md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Statut</label>
                    <select name="status" class="form-input">
                        <option value="pending"     @selected(old('status', $reservation->status) === 'pending')>En attente</option>
                        <option value="confirmed"   @selected(old('status', $reservation->status) === 'confirmed')>Confirmée</option>
                        <option value="checked_in"  @selected(old('status', $reservation->status) === 'checked_in')>En cours (check-in)</option>
                        <option value="checked_out" @selected(old('status', $reservation->status) === 'checked_out')>Terminée (check-out)</option>
                        <option value="cancelled"   @selected(old('status', $reservation->status) === 'cancelled')>Annulée</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Raison d'annulation</label>
                    <input type="text" name="cancellation_reason"
                           value="{{ old('cancellation_reason', $reservation->cancellation_reason) }}"
                           class="form-input" placeholder="Si annulée, précisez la raison...">
                </div>
            </div>
        </div>
        @endcan

        {{-- Actions --}}
        <div class="flex items-center gap-3">
            <button type="submit" class="btn-primary px-8 py-3 text-base">
                Sauvegarder les modifications
            </button>
            <a href="{{ route('reservations.show', $reservation) }}" class="btn-secondary px-8 py-3 text-base">
                Annuler
            </a>
            @can('reservations.delete')
            @if(!in_array($reservation->status, ['checked_in', 'checked_out']))
            <form action="{{ route('reservations.destroy', $reservation) }}" method="POST"
                  class="ml-auto" onsubmit="return confirm('Supprimer définitivement cette réservation ?')">
                @csrf @method('DELETE')
                <button type="submit" class="btn-danger px-6 py-3">
                    Supprimer
                </button>
            </form>
            @endif
            @endcan
        </div>
    </form>
</div>

@push('scripts')
<script>
function reservationEditForm() {
    return {
        init() {}
    }
}
</script>
@endpush
@endsection
