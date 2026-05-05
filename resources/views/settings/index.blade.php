@extends('layouts.app')
@section('title', 'Parametres')
@section('page_title', 'Parametres')
@section('page_subtitle', 'Configuration hotel, fiscalite et acces')

@section('content')
<style>
    .settings-page{display:grid;gap:18px}
    .settings-layout{
        display:grid;
        grid-template-columns:minmax(0,2fr) minmax(280px,.9fr);
        gap:16px;
    }
    .settings-form{
        display:grid;
        grid-template-columns:repeat(2,minmax(0,1fr));
        gap:14px;
    }
    .settings-form label{
        display:block;
        margin-bottom:8px;
        font-size:12px;
        text-transform:uppercase;
        letter-spacing:.08em;
        color:#5d544b;
        font-weight:800;
    }
    .tax-box{
        border:1px solid #e7c7ae;
        background:#f8ecdf;
        padding:14px;
        margin-bottom:12px;
    }
    .roles{
        display:grid;
        grid-template-columns:repeat(3,minmax(0,1fr));
    }
    .role{
        padding:18px 20px;
        border-right:1px solid #ead9cb;
    }
    .role:last-child{border-right:none}
    .mobile-panel{
        background:linear-gradient(180deg,#182131,#20160f);
        color:#fff;
        border:1px solid #334155;
        padding:18px 20px;
        display:grid;
        grid-template-columns:1.4fr 1fr;
        gap:16px;
    }
    @media (max-width:1180px){
        .settings-layout,.mobile-panel,.roles,.settings-form{grid-template-columns:1fr}
        .role{border-right:none;border-bottom:1px solid #ead9cb}
        .role:last-child{border-bottom:none}
    }
</style>

<div class="settings-page">
    <div class="screen-header">
        <div class="screen-heading">
            <h2>Parametres du Systeme</h2>
            <p>Configuration generale de l hotel, regles financieres et gestion des acces selon les roles.</p>
        </div>
    </div>

    <div class="settings-layout">
        <section class="lx-panel">
            <div class="lx-panel-head">
                <h3>Configuration generale</h3>
                <span class="link-accent">Hotel & exploitation</span>
            </div>
            <div class="lx-panel-body">
                <form class="settings-form" method="POST" action="{{ route('settings.update') }}">
                    @csrf
                    @method('PUT')
                    <div><label>Nom de l hotel</label><input class="form-input" name="hotel_name" value="{{ $settings['hotel_name'] }}" required></div>
                    <div><label>Telephone</label><input class="form-input" name="hotel_phone" value="{{ $settings['hotel_phone'] }}" required></div>
                    <div><label>Email</label><input class="form-input" type="email" name="hotel_email" value="{{ $settings['hotel_email'] }}" required></div>
                    <div><label>Adresse</label><input class="form-input" name="hotel_address" value="{{ $settings['hotel_address'] }}" required></div>
                    <div><label>Devise</label><input class="form-input" name="currency" value="{{ $settings['currency'] }}" required></div>
                    <div>
                        <label>Langue par defaut</label>
                        <select name="default_lang" class="form-input" required>
                            <option value="fr" @selected($settings['default_lang'] === 'fr')>Francais</option>
                            <option value="en" @selected($settings['default_lang'] === 'en')>English</option>
                        </select>
                    </div>
                    <div><label>TVA (%)</label><input class="form-input" type="number" step="0.01" min="0" name="tax_rate" value="{{ $settings['tax_rate'] }}" required></div>
                    <div><label>Taxe de sejour / nuit</label><input class="form-input" type="number" step="0.01" min="0" name="stay_tax_per_night" value="{{ $settings['stay_tax_per_night'] }}" required></div>
                    <div>
                        <label>Mobile Money actif</label>
                        <select name="mobile_money_enabled" class="form-input">
                            <option value="1" @selected($settings['mobile_money_enabled'] == '1')>Oui</option>
                            <option value="0" @selected($settings['mobile_money_enabled'] == '0')>Non</option>
                        </select>
                    </div>
                    <div><label>Message hors ligne</label><input class="form-input" name="offline_sync_hint" value="{{ $settings['offline_sync_hint'] }}"></div>
                    <div style="grid-column:1/-1;display:flex;justify-content:flex-end;gap:10px;flex-wrap:wrap">
                        <button type="button" class="btn-secondary">Annuler</button>
                        <button class="btn-primary" type="submit">Enregistrer les parametres</button>
                    </div>
                </form>
            </div>
        </section>

        <aside class="lx-panel">
            <div class="lx-panel-head">
                <h3>Regles fiscales</h3>
            </div>
            <div class="lx-panel-body">
                <div class="tax-box">
                    <strong>TVA / taxe de vente</strong>
                    <div style="float:right;color:#9b4e11;font-weight:800">{{ $settings['tax_rate'] }}%</div>
                    <p style="margin:6px 0 0;color:#6b7280">Appliquee aux reservations et a la restauration.</p>
                </div>
                <div class="tax-box">
                    <strong>Taxe de sejour</strong>
                    <div style="float:right;color:#9b4e11;font-weight:800">{{ number_format((float) $settings['stay_tax_per_night'], 2) }}/nuit</div>
                    <p style="margin:6px 0 0;color:#6b7280">Montant additionnel par nuit occupee.</p>
                </div>
                <div class="tax-box">
                    <strong>Service client</strong>
                    <div style="float:right;color:#9b4e11;font-weight:800">10%</div>
                    <p style="margin:6px 0 0;color:#6b7280">Frais facultatifs pour certaines prestations.</p>
                </div>
                <button class="btn-secondary" style="width:100%;justify-content:center" type="button">Mettre a jour le calendrier fiscal</button>
            </div>
        </aside>
    </div>

    <section class="lx-panel">
        <div class="lx-panel-head">
            <h3>Roles et permissions</h3>
            <span class="link-accent">Acces dynamiques</span>
        </div>
        <div class="roles">
            <article class="role">
                <h4 style="margin:0;font-family:'Outfit',sans-serif;font-size:30px">Administrateur</h4>
                <div style="margin-top:8px;color:#64748b">Controle complet du systeme</div>
                <ul style="margin:14px 0 0;padding-left:18px;line-height:1.8">
                    <li>Reservations, chambres, clients</li>
                    <li>Stocks, personnel, comptabilite</li>
                    <li>Rapports et parametres</li>
                </ul>
            </article>
            <article class="role">
                <h4 style="margin:0;font-family:'Outfit',sans-serif;font-size:30px">Receptionniste</h4>
                <div style="margin-top:8px;color:#64748b">Gestion du front office</div>
                <ul style="margin:14px 0 0;padding-left:18px;line-height:1.8">
                    <li>Reservations et check-in/out</li>
                    <li>Clients et chambres</li>
                    <li>Restaurant lie au client</li>
                </ul>
            </article>
            <article class="role">
                <h4 style="margin:0;font-family:'Outfit',sans-serif;font-size:30px">Visiteur</h4>
                <div style="margin-top:8px;color:#64748b">Acces public limite</div>
                <ul style="margin:14px 0 0;padding-left:18px;line-height:1.8">
                    <li>Consultation chambres et services</li>
                    <li>Reservation en ligne</li>
                    <li>Presentation de l hotel</li>
                </ul>
            </article>
        </div>
    </section>

    <section class="mobile-panel">
        <div>
            <h3 style="margin:0 0 8px;font-family:'Outfit',sans-serif;font-size:34px">Paiement Mobile Money integre</h3>
            <p style="margin:0;color:#d6dce6;line-height:1.7">Le systeme est prepare pour les paiements locaux type Orange Money, MTN, Wave ou Airtel, avec verification rapide pour les depots et sorties clients.</p>
        </div>
        <div style="border:1px solid #475569;padding:16px;background:rgba(255,255,255,.04)">
            <div style="display:flex;justify-content:space-between;gap:10px;align-items:center">
                <span class="badge badge-success">Actif</span>
                <strong>Passerelle locale</strong>
            </div>
            <div style="margin-top:14px;display:flex;gap:8px;flex-wrap:wrap">
                <button class="btn-primary" type="button">Configurer l API</button>
                <button class="btn-secondary" type="button">Voir les journaux</button>
            </div>
        </div>
    </section>
</div>
@endsection
