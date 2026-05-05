@extends('layouts.app')
@section('title', 'Calendrier des reservations')
@section('page_title', 'Calendrier des reservations')
@section('page_subtitle', 'Vue planning plus claire et moins technique des sejours')

@push('styles')
<link href='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/index.global.min.css' rel='stylesheet' />
<style>
    .fc{font-family:'Manrope',sans-serif}
    .fc .fc-toolbar-title{font-family:'Outfit',sans-serif;font-size:28px}
    .fc .fc-button{
        background:#b56518;border-color:#b56518;border-radius:10px;padding:.5rem .9rem;
    }
    .fc .fc-button:hover,.fc .fc-button-primary:not(:disabled).fc-button-active{background:#8d4a0f;border-color:#8d4a0f}
    .fc-theme-standard td,.fc-theme-standard th,.fc-theme-standard .fc-scrollgrid{border-color:#e5e7eb}
</style>
@endpush

@section('content')
<div class="page-shell">
    <section class="page-hero">
        <h2>Visualiser les sejours sur le planning</h2>
        <p>Le calendrier de reservations profite lui aussi d une enveloppe plus moderne, pour que la planification garde le meme niveau visuel que le reste du site.</p>
        <div class="hero-pills">
            <span class="hero-pill">Vue mensuelle</span>
            <span class="hero-pill">Vue hebdomadaire</span>
            <span class="hero-pill">Acces rapide aux dossiers</span>
        </div>
    </section>

    <div style="display:flex;justify-content:space-between;gap:12px;flex-wrap:wrap">
        <div style="display:flex;gap:14px;flex-wrap:wrap;color:#64748b;font-size:13px">
            <span><span style="display:inline-block;width:10px;height:10px;border-radius:999px;background:#3b82f6;margin-right:6px"></span>Confirmee</span>
            <span><span style="display:inline-block;width:10px;height:10px;border-radius:999px;background:#22c55e;margin-right:6px"></span>En cours</span>
            <span><span style="display:inline-block;width:10px;height:10px;border-radius:999px;background:#f59e0b;margin-right:6px"></span>En attente</span>
        </div>
        <a href="{{ route('reservations.create') }}" class="btn-primary">Nouvelle reservation</a>
    </div>

    <section class="card" style="padding:22px">
        <div id="calendar"></div>
    </section>
</div>
@endsection

@push('scripts')
<script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/index.global.min.js'></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    var calendarEl = document.getElementById('calendar');
    var calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridMonth',
        locale: 'fr',
        height: 680,
        headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: 'dayGridMonth,timeGridWeek,listWeek'
        },
        events: {!! $reservations !!},
        eventClick: function(info) {
            window.location.href = '/reservations/' + info.event.id;
        },
        eventDidMount: function(info) {
            info.el.style.cursor = 'pointer';
            info.el.style.borderRadius = '8px';
        }
    });
    calendar.render();
});
</script>
@endpush
