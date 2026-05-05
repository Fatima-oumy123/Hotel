@extends('layouts.guest')
@section('title', 'Reservation')
@section('hero_eyebrow', 'Reservation en ligne')
@section('hero_title', 'Trouvez la chambre ideale a Rufisque.')
@section('hero_copy', 'Confort familial, touche de luxe, tarifs abordables et environnement agreable. Choisissez vos dates et lancez la reservation en quelques minutes.')
@section('card_title', 'En quelques minutes')
@section('card_copy', 'Selectionnez la periode, voyez les disponibilites et finalisez votre reservation sans prise de tete.')
@section('hero_stats')
    <div class="box"><strong>3</strong><span>etapes simples</span></div>
    <div class="box"><strong>Mobile</strong><span>optimise</span></div>
    <div class="box"><strong>FR</strong><span>lecture claire</span></div>
@endsection

@section('content')
<style>
    .intro-grid{display:grid;grid-template-columns:1.1fr .9fr;gap:16px}
    .room-grid{display:grid;grid-template-columns:repeat(3,minmax(0,1fr));gap:14px}
    .room-card{overflow:hidden}
    .room-photo{height:220px;background-size:cover;background-position:center}
    .room-copy{padding:18px 20px}
    .room-copy h3{margin:0;font-family:'Outfit',sans-serif;font-size:28px}
    .room-copy p{margin:8px 0 0;color:#64748b;line-height:1.7;font-size:14px}
    @media (max-width:1000px){
        .intro-grid{grid-template-columns:1fr}
        .room-grid{grid-template-columns:1fr}
    }
</style>

<div class="intro-grid">
    <section class="panel">
        <div class="panel-head"><h2>Verifier les disponibilites</h2></div>
        <div class="panel-body">
            @if(session('error'))
                <div class="alert error" style="margin-bottom:16px">{{ session('error') }}</div>
            @endif
            @if($errors->any())
                <div class="alert error" style="margin-bottom:16px">{{ $errors->first() }}</div>
            @endif

            <form action="{{ route('guest.search') }}" method="POST" style="display:grid;gap:16px">
                @csrf
                <div class="form-grid">
                    <div class="field">
                        <label>Arrivee</label>
                        <input type="date" name="check_in" required min="{{ date('Y-m-d') }}" value="{{ old('check_in') }}">
                    </div>
                    <div class="field">
                        <label>Depart</label>
                        <input type="date" name="check_out" required value="{{ old('check_out') }}">
                    </div>
                    <div class="field">
                        <label>Adultes</label>
                        <select name="adults">
                            @for($i=1; $i<=6; $i++)
                                <option value="{{ $i }}" @selected(old('adults', 1) == $i)>{{ $i }} adulte{{ $i > 1 ? 's' : '' }}</option>
                            @endfor
                        </select>
                    </div>
                    <div class="field">
                        <label>Type de chambre</label>
                        <select name="room_type_id">
                            <option value="">Tous les types</option>
                            @foreach($roomTypes as $type)
                                <option value="{{ $type->id }}" @selected(old('room_type_id') == $type->id)>{{ $type->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <button type="submit" class="btn-primary" style="width:100%;padding:16px 18px;font-size:15px">Verifier les disponibilites</button>
            </form>
        </div>
    </section>

    <aside class="panel">
        <div class="panel-head"><h3>Pourquoi vous allez aimer</h3></div>
        <div class="panel-body" style="display:grid;gap:12px">
            <div style="padding:14px;border-radius:14px;background:#f8f3ed;border:1px solid #eddcc7">
                <strong>Reservation rapide</strong>
                <div style="margin-top:6px;color:#64748b;font-size:13px">Quelques etapes simples pour trouver une chambre et confirmer votre sejour.</div>
            </div>
            <div style="padding:14px;border-radius:14px;background:#fff;border:1px solid #eceef2">
                <strong>Infos claires</strong>
                <div style="margin-top:6px;color:#64748b;font-size:13px">Prix, nuits et details essentiels avant de confirmer, sans surprises.</div>
            </div>
            <div style="padding:14px;border-radius:14px;background:#fff;border:1px solid #eceef2">
                <strong>Paiement flexible</strong>
                <div style="margin-top:6px;color:#64748b;font-size:13px">Selon disponibilite, choisissez l option la plus simple pour vous.</div>
            </div>
        </div>
    </aside>
</div>

<section class="panel">
    <div class="panel-head"><h2>Types de chambres mis en avant</h2></div>
    <div class="panel-body">
        <div class="room-grid">
            @php
                $roomImages = [
                    'https://images.unsplash.com/photo-1590490360182-c33d57733427?auto=format&fit=crop&w=1200&q=80',
                    'https://images.unsplash.com/photo-1618773928121-c32242e63f39?auto=format&fit=crop&w=1200&q=80',
                    'https://images.unsplash.com/photo-1505693416388-ac5ce068fe85?auto=format&fit=crop&w=1200&q=80',
                    'https://images.unsplash.com/photo-1578683010236-d716f9a3f461?auto=format&fit=crop&w=1200&q=80',
                ];
            @endphp
            @forelse($roomTypes as $index => $type)
                <article class="panel room-card">
                    <div class="room-photo" style="background-image:url('{{ $roomImages[$index % count($roomImages)] }}')"></div>
                    <div class="room-copy">
                        <h3>{{ $type->name }}</h3>
                        <p>{{ $type->description ?: 'Un cocon familial et elegant, pense pour le repos, le confort et la tranquillite.' }}</p>
                        <div style="margin-top:12px;font-weight:800;color:#9a5210">{{ number_format($type->base_price, 0, ',', ' ') }} {{ config('hotel.currency') }} / nuit</div>
                    </div>
                </article>
            @empty
                <div style="color:#64748b">Aucun type de chambre n est encore configure.</div>
            @endforelse
        </div>
    </div>
</section>
@endsection
