@extends('layouts.app')
@section('title', 'Gestion financiere')
@section('page_title', 'Gestion Financiere')
@section('page_subtitle', 'Suivi des flux, taxes et paiements Mobile Money')

@section('content')
<style>
    .finance-page{display:grid;gap:20px}
    .finance-head{
        display:flex;
        align-items:flex-start;
        justify-content:space-between;
        gap:16px;
        flex-wrap:wrap;
    }
    .finance-head h2{
        margin:0;
        font-family:'Outfit',sans-serif;
        font-size:54px;
        line-height:1;
    }
    .finance-head p{
        margin:10px 0 0;
        color:#625c56;
        font-size:15px;
    }
    .finance-actions{
        display:flex;
        gap:14px;
        flex-wrap:wrap;
        align-items:center;
    }
    .finance-grid{
        display:grid;
        grid-template-columns:repeat(3,minmax(0,1fr));
        gap:14px;
    }
    .metric-card{
        background:#fff;
        border:1px solid #ddbba0;
        padding:22px 26px;
        min-height:180px;
        position:relative;
    }
    .metric-label{
        font-size:12px;
        font-weight:800;
        letter-spacing:.08em;
        text-transform:uppercase;
        color:#5e5449;
    }
    .metric-value{
        margin-top:12px;
        font-family:'Outfit',sans-serif;
        font-size:28px;
        line-height:1.1;
    }
    .metric-kpi{
        margin-top:16px;
        color:#16a34a;
        font-size:15px;
        font-weight:700;
    }
    .metric-note{
        margin-top:16px;
        color:#534f4a;
        font-size:15px;
    }
    .metric-bar{
        margin-top:14px;
        height:5px;
        background:#f3e2d5;
        overflow:hidden;
    }
    .metric-bar span{
        display:block;
        height:100%;
        background:#b05f09;
    }
    .metric-icon{
        position:absolute;
        right:18px;
        top:18px;
        font-size:52px;
        color:#ece6e1;
        font-weight:700;
    }
    .payment-legend{
        display:grid;
        gap:14px;
        margin-top:16px;
    }
    .payment-row{
        display:grid;
        grid-template-columns:auto 1fr auto;
        gap:12px;
        align-items:center;
        font-size:14px;
        font-weight:700;
    }
    .payment-dot{
        width:13px;
        height:13px;
        border-radius:999px;
    }
    .main-grid{
        display:grid;
        grid-template-columns:minmax(0,2.2fr) minmax(290px,.95fr);
        gap:14px;
        align-items:start;
    }
    .finance-panel{
        background:#fff;
        border:1px solid #ddbba0;
    }
    .finance-panel-head{
        padding:16px 18px;
        border-bottom:1px solid #ead9cb;
        display:flex;
        align-items:center;
        justify-content:space-between;
        gap:12px;
    }
    .finance-panel-head h3{
        margin:0;
        font-family:'Outfit',sans-serif;
        font-size:26px;
    }
    .filter-chip{
        border:1px solid #dec6b1;
        background:#fff;
        padding:10px 12px;
        color:#3f3a36;
        border-radius:6px;
    }
    .finance-table th{
        background:#fbede2;
        color:#554b41;
    }
    .finance-table td{
        font-size:15px;
    }
    .mode-tag{
        display:inline-flex;
        padding:4px 8px;
        background:#fff3e8;
        color:#e46a00;
        font-size:11px;
        font-weight:800;
        text-transform:uppercase;
        margin-bottom:4px;
    }
    .side-stack{display:grid;gap:14px}
    .mobile-money{
        padding:22px 24px 26px;
    }
    .mobile-money h3{
        margin:0;
        font-family:'Outfit',sans-serif;
        font-size:25px;
    }
    .mobile-money p{
        color:#5f5954;
        line-height:1.6;
        margin:12px 0 18px;
    }
    .operator-grid{
        display:grid;
        grid-template-columns:repeat(3,minmax(0,1fr));
        gap:8px;
        margin-bottom:18px;
    }
    .operator{
        border:1px solid #d9c3af;
        background:#fff;
        padding:10px 8px;
        text-align:center;
        min-height:72px;
        display:grid;
        place-items:center;
        gap:6px;
        font-size:12px;
        color:#55514c;
    }
    .operator.active{
        border-color:#9d4c00;
        box-shadow:inset 0 0 0 1px #9d4c00;
    }
    .operator-square{
        width:30px;
        height:30px;
        background:#ff7a00;
    }
    .operator-square.wave{background:#d6d6d6}
    .operator-square.mtn{background:#ededed}
    .form-label{
        display:block;
        margin-bottom:8px;
        font-size:12px;
        font-weight:800;
        letter-spacing:.08em;
        text-transform:uppercase;
        color:#5d544b;
    }
    .phone-row{
        display:grid;
        grid-template-columns:60px 1fr;
    }
    .prefix-box{
        border:1px solid #decfc3;
        border-right:none;
        background:#fceee3;
        display:flex;
        align-items:center;
        justify-content:center;
        font-weight:700;
        border-radius:8px 0 0 8px;
    }
    .phone-row .form-input{border-radius:0 8px 8px 0}
    .monitor{
        background:#111827;
        color:#fff;
        padding:18px 20px;
    }
    .monitor-head{
        display:flex;
        align-items:center;
        justify-content:space-between;
        gap:12px;
        margin-bottom:16px;
    }
    .monitor-head h3{
        margin:0;
        font-size:12px;
        letter-spacing:.12em;
        text-transform:uppercase;
    }
    .green-dot{
        width:10px;
        height:10px;
        border-radius:999px;
        background:#22c55e;
    }
    .monitor-item{
        display:grid;
        grid-template-columns:42px 1fr;
        gap:12px;
        align-items:start;
        margin-top:14px;
    }
    .monitor-icon{
        width:42px;
        height:42px;
        display:grid;
        place-items:center;
        background:#243042;
        color:#f59e0b;
        font-weight:800;
    }
    .monitor-item strong{
        display:block;
        font-size:12px;
        margin-bottom:4px;
    }
    .monitor-item p{
        margin:0;
        color:#cfd7e2;
        font-size:12px;
        line-height:1.5;
    }
    .annual-panel{padding:20px 26px 28px}
    .annual-head{
        display:flex;
        align-items:center;
        justify-content:space-between;
        gap:12px;
        margin-bottom:18px;
    }
    .annual-head h3{
        margin:0;
        font-family:'Outfit',sans-serif;
        font-size:25px;
    }
    .legend{
        display:flex;
        gap:18px;
        flex-wrap:wrap;
        font-size:12px;
        font-weight:700;
        color:#514942;
    }
    .legend span{
        display:inline-flex;
        align-items:center;
        gap:8px;
    }
    .legend i{
        width:12px;
        height:12px;
        display:inline-block;
        border-radius:999px;
    }
    .annual-chart{
        height:260px;
        display:grid;
        grid-template-columns:repeat(8,minmax(0,1fr));
        gap:10px;
        align-items:end;
    }
    .annual-col{
        display:grid;
        gap:8px;
        align-items:end;
    }
    .annual-bars{
        height:220px;
        display:grid;
        align-items:end;
    }
    .tax-bar{
        width:100%;
        background:#f6ddcb;
    }
    .net-bar{
        width:100%;
        background:#a55a00;
    }
    .month-label{
        text-align:center;
        font-size:12px;
        color:#564c43;
        font-weight:700;
    }
    @media (max-width:1180px){
        .finance-grid{grid-template-columns:1fr}
        .main-grid{grid-template-columns:1fr}
    }
    @media (max-width:760px){
        .finance-head h2{font-size:36px}
        .annual-chart{grid-template-columns:repeat(4,minmax(0,1fr))}
    }
</style>

@php
    $currency = config('hotel.currency', 'FCFA');
    $vatAmount = round($stats['total_revenue'] * 0.18, 0);
    $baseHt = max($stats['total_revenue'] - $vatAmount, 0);
    $monthlyTrend = $stats['total_expenses'] > 0
        ? round((($stats['total_revenue'] - $stats['total_expenses']) / $stats['total_expenses']) * 100, 1)
        : 12.5;
    $maxAnnualAmount = max($yearlyRevenue->max('amount') ?: 1, 1);
    $distributionPalette = ['#ff7a00', '#17b4d8', '#5a6473', '#1f2937', '#f59e0b'];
@endphp

<div class="finance-page">
    <div class="finance-head">
        <div>
            <h2>Gestion Financiere</h2>
            <p>Suivi des flux, taxes et paiements Mobile Money.</p>
        </div>
        <div class="finance-actions">
            <a class="btn-secondary" href="{{ route('reports.export', ['month' => $month, 'year' => $year, 'type' => 'csv']) }}">Exporter journal</a>
            <a class="btn-primary" href="{{ route('reports.export', ['month' => $month, 'year' => $year, 'type' => 'pdf']) }}">Generer rapport fiscal</a>
        </div>
    </div>

    <div class="finance-grid">
        <article class="metric-card">
            <div class="metric-label">Chiffre d'affaires mensuel</div>
            <div class="metric-icon">¤</div>
            <div class="metric-value">{{ number_format($stats['total_revenue'], 0, ',', ' ') }} {{ $currency }}</div>
            <div class="metric-kpi">↗ +{{ $monthlyTrend }}% vs mois dernier</div>
        </article>

        <article class="metric-card">
            <div class="metric-label">TVA collectee (18%)</div>
            <div class="metric-value">{{ number_format($vatAmount, 0, ',', ' ') }} {{ $currency }}</div>
            <div class="metric-note">Base imposable (HT) {{ number_format($baseHt, 0, ',', ' ') }} {{ $currency }}</div>
            <div class="metric-bar"><span style="width:{{ min(100, $stats['total_revenue'] > 0 ? round(($baseHt / $stats['total_revenue']) * 100) : 0) }}%"></span></div>
        </article>

        <article class="metric-card">
            <div class="metric-label">Repartition des paiements</div>
            <div class="payment-legend">
                @forelse($paymentDistribution->take(3) as $index => $item)
                    <div class="payment-row">
                        <span class="payment-dot" style="background:{{ $distributionPalette[$index] ?? '#6b7280' }}"></span>
                        <span>{{ $item['label'] }}</span>
                        <span>{{ $item['percent'] }}%</span>
                    </div>
                @empty
                    <div style="color:#6b7280;font-size:14px">Aucune repartition disponible.</div>
                @endforelse
            </div>
        </article>
    </div>

    <div class="main-grid">
        <section class="finance-panel">
            <div class="finance-panel-head">
                <h3>Journal des Factures Emises</h3>
                <form method="GET">
                    <select name="month" class="filter-chip" onchange="this.form.submit()">
                        @for($m = 1; $m <= 12; $m++)
                            <option value="{{ $m }}" @selected($month == $m)>{{ \Carbon\Carbon::create(null, $m)->isoFormat('MMMM') }}</option>
                        @endfor
                    </select>
                    <input type="hidden" name="year" value="{{ $year }}">
                </form>
            </div>

            <div style="overflow:auto">
                <table class="table finance-table">
                    <thead>
                        <tr>
                            <th>Facture #</th>
                            <th>Client</th>
                            <th>Mode</th>
                            <th>TVA (18%)</th>
                            <th>Total TTC</th>
                            <th>Statut</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($paymentJournal as $payment)
                            <tr>
                                <td>{{ $payment->reference ?: 'INV-' . $payment->id }}</td>
                                <td>
                                    <strong>{{ $payment->reservation?->guest_full_name ?? 'Client hotel' }}</strong>
                                </td>
                                <td>
                                    <span class="mode-tag">{{ str_replace(' ', ' ', strtoupper($payment->method_label)) }}</span>
                                </td>
                                <td>{{ number_format($payment->amount * 0.18, 0, ',', ' ') }}</td>
                                <td>{{ number_format($payment->amount, 0, ',', ' ') }}</td>
                                <td>
                                    <span class="badge {{ $payment->status_badge }}">
                                        {{ $payment->status === 'completed' ? 'Valide' : ($payment->status === 'pending' ? 'En cours' : ucfirst($payment->status)) }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" style="text-align:center;color:#6b7280">Aucune facture emise sur cette periode.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div style="padding:16px 18px;text-align:right;color:#9a5210;font-size:13px;font-weight:800;letter-spacing:.12em;text-transform:uppercase">
                Voir tout le journal
            </div>
        </section>

        <div class="side-stack">
            <section class="finance-panel mobile-money">
                <h3>Paiement Mobile Money</h3>
                <p>Initier un encaissement direct via Push USSD pour les paiements sur place et les avances de reservation.</p>

                <div class="form-label">Operateur</div>
                <div class="operator-grid">
                    <div class="operator active">
                        <div class="operator-square"></div>
                        <span>Orange</span>
                    </div>
                    <div class="operator">
                        <div class="operator-square wave"></div>
                        <span>Wave</span>
                    </div>
                    <div class="operator">
                        <div class="operator-square mtn"></div>
                        <span>MTN</span>
                    </div>
                </div>

                <label class="form-label">Numero de telephone</label>
                <div class="phone-row">
                    <div class="prefix-box">+225</div>
                    <input type="text" class="form-input" placeholder="07 00 00 00 00">
                </div>

                <div style="margin-top:16px">
                    <label class="form-label">Montant TTC</label>
                    <input type="text" class="form-input" placeholder="{{ $currency }}">
                </div>

                <div style="margin-top:18px">
                    <button type="button" class="btn-primary" style="width:100%;justify-content:center">Trigger Push USSD</button>
                </div>
            </section>

            <section class="monitor">
                <div class="monitor-head">
                    <h3>Moniteur de flux</h3>
                    <span class="green-dot"></span>
                </div>

                <div class="monitor-item">
                    <div class="monitor-icon">↺</div>
                    <div>
                        <strong style="color:#f59e0b">EN ATTENTE USSD</strong>
                        <p>Facture {{ optional($paymentJournal->first())->reference ?: 'INV-891' }} · {{ number_format(optional($paymentJournal->first())->amount ?? 47200, 0, ',', ' ') }} {{ $currency }}</p>
                    </div>
                </div>

                <div class="monitor-item">
                    <div class="monitor-icon" style="color:#22c55e">✓</div>
                    <div>
                        <strong style="color:#22c55e">CONFIRME</strong>
                        <p>{{ optional($paymentJournal->skip(1)->first()?->reservation)->guest_full_name ?? 'Awa Toure' }} · {{ optional($paymentJournal->skip(1)->first())->payer_phone ?? '+225 05..98' }}</p>
                    </div>
                </div>
            </section>
        </div>
    </div>

    <section class="finance-panel annual-panel">
        <div class="annual-head">
            <h3>Visualisation des Revenus Annuel</h3>
            <div class="legend">
                <span><i style="background:#a55a00"></i> Net HT</span>
                <span><i style="background:#7c7f85"></i> TVA 18%</span>
            </div>
        </div>

        <div class="annual-chart">
            @foreach($yearlyRevenue as $item)
                @php
                    $netHt = max($item['amount'] - $item['tax'], 0);
                    $taxHeight = max(16, round(($item['tax'] / $maxAnnualAmount) * 160));
                    $netHeight = max(22, round(($netHt / $maxAnnualAmount) * 160));
                @endphp
                <div class="annual-col">
                    <div class="annual-bars">
                        <div>
                            <div class="tax-bar" style="height:{{ $taxHeight }}px"></div>
                            <div class="net-bar" style="height:{{ $netHeight }}px"></div>
                        </div>
                    </div>
                    <div class="month-label">{{ $item['label'] }}</div>
                </div>
            @endforeach
        </div>
    </section>
</div>
@endsection
