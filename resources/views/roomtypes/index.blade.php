@extends('layouts.app')
@section('title', 'Types de chambres')
@section('page_title', 'Types de chambres')
@section('page_subtitle', 'Categories, prix et equipements')

@section('content')
<style>
    .roomtypes-page{display:grid;gap:18px}
    .type-grid{
        display:grid;
        grid-template-columns:repeat(3,minmax(0,1fr));
        gap:16px;
    }
    .type-card{
        background:#fff;
        border:1px solid #ddbba0;
        overflow:hidden;
    }
    .type-head{
        padding:20px;
        background:linear-gradient(120deg,#b56518,#d88322);
        color:#fff;
    }
    .type-body{padding:20px}
    @media (max-width:1180px){
        .type-grid{grid-template-columns:1fr}
    }
</style>

<div class="roomtypes-page">
    <div class="screen-header">
        <div class="screen-heading">
            <h2>Structurer votre offre de chambres</h2>
            <p>Presentation des categories avec prix, capacites et equipements dans un format plus coherent avec le back-office.</p>
        </div>
        <div class="screen-actions">
            @can('rooms.create')
                <a href="{{ route('room-types.create') }}" class="btn-primary">Nouveau type</a>
            @endcan
        </div>
    </div>

    <div class="type-grid">
        @forelse($roomTypes as $type)
            <article class="type-card">
                <div class="type-head">
                    <div style="display:flex;justify-content:space-between;gap:12px;align-items:start">
                        <div>
                            <h3 style="margin:0;font-family:'Outfit',sans-serif;font-size:28px">{{ $type->name }}</h3>
                            <div style="margin-top:6px;color:#f9e4ce;font-size:13px">{{ $type->rooms_count }} chambre(s)</div>
                        </div>
                        <div style="text-align:right">
                            <div style="font-family:'Outfit',sans-serif;font-size:30px">{{ number_format($type->base_price,0,',',' ') }}</div>
                            <div style="font-size:12px;color:#f9e4ce">{{ config('hotel.currency') }} / nuit</div>
                        </div>
                    </div>
                </div>
                <div class="type-body">
                    <div style="color:#64748b;font-size:14px">{{ $type->capacity }} personne(s)</div>
                    @if($type->description)
                        <p style="margin:10px 0 0;color:#475569;line-height:1.7;font-size:14px">{{ $type->description }}</p>
                    @endif
                    @if($type->amenities && count($type->amenities) > 0)
                        <div style="display:flex;gap:8px;flex-wrap:wrap;margin-top:14px">
                            @foreach(array_slice($type->amenities, 0, 4) as $amenity)
                                <span class="soft-tag gray">{{ $amenity }}</span>
                            @endforeach
                        </div>
                    @endif
                    <div style="display:flex;gap:8px;flex-wrap:wrap;margin-top:18px">
                        @can('rooms.edit')
                            <a href="{{ route('room-types.edit', $type) }}" class="btn-secondary">Modifier</a>
                        @endcan
                        <a href="{{ route('rooms.index', ['room_type_id' => $type->id]) }}" class="btn-secondary">Voir chambres</a>
                        @can('rooms.delete')
                            @if($type->rooms_count === 0)
                                <form action="{{ route('room-types.destroy', $type) }}" method="POST" onsubmit="return confirm('Supprimer ce type ?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn-primary">Supprimer</button>
                                </form>
                            @endif
                        @endcan
                    </div>
                </div>
            </article>
        @empty
            <div class="lx-panel" style="grid-column:1/-1">
                <div class="lx-panel-body" style="text-align:center;color:#64748b">Aucun type de chambre n est encore configure.</div>
            </div>
        @endforelse
    </div>

    <div>{{ $roomTypes->links() }}</div>
</div>
@endsection
