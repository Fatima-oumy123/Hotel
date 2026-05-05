@extends('layouts.guest')
@section('title', 'Reservation')
@section('hero_eyebrow', 'Reservation en 1 etape')
@section('hero_title', 'Reservez votre sejour a Rufisque.')
@section('hero_copy', 'Choisissez vos dates, le type de chambre, puis laissez-nous vos informations. Nous vous proposerons automatiquement une chambre disponible.')
@section('card_title', 'Simple et rapide')
@section('card_copy', 'Une seule page pour reserver. Total clair avant paiement.')
@section('hero_stats')
    <div class="box"><strong>1</strong><span>etape</span></div>
    <div class="box"><strong>Rufisque</strong><span>zone calme</span></div>
    <div class="box"><strong>{{ config('hotel.currency') }}</strong><span>prix abordables</span></div>
@endsection

@section('content')
@php
    $defaults = $defaults ?? [];
@endphp

<div class="panel">
    <div class="panel-head"><h2>Reservation en 1 etape</h2></div>
    <div class="panel-body">
        @if($errors->any())
            <div class="alert error" style="margin-bottom:16px">
                @foreach($errors->all() as $error)
                    <div>{{ $error }}</div>
                @endforeach
            </div>
        @endif

        <form action="{{ route('guest.book') }}" method="POST" style="display:grid;gap:16px">
            @csrf

            <div class="panel" style="border-radius:18px">
                <div class="panel-head"><h3>Votre sejour</h3></div>
                <div class="panel-body">
                    <div class="form-grid">
                        <div class="field">
                            <label>Arrivee</label>
                            <input type="date" name="check_in" required min="{{ date('Y-m-d') }}" value="{{ old('check_in', $defaults['check_in'] ?? '') }}">
                        </div>
                        <div class="field">
                            <label>Depart</label>
                            <input type="date" name="check_out" required value="{{ old('check_out', $defaults['check_out'] ?? '') }}">
                        </div>
                        <div class="field">
                            <label>Adultes</label>
                            <select name="adults" required>
                                @for($i=1; $i<=10; $i++)
                                    <option value="{{ $i }}" @selected((int) old('adults', $defaults['adults'] ?? 2) === $i)>{{ $i }}</option>
                                @endfor
                            </select>
                        </div>
                        <div class="field">
                            <label>Enfants</label>
                            <select name="children">
                                @for($i=0; $i<=10; $i++)
                                    <option value="{{ $i }}" @selected((int) old('children', $defaults['children'] ?? 0) === $i)>{{ $i }}</option>
                                @endfor
                            </select>
                        </div>
                    </div>

                    <div class="field" style="margin-top:12px">
                        <label>Type de chambre</label>
                        <select name="room_type_id">
                            <option value="">Le meilleur choix pour vous (recommande)</option>
                            @foreach($roomTypes as $type)
                                <option value="{{ $type->id }}" @selected((string) old('room_type_id', $defaults['room_type_id'] ?? '') === (string) $type->id)>{{ $type->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="alert info" style="margin-top:12px">
                        Nous selectionnons automatiquement une chambre disponible qui correspond a votre demande, pour vous faire gagner du temps.
                    </div>
                </div>
            </div>

            <div class="panel" style="border-radius:18px">
                <div class="panel-head"><h3>Vos informations</h3></div>
                <div class="panel-body">
                    <div class="form-grid">
                        <div class="field">
                            <label>Prenom</label>
                            <input type="text" name="guest_first_name" value="{{ old('guest_first_name') }}" required placeholder="Votre prenom">
                        </div>
                        <div class="field">
                            <label>Nom</label>
                            <input type="text" name="guest_last_name" value="{{ old('guest_last_name') }}" required placeholder="Votre nom">
                        </div>
                        <div class="field">
                            <label>Telephone</label>
                            <input type="text" name="guest_phone" value="{{ old('guest_phone') }}" required placeholder="+221 77 000 00 00">
                        </div>
                        <div class="field">
                            <label>Email (optionnel)</label>
                            <input type="email" name="guest_email" value="{{ old('guest_email') }}" placeholder="votre@email.com">
                        </div>
                        <div class="field">
                            <label>Date de naissance (optionnel)</label>
                            <input type="date" name="guest_dob" value="{{ old('guest_dob') }}">
                        </div>
                        <div class="field">
                            <label>Numero de piece (optionnel)</label>
                            <input type="text" name="guest_id_number" value="{{ old('guest_id_number') }}" placeholder="CNI, passeport...">
                        </div>
                    </div>

                    <div class="field" style="margin-top:12px">
                        <label>Demandes speciales (optionnel)</label>
                        <textarea name="special_requests" rows="4" placeholder="Lit bebe, chambre calme, preference alimentaire...">{{ old('special_requests') }}</textarea>
                    </div>

                    <div class="alert info" style="margin-top:12px">
                        Vos donnees sont utilisees uniquement pour votre reservation. L annulation gratuite reste possible jusqu a {{ config('hotel.cancellation_hours', 48) }}h avant l arrivee.
                    </div>
                </div>
            </div>

            <button type="submit" class="btn-primary" style="width:100%;padding:16px 18px;font-size:15px">
                Confirmer et passer au paiement
            </button>
        </form>
    </div>
</div>
@endsection
