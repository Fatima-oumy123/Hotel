<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Confirmation de réservation</title>
<style>
    * { margin: 0; padding: 0; box-sizing: border-box; }
    body { font-family: 'Segoe UI', Arial, sans-serif; background: #f8fafc; color: #1e293b; }
    .wrapper { max-width: 600px; margin: 0 auto; padding: 20px; }
    .card { background: white; border-radius: 16px; overflow: hidden; box-shadow: 0 4px 24px rgba(0,0,0,0.08); }
    .header { background: linear-gradient(135deg, #f59e0b, #d97706); padding: 40px 32px; text-align: center; }
    .hotel-name { color: white; font-size: 24px; font-weight: bold; margin-bottom: 8px; }
    .booking-num { background: rgba(255,255,255,0.2); color: white; padding: 8px 20px;
                   border-radius: 100px; font-size: 18px; font-weight: bold; font-family: monospace;
                   display: inline-block; margin-top: 8px; }
    .body { padding: 32px; }
    h2 { font-size: 20px; color: #1e293b; margin-bottom: 6px; }
    .subtitle { color: #64748b; font-size: 14px; margin-bottom: 24px; }
    .info-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin: 20px 0; }
    .info-box { background: #f8fafc; border-radius: 10px; padding: 14px 16px; }
    .info-label { font-size: 11px; color: #94a3b8; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 4px; }
    .info-value { font-size: 15px; font-weight: 600; color: #1e293b; }
    .total-box { background: #fffbeb; border: 2px solid #fbbf24; border-radius: 12px;
                 padding: 16px 20px; display: flex; justify-content: space-between; align-items: center; margin: 20px 0; }
    .total-label { font-size: 14px; color: #92400e; font-weight: 500; }
    .total-amount { font-size: 24px; font-weight: bold; color: #b45309; }
    .note { background: #eff6ff; border-left: 4px solid #3b82f6; padding: 12px 16px;
            border-radius: 0 8px 8px 0; font-size: 13px; color: #1e40af; margin: 16px 0; }
    .btn { display: block; text-align: center; background: #f59e0b; color: white;
           text-decoration: none; padding: 14px 32px; border-radius: 10px;
           font-weight: bold; font-size: 15px; margin: 20px 0; }
    .footer { padding: 20px 32px; border-top: 1px solid #f1f5f9; text-align: center;
              font-size: 12px; color: #94a3b8; }
    .cancel-link { color: #ef4444; font-size: 13px; }
</style>
</head>
<body>
<div class="wrapper">
    <div class="card">
        <div class="header">
            <div class="hotel-name">🏨 {{ config('app.name') }}</div>
            <p style="color:rgba(255,255,255,0.85); font-size:14px">Confirmation de réservation</p>
            <div class="booking-num">{{ $reservation->booking_number }}</div>
        </div>
        <div class="body">
            <h2>Bonjour {{ $reservation->guest_first_name }} !</h2>
            <p class="subtitle">Votre réservation a été confirmée avec succès.</p>

            <div class="info-grid">
                <div class="info-box">
                    <div class="info-label">Chambre</div>
                    <div class="info-value">Ch.{{ $reservation->room->number }}</div>
                    <div style="font-size:12px;color:#64748b">{{ $reservation->room->roomType->name }}</div>
                </div>
                <div class="info-box">
                    <div class="info-label">Durée</div>
                    <div class="info-value">{{ $reservation->nights }} nuit(s)</div>
                </div>
                <div class="info-box">
                    <div class="info-label">Arrivée</div>
                    <div class="info-value">{{ $reservation->check_in->format('d/m/Y') }}</div>
                    <div style="font-size:12px;color:#64748b">à partir de 14h00</div>
                </div>
                <div class="info-box">
                    <div class="info-label">Départ</div>
                    <div class="info-value">{{ $reservation->check_out->format('d/m/Y') }}</div>
                    <div style="font-size:12px;color:#64748b">avant 12h00</div>
                </div>
            </div>

            <div class="total-box">
                <div class="total-label">Montant total TTC</div>
                <div class="total-amount">{{ number_format($reservation->final_amount,0,',',' ') }} FCFA</div>
            </div>

            <div class="note">
                📋 Présentez votre numéro de réservation <strong>{{ $reservation->booking_number }}</strong>
                ainsi que votre pièce d'identité à la réception lors de votre arrivée.
            </div>

            <p style="font-size:14px;color:#64748b;margin-bottom:8px;text-align:center">
                Pour annuler votre réservation (gratuit jusqu'à 48h avant) :
            </p>
            <a href="{{ route('guest.cancel') }}" class="cancel-link" style="display:block;text-align:center;margin-bottom:16px">
                Gérer ma réservation
            </a>
        </div>
        <div class="footer">
            <p>{{ config('app.name') }} · Dakar, Sénégal</p>
            <p style="margin-top:4px">Cet email a été envoyé automatiquement, ne pas répondre.</p>
        </div>
    </div>
</div>
</body>
</html>
