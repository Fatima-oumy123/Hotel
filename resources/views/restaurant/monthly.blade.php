@extends('layouts.app')
@section('title', 'Rapport mensuel restauration')
@section('page_title', 'Rapport mensuel restauration')
@section('page_subtitle', 'Lecture visuelle des ventes et de la rentabilite du restaurant')

@section('content')
<div class="page-shell">
    <section class="page-hero">
        <h2>{{ \Carbon\Carbon::create($year, $month, 1)->isoFormat('MMMM YYYY') }}</h2>
        <p>Le rapport restauration a ete rendu plus lisible et plus vivant, avec un meilleur equilibre entre chiffres, graphique et analyse d exploitation.</p>
        <div class="hero-pills">
            <span class="hero-pill">CA mensuel</span>
            <span class="hero-pill">Ticket moyen</span>
            <span class="hero-pill">Inventaire</span>
        </div>
    </section>

    <section class="card" style="padding:18px">
        <form method="GET" class="detail-grid" style="grid-template-columns:.8fr .8fr auto">
            <div>
                <label style="display:block;margin-bottom:8px;font-size:12px;font-weight:800;color:#64748b;text-transform:uppercase">Mois</label>
                <select name="month" class="form-input">
                    @for($m = 1; $m <= 12; $m++)
                        <option value="{{ $m }}" @selected($month == $m)>{{ \Carbon\Carbon::create(null, $m)->isoFormat('MMMM') }}</option>
                    @endfor
                </select>
            </div>
            <div>
                <label style="display:block;margin-bottom:8px;font-size:12px;font-weight:800;color:#64748b;text-transform:uppercase">Annee</label>
                <select name="year" class="form-input">
                    @for($y = date('Y'); $y >= date('Y') - 3; $y--)
                        <option value="{{ $y }}" @selected($year == $y)>{{ $y }}</option>
                    @endfor
                </select>
            </div>
            <div style="display:flex;align-items:end"><button type="submit" class="btn-primary">Afficher</button></div>
        </form>
    </section>

    @php
        $totalRevenue  = $data->sum('revenue');
        $totalOrders   = $data->sum('count');
        $avgPerDay     = $data->count() > 0 ? round($totalRevenue / $data->count()) : 0;
        $avgPerOrder   = $totalOrders > 0 ? round($totalRevenue / $totalOrders) : 0;
    @endphp

    <div class="detail-grid" style="grid-template-columns:repeat(4,minmax(0,1fr))">
        <div class="detail-card"><h3>CA du mois</h3><div style="font-family:'Outfit',sans-serif;font-size:34px;color:#16a34a">{{ number_format($totalRevenue,0,',',' ') }}</div><div style="color:#64748b;font-size:13px">FCFA</div></div>
        <div class="detail-card"><h3>Commandes</h3><div style="font-family:'Outfit',sans-serif;font-size:34px">{{ $totalOrders }}</div></div>
        <div class="detail-card"><h3>Moy. / jour</h3><div style="font-family:'Outfit',sans-serif;font-size:34px">{{ number_format($avgPerDay,0,',',' ') }}</div><div style="color:#64748b;font-size:13px">FCFA</div></div>
        <div class="detail-card"><h3>Ticket moyen</h3><div style="font-family:'Outfit',sans-serif;font-size:34px">{{ number_format($avgPerOrder,0,',',' ') }}</div><div style="color:#64748b;font-size:13px">FCFA</div></div>
    </div>

    <div class="detail-grid" style="grid-template-columns:1.2fr .8fr">
        <section class="card" style="padding:22px">
            <h3 style="margin:0 0 16px;font-family:'Outfit',sans-serif;font-size:28px">Revenus journaliers</h3>
            @if($data->count() > 0)
                <canvas id="revenueChart" height="220"></canvas>
            @else
                <div style="color:#64748b">Aucune donnee pour ce mois.</div>
            @endif
        </section>
        <section class="card" style="padding:22px">
            <h3 style="margin:0 0 16px;font-family:'Outfit',sans-serif;font-size:28px">Inventaire du mois</h3>
            <div style="display:grid;gap:12px">
                @forelse($inventory as $item)
                    @php $ratio = $item['stock'] > 0 ? round(($item['consumed'] / $item['stock']) * 100) : 0; @endphp
                    <div>
                        <div style="display:flex;justify-content:space-between;gap:10px"><span>{{ $item['name'] }}</span><strong>{{ $item['consumed'] }} / {{ $item['stock'] }} {{ $item['unit'] }}</strong></div>
                        <div style="margin-top:6px;height:8px;background:#e5e7eb;border-radius:999px;overflow:hidden"><span style="display:block;height:100%;width:{{ $ratio }}%;background:{{ $ratio > 80 ? '#ef4444' : ($ratio > 50 ? '#f59e0b' : '#22c55e') }}"></span></div>
                    </div>
                @empty
                    <div style="color:#64748b">Aucun inventaire disponible.</div>
                @endforelse
            </div>
        </section>
    </div>

    <section class="card">
        <div style="padding:18px 20px;border-bottom:1px solid #eceef2"><h3 style="margin:0;font-family:'Outfit',sans-serif;font-size:28px">Detail journalier</h3></div>
        <div style="overflow:auto">
            <table class="table">
                <thead><tr><th>Date</th><th>Commandes</th><th>Chiffre d affaires</th><th>Ticket moyen</th><th>% du mois</th></tr></thead>
                <tbody>
                    @forelse($data as $day)
                        @php $ticketMoyen = $day->count > 0 ? round($day->revenue / $day->count) : 0; $pctMois = $totalRevenue > 0 ? round(($day->revenue / $totalRevenue) * 100, 1) : 0; @endphp
                        <tr>
                            <td>{{ \Carbon\Carbon::parse($day->date)->isoFormat('dddd D MMM') }}</td>
                            <td>{{ $day->count }}</td>
                            <td><strong>{{ number_format($day->revenue, 0, ',', ' ') }} FCFA</strong></td>
                            <td>{{ number_format($ticketMoyen, 0, ',', ' ') }} FCFA</td>
                            <td>{{ $pctMois }}%</td>
                        </tr>
                    @empty
                        <tr><td colspan="5" style="text-align:center">Aucune vente enregistree pour ce mois.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
@if($data->count() > 0)
const ctx = document.getElementById('revenueChart').getContext('2d');
new Chart(ctx, {
    type: 'bar',
    data: {
        labels: {!! json_encode($data->map(fn($d) => \Carbon\Carbon::parse($d->date)->format('d/m'))) !!},
        datasets: [{
            label: 'Revenus (FCFA)',
            data: {!! json_encode($data->pluck('revenue')) !!},
            backgroundColor: 'rgba(181,101,24,0.7)',
            borderColor: 'rgba(181,101,24,1)',
            borderWidth: 1,
            borderRadius: 8,
        }]
    },
    options: {
        responsive: true,
        plugins: { legend: { display: false } },
        scales: { y: { beginAtZero: true, ticks: { callback: v => v.toLocaleString('fr') + ' F' } } }
    }
});
@endif
</script>
@endpush
@endsection
