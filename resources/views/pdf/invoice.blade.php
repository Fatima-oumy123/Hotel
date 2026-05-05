<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<style>
    * { margin: 0; padding: 0; box-sizing: border-box; }
    body { font-family: DejaVu Sans, sans-serif; font-size: 12px; color: #1e293b; }
    .header { background: #0f172a; color: white; padding: 30px; display: flex; justify-content: space-between; }
    .hotel-name { font-size: 22px; font-weight: bold; color: #f59e0b; }
    .invoice-title { font-size: 28px; font-weight: bold; color: white; }
    .content { padding: 30px; }
    .two-col { display: flex; justify-content: space-between; margin-bottom: 30px; }
    .section-title { font-size: 10px; font-weight: bold; text-transform: uppercase;
                     letter-spacing: 1px; color: #64748b; border-bottom: 2px solid #f59e0b;
                     padding-bottom: 4px; margin-bottom: 12px; }
    table { width: 100%; border-collapse: collapse; }
    th { background: #f1f5f9; padding: 10px 12px; text-align: left; font-size: 10px;
         text-transform: uppercase; letter-spacing: 0.5px; color: #475569; }
    td { padding: 10px 12px; border-bottom: 1px solid #f1f5f9; }
    .totals { margin-left: auto; width: 300px; margin-top: 20px; }
    .total-row { display: flex; justify-content: space-between; padding: 6px 0; }
    .total-row.final { font-size: 16px; font-weight: bold; background: #0f172a; color: white;
                       padding: 12px; border-radius: 6px; margin-top: 8px; }
    .badge { padding: 4px 10px; border-radius: 4px; font-size: 10px; font-weight: bold; }
    .badge-paid { background: #dcfce7; color: #166534; }
    .footer { background: #f8fafc; border-top: 1px solid #e2e8f0; padding: 20px 30px;
              text-align: center; color: #94a3b8; font-size: 10px; }
</style>
</head>
<body>
<div class="header">
    <div>
        <div class="hotel-name">🏨 {{ config('app.name') }}</div>
        <div style="color: #94a3b8; margin-top: 4px; font-size: 11px;">
            Dakar, Sénégal · Tel: +221 XX XXX XX XX<br>
            contact@hotel.com
        </div>
    </div>
    <div style="text-align: right;">
        <div class="invoice-title">FACTURE</div>
        <div style="color: #f59e0b; font-size: 15px; margin-top: 4px;">{{ $invoice->invoice_number }}</div>
        <div style="color: #94a3b8; font-size: 11px; margin-top: 4px;">
            Émise le : {{ $invoice->issued_at->format('d/m/Y') }}
        </div>
    </div>
</div>

<div class="content">
    <div class="two-col">
        <div style="width: 45%">
            <div class="section-title">Facturé à</div>
            <p style="font-weight: bold; font-size: 14px;">{{ $reservation->guest_full_name }}</p>
            <p style="color: #64748b;">Tél: {{ $reservation->guest_phone }}</p>
            @if($reservation->guest_email)
            <p style="color: #64748b;">{{ $reservation->guest_email }}</p>
            @endif
            @if($reservation->guest_id_number)
            <p style="color: #64748b;">N° pièce: {{ $reservation->guest_id_number }}</p>
            @endif
        </div>
        <div style="width: 45%">
            <div class="section-title">Détails du séjour</div>
            <p>Réservation: <strong>{{ $reservation->booking_number }}</strong></p>
            <p>Chambre: <strong>{{ $reservation->room->number }} — {{ $reservation->room->roomType->name }}</strong></p>
            <p>Arrivée: <strong>{{ $reservation->check_in->format('d/m/Y') }}</strong></p>
            <p>Départ: <strong>{{ $reservation->check_out->format('d/m/Y') }}</strong></p>
            <p>Durée: <strong>{{ $reservation->nights }} nuit(s)</strong></p>
        </div>
    </div>

    <div class="section-title">Détail des prestations</div>
    <table>
        <thead>
            <tr>
                <th>Description</th>
                <th style="text-align: center;">Nuits</th>
                <th style="text-align: right;">Prix/nuit</th>
                <th style="text-align: right;">Total</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>Hébergement — {{ $reservation->room->roomType->name }} (Ch. {{ $reservation->room->number }})</td>
                <td style="text-align: center;">{{ $reservation->nights }}</td>
                <td style="text-align: right;">{{ number_format($reservation->price_per_night, 0, ',', ' ') }} FCFA</td>
                <td style="text-align: right;">{{ number_format($reservation->total_amount, 0, ',', ' ') }} FCFA</td>
            </tr>
        </tbody>
    </table>

    <div class="totals">
        <div class="total-row">
            <span style="color: #64748b;">Sous-total</span>
            <span>{{ number_format($invoice->subtotal, 0, ',', ' ') }} FCFA</span>
        </div>
        @if($reservation->discount > 0)
        <div class="total-row" style="color: #16a34a;">
            <span>Réduction</span>
            <span>-{{ number_format($reservation->discount, 0, ',', ' ') }} FCFA</span>
        </div>
        @endif
        <div class="total-row">
            <span style="color: #64748b;">TVA ({{ $invoice->tax_rate }}%)</span>
            <span>{{ number_format($invoice->tax_amount, 0, ',', ' ') }} FCFA</span>
        </div>
        @if($invoice->stay_tax > 0)
        <div class="total-row">
            <span style="color: #64748b;">Taxe de séjour</span>
            <span>{{ number_format($invoice->stay_tax, 0, ',', ' ') }} FCFA</span>
        </div>
        @endif
        <div class="total-row final">
            <span>TOTAL TTC</span>
            <span>{{ number_format($invoice->total, 0, ',', ' ') }} FCFA</span>
        </div>
    </div>
</div>

<div class="footer">
    Merci de votre confiance · {{ config('app.name') }} · Document généré automatiquement
</div>
</body>
</html>
