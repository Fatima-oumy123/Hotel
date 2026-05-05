@extends('layouts.app')
@section('title', 'Factures')
@section('page_title', 'Factures')
@section('page_subtitle', 'Documents emis et recherche comptable')

@section('content')
<style>
    .invoices-page{display:grid;gap:18px}
    .invoice-filters{
        display:grid;
        grid-template-columns:1.4fr .9fr .9fr auto auto;
        gap:12px;
        align-items:end;
    }
    .invoice-filters label{
        display:block;
        margin-bottom:8px;
        font-size:12px;
        font-weight:800;
        color:#5d544b;
        text-transform:uppercase;
        letter-spacing:.08em;
    }
    .invoice-table th{background:#faf1ea}
    .invoice-table td{font-size:15px}
    @media (max-width:1180px){
        .invoice-filters{grid-template-columns:1fr}
    }
</style>

<div class="invoices-page">
    <div class="screen-header">
        <div class="screen-heading">
            <h2>Gestion des Factures</h2>
            <p>Recherche, filtrage et consultation des documents emis pour les reservations et prestations.</p>
        </div>
        <div class="screen-actions">
            <a href="{{ route('reports.index') }}" class="btn-secondary">Vue finance</a>
        </div>
    </div>

    <section class="lx-panel">
        <div class="lx-panel-body">
            <form method="GET" class="invoice-filters">
                <div>
                    <label>Numero facture</label>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="FACT-2024-..." class="form-input">
                </div>
                <div>
                    <label>Statut</label>
                    <select name="status" class="form-input">
                        <option value="">Tous</option>
                        <option value="issued" @selected(request('status')==='issued')>Emise</option>
                        <option value="paid" @selected(request('status')==='paid')>Payee</option>
                        <option value="cancelled" @selected(request('status')==='cancelled')>Annulee</option>
                    </select>
                </div>
                <div>
                    <label>Mois</label>
                    <input type="month" name="month" value="{{ request('month') }}" class="form-input">
                </div>
                <div><button type="submit" class="btn-primary">Filtrer</button></div>
                <div><a href="{{ route('invoices.index') }}" class="btn-secondary">Reinitialiser</a></div>
            </form>
        </div>
    </section>

    <section class="lx-panel">
        <div class="lx-panel-head">
            <h3>Journal des Factures</h3>
            <span class="link-accent">{{ $invoices->total() }} facture(s)</span>
        </div>
        <div style="overflow:auto">
            <table class="table invoice-table">
                <thead>
                    <tr>
                        <th>Facture</th>
                        <th>Reservation</th>
                        <th>Client</th>
                        <th>Sous-total</th>
                        <th>TVA</th>
                        <th>Total</th>
                        <th>Statut</th>
                        <th>Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($invoices as $invoice)
                        <tr>
                            <td style="font-family:monospace;font-weight:700;color:#9a5210">{{ $invoice->invoice_number }}</td>
                            <td><a href="{{ route('reservations.show', $invoice->reservation) }}" style="color:#9a5210;font-weight:700">{{ $invoice->reservation->booking_number }}</a></td>
                            <td>{{ $invoice->reservation->guest_full_name }}</td>
                            <td>{{ number_format($invoice->subtotal,0,',',' ') }} F</td>
                            <td>{{ number_format($invoice->tax_amount,0,',',' ') }} F</td>
                            <td><strong>{{ number_format($invoice->total,0,',',' ') }} F</strong></td>
                            <td><span class="badge {{ $invoice->status === 'issued' ? 'badge-info' : ($invoice->status === 'paid' ? 'badge-success' : 'badge-danger') }}">{{ ucfirst($invoice->status) }}</span></td>
                            <td>{{ $invoice->issued_at?->format('d/m/Y') ?? '—' }}</td>
                            <td style="display:flex;gap:8px;flex-wrap:wrap">
                                <a href="{{ route('invoices.show', $invoice) }}" class="btn-secondary">Voir</a>
                                <a href="{{ route('invoices.download', $invoice) }}" class="btn-secondary">PDF</a>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="9" style="text-align:center">Aucune facture.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div style="padding:16px 18px">{{ $invoices->withQueryString()->links() }}</div>
    </section>
</div>
@endsection
