@extends('layouts.guest')
@section('title', 'Disponibilites')
@section('hero_eyebrow', 'Etape 2 · Selection de chambre')
@section('hero_title', 'Choisissez une chambre adaptee a votre sejour.')
@section('hero_copy', 'Comparez les options et choisissez votre confort a Rufisque : une ambiance familiale, des tarifs abordables, et un cadre agreable.')
@section('card_title', 'Recherche en cours')
@section('card_copy', 'Periode du ' . \Carbon\Carbon::parse($request->check_in)->format('d/m/Y') . ' au ' . \Carbon\Carbon::parse($request->check_out)->format('d/m/Y') . ' pour ' . $request->adults . ' adulte(s).')
@section('hero_stats')
    <div class="box"><strong>{{ $roomsWithPricing->count() }}</strong><span>offres trouvees</span></div>
    <div class="box"><strong>{{ \Carbon\Carbon::parse($request->check_in)->diffInDays(\Carbon\Carbon::parse($request->check_out)) }}</strong><span>nuit(s)</span></div>
    <div class="box"><strong>FR</strong><span>parcours simple</span></div>
@endsection

@section('content')
<style>
    .room-grid{display:grid;gap:14px}
    .room-card{display:grid;grid-template-columns:.8fr 1.2fr;gap:0}
    .room-visual{
        min-height:240px;
        background:linear-gradient(180deg,rgba(0,0,0,.12),rgba(0,0,0,.45)), url('https://images.unsplash.com/photo-1590490360182-c33d57733427?auto=format&fit=crop&w=1200&q=80') center/cover;
        color:#fff;padding:22px;display:flex;flex-direction:column;justify-content:flex-end;
    }
    .room-visual h3{margin:0;font-family:'Outfit',sans-serif;font-size:38px}
    .room-visual p{margin:6px 0 0;color:#ede4da}
    .room-body{padding:22px}
    .chips{display:flex;flex-wrap:wrap;gap:8px;margin-top:12px}
    .chip{padding:7px 10px;border-radius:999px;background:#f5f6f8;border:1px solid #e1e5ec;color:#475467;font-size:12px;font-weight:700}
    .price-box{
        margin-top:18px;padding:16px;border-radius:16px;background:#f8f3ed;border:1px solid #eddcc7;
        display:flex;justify-content:space-between;gap:12px;align-items:end;flex-wrap:wrap;
    }
    .price-box strong{display:block;font-family:'Outfit',sans-serif;font-size:34px;color:#9a5210;line-height:1}
    .helper{color:#64748b;font-size:13px}
    @media (max-width:900px){.room-card{grid-template-columns:1fr}}
</style>

<div class="panel">
    <div class="panel-body">
        <div class="stepper">
            <div class="step done"><div class="bubble">✓</div><span>Dates</span></div>
            <div class="step-line"></div>
            <div class="step active"><div class="bubble">2</div><span>Chambre</span></div>
            <div class="step-line"></div>
            <div class="step"><div class="bubble">3</div><span>Informations</span></div>
        </div>
    </div>
</div>

@if($roomsWithPricing->isEmpty())
    <div class="panel">
        <div class="panel-body" style="text-align:center;padding:40px 22px">
            <h2 style="margin:0;font-family:'Outfit',sans-serif;font-size:36px">Aucune chambre disponible</h2>
            <p style="margin:12px auto 0;max-width:560px;color:#64748b">Aucune chambre n est disponible pour les dates selectionnees. Vous pouvez ajuster la periode ou relancer une nouvelle recherche.</p>
            <div style="margin-top:20px">
                <a href="{{ route('guest.step1') }}" class="btn-primary">Modifier les dates</a>
            </div>
        </div>
    </div>
@else
    <div class="room-grid">
        @foreach($roomsWithPricing as $index => $item)
            @php
                $room = $item['room_model'];
                $pricing = $item['pricing'];
                $images = [
                    'https://images.unsplash.com/photo-1590490360182-c33d57733427?auto=format&fit=crop&w=1200&q=80',
                    'https://images.unsplash.com/photo-1618773928121-c32242e63f39?auto=format&fit=crop&w=1200&q=80',
                    'https://images.unsplash.com/photo-1505693416388-ac5ce068fe85?auto=format&fit=crop&w=1200&q=80',
                    'https://images.unsplash.com/photo-1578683010236-d716f9a3f461?auto=format&fit=crop&w=1200&q=80',
                ];
            @endphp
            <article class="panel room-card">
                <div class="room-visual" style="background-image:linear-gradient(180deg,rgba(0,0,0,.12),rgba(0,0,0,.45)), url('{{ $images[$index % count($images)] }}')">
                    <h3>Ch. {{ $room['number'] }}</h3>
                    <p>{{ $room['roomType']['name'] ?? $item['type'] }} · Etage {{ $room['floor'] }}</p>
                </div>
                <div class="room-body">
                    <div style="display:flex;justify-content:space-between;gap:12px;align-items:start;flex-wrap:wrap">
                        <div>
                            <h3 style="margin:0;font-family:'Outfit',sans-serif;font-size:30px">{{ $room['roomType']['name'] ?? $item['type'] }}</h3>
                            <div class="helper">Capacite : {{ $room['roomType']['capacity'] ?? 2 }} personne(s)</div>
                        </div>
                        @if($pricing['seasonal_rate'])
                            <span class="badge badge-success">{{ $pricing['seasonal_rate'] }}</span>
                        @endif
                    </div>

                    @if(!empty($room['roomType']['amenities']))
                        <div class="chips">
                            @foreach(($room['roomType']['amenities'] ?? []) as $amenity)
                                <span class="chip">{{ $amenity }}</span>
                            @endforeach
                        </div>
                    @endif

                    <div class="price-box">
                        <div>
                            <strong>{{ number_format($pricing['final_amount'], 0, ',', ' ') }} {{ config('hotel.currency') }}</strong>
                            <div class="helper">{{ number_format($pricing['price_per_night'], 0, ',', ' ') }} {{ config('hotel.currency') }} / nuit · {{ $pricing['nights'] }} nuit(s)</div>
                        </div>
                        <form action="{{ route('guest.step2') }}" method="GET">
                            <input type="hidden" name="room_id" value="{{ $room['id'] }}">
                            <button type="submit" class="btn-primary">Choisir cette chambre</button>
                        </form>
                    </div>
                </div>
            </article>
        @endforeach
    </div>
@endif

<div style="display:flex;justify-content:center">
    <a href="{{ route('guest.step1') }}" class="btn-secondary">Modifier ma recherche</a>
</div>
@endsection
