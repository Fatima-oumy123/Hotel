@extends('layouts.app')
@section('title', 'Clients')
@section('page_title', 'Clients')
@section('page_subtitle', 'Fidelisation et suivi des profils voyageurs')

@section('content')
<style>
    .customers-page{display:grid;gap:18px}
    .customers-layout{
        display:grid;
        grid-template-columns:minmax(0,2fr) minmax(300px,.9fr);
        gap:16px;
    }
    .customer-form{
        display:grid;
        grid-template-columns:repeat(4,minmax(0,1fr));
        gap:12px;
    }
    .customer-form input{
        width:100%;
    }
    .customer-table th{background:#faf1ea}
    .customer-table td{font-size:15px}
    .profile-card{
        padding:22px;
    }
    .profile-photo{
        width:110px;
        height:110px;
        object-fit:cover;
        border:2px solid #ead9cb;
        display:block;
        margin:0 auto;
    }
    .profile-stats{
        display:grid;
        grid-template-columns:1fr 1fr;
        gap:10px;
        margin-top:18px;
    }
    .profile-box{
        border:1px solid #ead9cb;
        background:#fbf5ef;
        text-align:center;
        padding:14px;
    }
    @media (max-width:1180px){
        .customers-layout,.customer-form{grid-template-columns:1fr}
    }
</style>

<div class="customers-page">
    <div class="screen-header">
        <div class="screen-heading">
            <h2>Gestion des Clients</h2>
            <p>Annuaire client, points de fidelite et vision rapide des profils a forte valeur.</p>
        </div>
    </div>

    <div class="metric-grid">
        <article class="metric-tile">
            <div class="metric-title">Clients VIP</div>
            <div class="metric-figure">{{ $stats['vip'] }}</div>
            <div class="metric-caption">Profils premium</div>
        </article>
        <article class="metric-tile">
            <div class="metric-title">Clients fideles</div>
            <div class="metric-figure">{{ $stats['repeat'] }}</div>
            <div class="metric-caption success">Plus d un sejour</div>
        </article>
        <article class="metric-tile">
            <div class="metric-title">Profils fidelises</div>
            <div class="metric-figure">{{ $stats['fidelized'] }}</div>
            <div class="metric-caption">Programme actif</div>
        </article>
        <article class="metric-tile">
            <div class="metric-title">Satisfaction cible</div>
            <div class="metric-figure">4.8/5</div>
            <div class="metric-caption">Experience client</div>
        </article>
    </div>

    <div class="customers-layout">
        <section class="lx-panel">
            <div class="lx-panel-head">
                <h3>Annuaire Clients</h3>
                <span class="link-accent">{{ $customers->total() }} client(s)</span>
            </div>

            <div class="lx-panel-body" style="border-bottom:1px solid #ead9cb">
                <form class="customer-form" method="POST" action="{{ route('customers.store') }}">
                    @csrf
                    <input class="form-input" name="first_name" placeholder="Prenom" required>
                    <input class="form-input" name="last_name" placeholder="Nom" required>
                    <input class="form-input" name="phone" placeholder="Telephone" required>
                    <input class="form-input" name="email" placeholder="Email">
                    <input class="form-input" name="national_id" placeholder="Numero de piece">
                    <input class="form-input" name="loyalty_points" placeholder="Points fidelite" value="0" type="number" min="0">
                    <input class="form-input" name="address" placeholder="Adresse">
                    <button class="btn-primary" type="submit">Ajouter le client</button>
                </form>
            </div>

            <div style="overflow:auto">
                <table class="table customer-table">
                    <thead>
                        <tr>
                            <th>Client</th>
                            <th>Fidelite</th>
                            <th>Historique</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($customers as $customer)
                            <tr>
                                <td>
                                    <strong>{{ $customer->full_name }}</strong><br>
                                    <small style="color:#8a8179">{{ $customer->email ?: $customer->phone }}</small>
                                </td>
                                <td>
                                    @if($customer->is_vip)
                                        <span class="soft-tag">VIP</span>
                                    @elseif($customer->loyalty_points > 0)
                                        <span class="soft-tag blue">Fidele</span>
                                    @else
                                        <span class="soft-tag gray">Standard</span>
                                    @endif
                                </td>
                                <td>{{ $customer->reservations_count }} sejour(s)</td>
                                <td>
                                    <form method="POST" action="{{ route('customers.destroy', $customer) }}" onsubmit="return confirm('Supprimer ce client ?')">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn-secondary" type="submit">Supprimer</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" style="text-align:center">Aucun client enregistre.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div style="padding:16px 18px">{{ $customers->withQueryString()->links() }}</div>
        </section>

        <aside class="lx-panel profile-card">
            @php $featured = $customers->first(); @endphp
            @if($featured)
                <img class="profile-photo" src="https://images.unsplash.com/photo-1560250097-0b93528c311a?auto=format&fit=crop&w=320&q=80" alt="client">
                <h3 style="margin:18px 0 6px;font-family:'Outfit',sans-serif;font-size:32px">{{ $featured->full_name }}</h3>
                <div style="color:#7b746d">Fiche rapide du client mis en avant</div>
                <div class="profile-stats">
                    <div class="profile-box">
                        <div style="font-size:11px;color:#7b746d;text-transform:uppercase">Sejours</div>
                        <strong>{{ $featured->reservations_count }}</strong>
                    </div>
                    <div class="profile-box">
                        <div style="font-size:11px;color:#7b746d;text-transform:uppercase">Points</div>
                        <strong>{{ $featured->loyalty_points }}</strong>
                    </div>
                </div>
                <div style="margin-top:16px;display:grid;gap:8px;color:#564f49;font-size:13px">
                    <div>Telephone : {{ $featured->phone }}</div>
                    <div>Email : {{ $featured->email ?: 'Non renseigne' }}</div>
                    <div>Adresse : {{ $featured->address ?: 'Non renseignee' }}</div>
                </div>
                <button class="btn-primary" type="button" style="margin-top:18px;width:100%;justify-content:center">Envoyer un message</button>
            @else
                <div style="color:#7b746d">Aucun profil client a afficher.</div>
            @endif
        </aside>
    </div>
</div>
@endsection
