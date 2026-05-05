@extends('layouts.app')
@section('title', 'Paiements')
@section('page_title', 'Finance')
@section('page_subtitle', 'Encaissements et suivi des paiements')

@section('content')
<style>
    .payments-page{display:grid;gap:18px}
    .payments-filter{
        background:#fff;
        border:1px solid #ddbba0;
        padding:18px;
    }
    .payments-filter form{
        display:grid;
        grid-template-columns:repeat(4,minmax(0,1fr)) auto auto;
        gap:12px;
        align-items:end;
    }
    .payments-filter label{
        display:block;
        margin-bottom:8px;
        font-size:12px;
        font-weight:800;
        color:#5d544b;
        text-transform:uppercase;
        letter-spacing:.08em;
    }
    .payment-table th{background:#faf1ea}
    .payment-table td{font-size:15px}
    @media (max-width:1180px){
        .payments-filter form{grid-template-columns:1fr 1fr}
    }
    @media (max-width:760px){
        .payments-filter form{grid-template-columns:1fr}
    }
</style>

<div class="payments-page">
    <div class="screen-header">
        <div class="screen-heading">
            <h2>Gestion des Paiements</h2>
            <p>Suivi des encaissements especes, carte et mobile money dans une interface alignee sur le back-office.</p>
        </div>
        <div class="screen-actions">
            @can('payments.create')
                <a href="{{ route('payments.create') }}" class="btn-primary">Nouveau paiement</a>
            @endcan
            <a href="{{ route('reports.index') }}" class="btn-secondary">Vue finance</a>
        </div>
    </div>

    <div class="metric-grid">
        <article class="metric-tile">
            <div class="metric-title">Jour</div>
            <div class="metric-figure" style="color:#16a34a">{{ number_format($stats['total_today'],0,',',' ') }}</div>
            <div class="metric-caption">{{ config('hotel.currency', 'FCFA') }}</div>
        </article>
        <article class="metric-tile">
            <div class="metric-title">Mois</div>
            <div class="metric-figure">{{ number_format($stats['total_month'],0,',',' ') }}</div>
            <div class="metric-caption">{{ config('hotel.currency', 'FCFA') }} encaisses</div>
        </article>
        <article class="metric-tile">
            <div class="metric-title">En attente</div>
            <div class="metric-figure" style="color:#a55a00">{{ $stats['pending_count'] }}</div>
            <div class="metric-caption danger">Dossiers a confirmer</div>
        </article>
        <article class="metric-tile">
            <div class="metric-title">Completes</div>
            <div class="metric-figure">{{ $stats['total_count'] }}</div>
            <div class="metric-caption">Transactions traitees</div>
        </article>
    </div>

    <section class="payments-filter">
        <form method="GET">
            <div>
                <label>Methode</label>
                <select name="method" class="form-input">
                    <option value="">Toutes</option>
                    <option value="card" @selected(request('method')==='card')>Carte</option>
                    <option value="cash" @selected(request('method')==='cash')>Especes</option>
                    <option value="check" @selected(request('method')==='check')>Cheque</option>
                    <option value="transfer" @selected(request('method')==='transfer')>Virement</option>
                    <option value="mobile_money" @selected(request('method')==='mobile_money')>Mobile Money</option>
                </select>
            </div>
            <div>
                <label>Statut</label>
                <select name="status" class="form-input">
                    <option value="">Tous</option>
                    <option value="completed" @selected(request('status')==='completed')>Complete</option>
                    <option value="pending" @selected(request('status')==='pending')>En attente</option>
                    <option value="refunded" @selected(request('status')==='refunded')>Rembourse</option>
                </select>
            </div>
            <div>
                <label>Du</label>
                <input type="date" name="date_from" value="{{ request('date_from') }}" class="form-input">
            </div>
            <div>
                <label>Au</label>
                <input type="date" name="date_to" value="{{ request('date_to') }}" class="form-input">
            </div>
            <div><button type="submit" class="btn-primary">Filtrer</button></div>
            <div><a href="{{ route('payments.index') }}" class="btn-secondary">Reinitialiser</a></div>
        </form>
    </section>

    <section class="lx-panel">
        <div class="lx-panel-head">
            <h3>Journal des Paiements</h3>
            <span class="link-accent">Vue operationnelle</span>
        </div>
        <div style="overflow:auto">
            <table class="table payment-table">
                <thead>
                    <tr>
                        <th>Reservation</th>
                        <th>Client</th>
                        <th>Montant</th>
                        <th>Methode</th>
                        <th>Statut</th>
                        <th>Date</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($payments as $payment)
                        <tr>
                            <td><a href="{{ route('reservations.show', $payment->reservation) }}" style="font-family:monospace;color:#9a5210;font-weight:700">{{ $payment->reservation->booking_number }}</a></td>
                            <td>{{ $payment->reservation->guest_full_name }}</td>
                            <td><strong>{{ number_format($payment->amount,0,',',' ') }} {{ config('hotel.currency', 'FCFA') }}</strong></td>
                            <td>{{ $payment->method_label }}</td>
                            <td><span class="badge {{ $payment->status_badge }}">{{ ucfirst($payment->status) }}</span></td>
                            <td>{{ $payment->paid_at?->format('d/m/Y H:i') ?? '—' }}</td>
                            <td><a href="{{ route('payments.show', $payment) }}" class="link-accent">Voir</a></td>
                        </tr>
                    @empty
                        <tr><td colspan="7" style="text-align:center">Aucun paiement trouve.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div style="padding:16px 18px">{{ $payments->withQueryString()->links() }}</div>
    </section>
</div>
@endsection
