@extends('layouts.guest')
@section('title', 'Gerer ma reservation')
@section('hero_eyebrow', 'Assistance reservation')
@section('hero_title', 'Retrouvez ou annulez votre reservation facilement.')
@section('hero_copy', 'Saisissez votre numero de reservation et le telephone utilise lors de la reservation pour retrouver votre dossier a Rufisque.')
@section('card_title', 'Besoin de modifier ?')
@section('card_copy', 'Retrouvez votre reservation rapidement et, si necessaire, annulez-la selon les conditions affichees.')
@section('hero_stats')
    <div class="box"><strong>48h</strong><span>annulation gratuite</span></div>
    <div class="box"><strong>2</strong><span>champs requis</span></div>
    <div class="box"><strong>FR</strong><span>accompagnement clair</span></div>
@endsection

@section('content')
<div style="display:grid;grid-template-columns:1.05fr .95fr;gap:16px">
    <section class="panel">
        <div class="panel-head"><h3>Gerer ma reservation</h3></div>
        <div class="panel-body">
            @if(session('success'))
                <div class="alert success" style="margin-bottom:16px">{{ session('success') }}</div>
            @endif
            @if($errors->any())
                <div class="alert error" style="margin-bottom:16px">{{ $errors->first() }}</div>
            @endif

            <form action="{{ route('guest.cancel.process') }}" method="POST" style="display:grid;gap:16px">
                @csrf
                <div class="field">
                    <label>Numero de reservation</label>
                    <input type="text" name="booking_number" value="{{ old('booking_number') }}" required placeholder="REZ-2026-00001">
                </div>
                <div class="field">
                    <label>Telephone utilise</label>
                    <input type="text" name="guest_phone" value="{{ old('guest_phone') }}" required placeholder="+221 77 000 00 00">
                </div>
                <button type="submit" class="btn-danger" style="width:100%;padding:16px 18px">Annuler ma reservation</button>
            </form>

            <div style="margin-top:16px">
                <a href="{{ route('guest.step1') }}" class="btn-secondary">Faire une nouvelle reservation</a>
            </div>
        </div>
    </section>

    <aside class="panel">
        <div class="panel-head"><h3>Regles d annulation</h3></div>
        <div class="panel-body" style="display:grid;gap:12px">
            <div style="padding:14px;border-radius:14px;background:#f8f3ed;border:1px solid #eddcc7">
                <strong>Annulation gratuite</strong>
                <div style="margin-top:6px;color:#64748b;font-size:13px">Possible jusqu a 48h avant votre date d arrivee.</div>
            </div>
            <div style="padding:14px;border-radius:14px;background:#fff;border:1px solid #eceef2">
                <strong>Verification securisee</strong>
                <div style="margin-top:6px;color:#64748b;font-size:13px">Le systeme croise le numero de reservation et le telephone saisi a la creation du dossier.</div>
            </div>
            <div style="padding:14px;border-radius:14px;background:#fff;border:1px solid #eceef2">
                <strong>Besoin d aide ?</strong>
                <div style="margin-top:6px;color:#64748b;font-size:13px">Contactez la reception si vous ne retrouvez plus vos references.</div>
            </div>
        </div>
    </aside>
</div>
@endsection
