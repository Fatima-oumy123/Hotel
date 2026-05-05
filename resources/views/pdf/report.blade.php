<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<style>
    * { margin: 0; padding: 0; box-sizing: border-box; }
    body { font-family: DejaVu Sans, sans-serif; font-size: 11px; color: #1e293b; }
    .header { background: #0f172a; color: white; padding: 20px 24px; display: flex; justify-content: space-between; align-items: center; }
    .hotel { font-size: 18px; font-weight: bold; color: #f59e0b; }
    .report-title { font-size: 14px; color: #94a3b8; margin-top: 4px; }
    .period { color: white; text-align: right; font-size: 13px; }
    .content { padding: 20px 24px; }
    .kpis { display: flex; gap: 12px; margin-bottom: 20px; }
    .kpi { flex: 1; background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 8px; padding: 12px; text-align: center; }
    .kpi-label { font-size: 9px; color: #94a3b8; text-transform: uppercase; letter-spacing: 0.5px; }
    .kpi-value { font-size: 18px; font-weight: bold; color: #1e293b; margin-top: 4px; }
    .kpi-sub { font-size: 9px; color: #64748b; margin-top: 2px; }
    h2 { font-size: 13px; font-weight: bold; color: #1e293b; margin-bottom: 10px; padding-bottom: 6px; border-bottom: 2px solid #f59e0b; }
    table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
    th { background: #f1f5f9; padding: 8px 10px; text-align: left; font-size: 9px; color: #475569; text-transform: uppercase; letter-spacing: 0.5px; }
    td { padding: 7px 10px; border-bottom: 1px solid #f1f5f9; font-size: 10px; }
    tr:nth-child(even) td { background: #fafafa; }
    .badge { padding: 2px 8px; border-radius: 100px; font-size: 9px; font-weight: bold; }
    .badge-green { background: #dcfce7; color: #166534; }
    .badge-blue  { background: #dbeafe; color: #1e40af; }
    .badge-red   { background: #fee2e2; color: #991b1b; }
    .badge-gray  { background: #f1f5f9; color: #475569; }
    .footer { padding: 12px 24px; background: #f8fafc; border-top: 1px solid #e2e8f0; font-size: 9px; color: #94a3b8; text-align: center; }
    .text-right { text-align: right; }
    .font-bold { font-weight: bold; }
    .text-amber { color: #b45309; }
</style>
</head>
<body>
<div class="header">
    <div>
        <div class="hotel">🏨 {{ config('app.name') }}</div>
        <div class="report-title">Rapport d'activité mensuel</div>
    </div>
    <div class="period">
        <div>{{ $from->isoFormat('MMMM YYYY') }}</div>
        <div style="font-size:11px;color:#64748b">Généré le {{ now()->format('d/m/Y H:i') }}</div>
    </div>
</div>

<div class="content">
    <div class="kpis">
        <div class="kpi">
            <div class="kpi-label">Réservations</div>
            <div class="kpi-value">{{ $reservations->count() }}</div>
        </div>
        <div class="kpi">
            <div class="kpi-label">Nuitées</div>
            <div class="kpi-value">{{ $reservations->where('status','!=','cancelled')->sum('nights') }}</div>
        </div>
        <div class="kpi">
            <div class="kpi-label">Revenus (FCFA)</div>
            <div class="kpi-value text-amber">{{ number_format($reservations->whereNotIn('status',['cancelled'])->sum('final_amount'),0,',',' ') }}</div>
        </div>
        <div class="kpi">
            <div class="kpi-label">Annulations</div>
            <div class="kpi-value">{{ $reservations->where('status','cancelled')->count() }}</div>
        </div>
    </div>

    <h2>Détail des réservations</h2>
    <table>
        <thead>
            <tr>
                <th>N° Réservation</th>
                <th>Client</th>
                <th>Chambre</th>
                <th>Arrivée</th>
                <th>Départ</th>
                <th class="text-right">Nuits</th>
                <th class="text-right">Montant</th>
                <th>Statut</th>
            </tr>
        </thead>
        <tbody>
            @foreach($reservations as $r)
            <tr>
                <td><strong>{{ $r->booking_number }}</strong></td>
                <td>{{ $r->guest_full_name }}</td>
                <td>Ch.{{ $r->room->number }}</td>
                <td>{{ $r->check_in->format('d/m/Y') }}</td>
                <td>{{ $r->check_out->format('d/m/Y') }}</td>
                <td class="text-right">{{ $r->nights }}</td>
                <td class="text-right font-bold">{{ number_format($r->final_amount,0,',',' ') }} F</td>
                <td>
                    <span class="badge {{ match($r->status) {
                        'confirmed'   => 'badge-blue',
                        'checked_in'  => 'badge-blue',
                        'checked_out' => 'badge-green',
                        'cancelled'   => 'badge-red',
                        default       => 'badge-gray',
                    } }}">{{ $r->status }}</span>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

<div class="footer">
    {{ config('app.name') }} · Rapport généré automatiquement · {{ now()->format('d/m/Y à H:i') }}
</div>
</body>
</html>
