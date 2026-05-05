<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mode visiteur - {{ config('hotel.name', 'Mon Hotel') }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&family=Outfit:wght@500;600;700;800&display=swap" rel="stylesheet">
    @include('partials.vite-assets')
    <style>
        :root{
            --bg:#d6d7da;
            --shell:#f7f7f8;
            --card:#ffffff;
            --line:#d9dde5;
            --text:#171717;
            --muted:#6b7280;
            --brand:#a55b12;
            --brand-dark:#85450d;
            --soft:#f5eadf;
        }
        *{box-sizing:border-box}
        body{margin:0;font-family:'Manrope',sans-serif;background:var(--bg);color:var(--text)}
        a{text-decoration:none;color:inherit}
        .shell{min-height:100vh;display:flex}
        .sidebar{
            width:270px;
            background:var(--shell);
            border-right:1px solid var(--line);
            padding:24px 0;
            display:flex;
            flex-direction:column;
        }
        .brand{padding:0 24px 20px;border-bottom:1px solid var(--line)}
        .brand h1{margin:0;font-family:'Outfit',sans-serif;font-size:24px}
        .brand p{margin:10px 0 0;color:var(--muted);font-size:12px;letter-spacing:.16em;text-transform:uppercase;font-weight:800}
        .nav{padding:16px 0;display:grid}
        .nav a{
            padding:14px 24px;
            display:flex;
            gap:12px;
            align-items:center;
            font-weight:700;
            color:#273449;
        }
        .nav a.active{background:var(--soft);color:var(--brand-dark);border-left:4px solid var(--brand)}
        .cta{margin-top:auto;padding:16px 20px 0;border-top:1px solid var(--line)}
        .cta a{display:block;text-align:center;padding:13px 16px;border-radius:10px;background:var(--brand);color:#fff;font-weight:800}
        .main{flex:1;display:flex;flex-direction:column}
        .topbar{
            background:#fbfbfc;
            border-bottom:1px solid var(--line);
            padding:16px 22px;
            display:flex;
            align-items:center;
            justify-content:space-between;
            gap:16px;
        }
        .topbar h2{margin:0;font-family:'Outfit',sans-serif;font-size:36px}
        .topbar p{margin:4px 0 0;color:var(--muted);font-size:13px}
        .actions{display:flex;gap:10px;flex-wrap:wrap}
        .btn,.btn-outline{
            padding:11px 15px;
            border-radius:10px;
            font-size:13px;
            font-weight:800;
        }
        .btn{background:var(--brand);color:#fff}
        .btn-outline{background:#fff;border:1px solid var(--line);color:#374151}
        .content{padding:22px}
        .grid{display:grid;gap:16px}
        .stats{display:grid;grid-template-columns:repeat(4,1fr);gap:14px}
        .card{
            background:var(--card);
            border:1px solid var(--line);
            border-radius:12px;
            overflow:hidden;
        }
        .kpi{padding:20px}
        .kpi .label{font-size:12px;text-transform:uppercase;letter-spacing:.12em;color:var(--muted);font-weight:800}
        .kpi .value{margin-top:10px;font-size:42px;font-family:'Outfit',sans-serif}
        .kpi .meta{margin-top:8px;font-size:13px;color:#4b5563}
        .row{display:grid;grid-template-columns:1.5fr 1fr;gap:16px}
        .panel-head{padding:18px 20px;border-bottom:1px solid #eceef2}
        .panel-head h3{margin:0;font-family:'Outfit',sans-serif;font-size:30px}
        .panel-body{padding:18px 20px}
        .room-grid{display:grid;grid-template-columns:repeat(2,1fr);gap:14px}
        .room{
            border:1px solid #ece5dc;
            border-radius:14px;
            overflow:hidden;
            background:#fff;
        }
        .photo{height:180px;background-size:cover;background-position:center}
        .room-body{padding:14px}
        .room-body h4{margin:0;font-size:20px;font-family:'Outfit',sans-serif}
        .room-body p{margin:8px 0 0;color:#6b7280;font-size:13px;line-height:1.6}
        .price{margin-top:10px;font-weight:800;color:var(--brand-dark)}
        .service-list{display:grid;gap:12px}
        .service{
            padding:16px;
            border-radius:14px;
            border:1px solid #eceef2;
            background:#fafafa;
        }
        .service strong{display:block;font-size:15px}
        .service span{display:block;margin-top:6px;color:#6b7280;font-size:13px;line-height:1.6}
        .banner{
            min-height:280px;
            border-radius:16px;
            background:
                linear-gradient(180deg,rgba(0,0,0,.12),rgba(0,0,0,.48)),
                url('https://images.unsplash.com/photo-1505693416388-ac5ce068fe85?auto=format&fit=crop&w=1400&q=80') center/cover;
            display:flex;
            align-items:flex-end;
            padding:22px;
            color:#fff;
        }
        .banner h3{margin:0;font-family:'Outfit',sans-serif;font-size:34px}
        .banner p{margin:8px 0 0;max-width:420px;color:#f2e9df}
        @media (max-width:1100px){
            .stats,.row,.room-grid{grid-template-columns:1fr}
        }
        @media (max-width:860px){
            .shell{display:block}
            .sidebar{width:100%;padding-bottom:10px}
            .topbar{padding:16px}
            .topbar h2{font-size:28px}
            .content{padding:16px}
        }
    </style>
</head>
<body>
    <div class="shell">
        <aside class="sidebar">
            <div class="brand">
                <h1>{{ strtoupper(config('hotel.name', 'Mon Hotel')) }}</h1>
                <p>Mode visiteur</p>
            </div>
            <nav class="nav">
                <a href="{{ route('home') }}">⌂ <span>Accueil</span></a>
                <a href="#chambres" class="active">▤ <span>Chambres</span></a>
                <a href="{{ route('guest.step1') }}">◷ <span>Reserver</span></a>
            </nav>
            <div class="cta">
                <a href="{{ route('guest.step1') }}">Reserver maintenant</a>
            </div>
        </aside>

        <main class="main">
            <div class="topbar">
                <div>
                    <h2>Interface visiteur</h2>
                    <p>Decouvrez <strong>{{ config('hotel.name', 'Keur Ndiaye Lo') }}</strong> a Rufisque : confort, tarifs accessibles, zone calme et proche du Lac Rose.</p>
                </div>
                <div class="actions">
                    <a href="{{ route('home') }}" class="btn-outline">Retour accueil</a>
                    <a href="{{ route('login') }}" class="btn-outline">Connexion staff</a>
                    <a href="{{ route('guest.step1') }}" class="btn">Reserver</a>
                </div>
            </div>

            <div class="content">
                <div class="grid">
                    <div class="stats">
                        <div class="card kpi">
                            <div class="label">Chambres visibles</div>
                            <div class="value">{{ $featuredRooms->count() }}</div>
                            <div class="meta">Une selection pour couples et familles</div>
                        </div>
                        <div class="card kpi">
                            <div class="label">Prix abordables</div>
                            <div class="value">3</div>
                            <div class="meta">Un excellent confort a un tarif accessible</div>
                        </div>
                        <div class="card kpi">
                            <div class="label">Acces mobile</div>
                            <div class="value">100%</div>
                            <div class="meta">Reservation simple depuis votre telephone</div>
                        </div>
                        <div class="card kpi">
                            <div class="label">Ambiance</div>
                            <div class="value">FR</div>
                            <div class="meta">Chaleureuse, elegante, familiale</div>
                        </div>
                    </div>

                    <div class="row">
                        <section class="card" id="chambres">
                            <div class="panel-head">
                                <h3>Explorer les chambres et suites</h3>
                            </div>
                            <div class="panel-body">
                                <div class="room-grid">
                                    @forelse($featuredRooms as $index => $room)
                                        @php
                                            $images = [
                                                'https://images.unsplash.com/photo-1590490360182-c33d57733427?auto=format&fit=crop&w=900&q=80',
                                                'https://images.unsplash.com/photo-1618773928121-c32242e63f39?auto=format&fit=crop&w=900&q=80',
                                                'https://images.unsplash.com/photo-1505693416388-ac5ce068fe85?auto=format&fit=crop&w=900&q=80',
                                                'https://images.unsplash.com/photo-1578683010236-d716f9a3f461?auto=format&fit=crop&w=900&q=80',
                                            ];
                                        @endphp
                                        <article class="room">
                                            <div class="photo" style="background-image:url('{{ $images[$index % count($images)] }}')"></div>
                                            <div class="room-body">
                                                <h4>{{ $room->roomType->name ?? 'Chambre' }}</h4>
                                                <p>Chambre {{ $room->number }} · Un cocon confortable, ideal pour se reposer en toute tranquillite.</p>
                                                <div class="price">A partir de {{ number_format((float) ($room->base_price ?? 0), 0, ',', ' ') }} {{ config('hotel.currency') }}</div>
                                            </div>
                                        </article>
                                    @empty
                                        <div class="room">
                                            <div class="room-body">
                                                <h4>Aucune chambre publiee</h4>
                                                <p>Les chambres apparaitront ici des qu elles seront disponibles.</p>
                                            </div>
                                        </div>
                                    @endforelse
                                </div>
                            </div>
                        </section>

                        <section class="grid">
                            <div class="card">
                                <div class="panel-head">
                                    <h3>Services disponibles</h3>
                                </div>
                                <div class="panel-body">
                                    <div class="service-list">
                                        <div class="service">
                                            <strong>Reservation simple</strong>
                                            <span>Choisissez vos dates et confirmez en quelques etapes, sans complications.</span>
                                        </div>
                                        <div class="service">
                                            <strong>Services sur place</strong>
                                            <span>Profitez d une prise en charge fluide et d attentions utiles pendant le sejour.</span>
                                        </div>
                                        <div class="service">
                                            <strong>Paiement flexible</strong>
                                            <span>Selon disponibilite, choisissez l option la plus simple pour vous.</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="banner">
                                <div>
                                    <h3>Visitez, choisissez, reservez</h3>
                                    <p>A Rufisque, profitez du confort, de prix abordables et d un environnement agreable.</p>
                                </div>
                            </div>
                        </section>
                    </div>
                </div>
            </div>
        </main>
    </div>
</body>
</html>
