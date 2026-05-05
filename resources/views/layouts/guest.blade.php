<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Reservation') - {{ config('hotel.name', 'Mon Hotel') }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&family=Outfit:wght@500;600;700;800&display=swap" rel="stylesheet">
    @include('partials.vite-assets')
    <style>
        :root{
            --bg:#d7d8db;
            --shell:#f4f5f7;
            --card:#ffffff;
            --line:#dde1e8;
            --text:#171717;
            --muted:#667085;
            --brand:#b56518;
            --brand-dark:#8d4a0f;
            --brand-soft:#f7ebde;
            --navy:#202632;
            --ok:#16a34a;
            --danger:#dc2626;
        }
        *{box-sizing:border-box}
        html{scroll-behavior:smooth}
        body{
            margin:0;
            min-height:100vh;
            font-family:'Manrope',sans-serif;
            color:var(--text);
            background:
                radial-gradient(circle at top right, rgba(255,255,255,.65), transparent 28%),
                linear-gradient(180deg, #d7d8db 0%, #eef1f4 100%);
        }
        a{text-decoration:none;color:inherit}
        button,input,select,textarea{font:inherit}
        .container{width:min(1220px,calc(100% - 28px));margin:0 auto}
        .hero-shell{
            position:relative;
            overflow:hidden;
            background:
                linear-gradient(110deg, rgba(28,33,42,.92) 0%, rgba(28,33,42,.78) 40%, rgba(181,101,24,.32) 100%),
                url('https://images.unsplash.com/photo-1566073771259-6a8506099945?auto=format&fit=crop&w=1800&q=80') center/cover;
            color:#fff;
        }
        .hero-shell::after{
            content:"";
            position:absolute;
            inset:auto -120px -120px auto;
            width:320px;height:320px;border-radius:999px;
            background:rgba(255,255,255,.08);filter:blur(4px);
        }
        .guest-nav{
            position:sticky;top:0;z-index:40;
            background:rgba(25,30,38,.82);
            backdrop-filter:blur(12px);
            border-bottom:1px solid rgba(255,255,255,.08);
        }
        .guest-nav-inner{
            display:flex;align-items:center;justify-content:space-between;gap:16px;
            padding:16px 0;
        }
        .brand{
            font-family:'Outfit',sans-serif;
            font-size:24px;font-weight:800;letter-spacing:.02em;color:#fff;
        }
        .nav-links{display:flex;gap:18px;flex-wrap:wrap;color:#e8ebef;font-size:13px;font-weight:700}
        .nav-links a:hover{color:#ffd7b0}
        .nav-actions{display:flex;gap:10px;flex-wrap:wrap}
        .btn-primary,.btn-secondary,.btn-danger{
            display:inline-flex;align-items:center;justify-content:center;
            padding:12px 16px;border-radius:999px;font-size:13px;font-weight:800;cursor:pointer;
            border:none;
        }
        .btn-primary{background:var(--brand);color:#fff}
        .btn-primary:hover{background:var(--brand-dark)}
        .btn-secondary{background:#fff;border:1px solid #d4dae3;color:#344054}
        .btn-secondary:hover{border-color:#f4b47a;color:var(--brand-dark)}
        .btn-danger{background:var(--danger);color:#fff}
        .hero-content{
            position:relative;z-index:1;
            padding:64px 0 80px;
        }
        .hero-grid{
            display:grid;grid-template-columns:1.15fr .85fr;gap:24px;align-items:end;
        }
        .eyebrow{
            display:inline-flex;align-items:center;gap:8px;
            padding:8px 12px;border-radius:999px;
            background:rgba(255,255,255,.08);
            border:1px solid rgba(255,255,255,.12);
            font-size:12px;font-weight:700;color:#f5e8db;
        }
        .hero-title{
            margin:18px 0 0;font-family:'Outfit',sans-serif;
            font-size:58px;line-height:.96;letter-spacing:-.03em;
        }
        .hero-copy{
            margin:16px 0 0;max-width:650px;color:#e7ded5;font-size:16px;line-height:1.7;
        }
        .hero-card{
            background:rgba(255,255,255,.08);
            border:1px solid rgba(255,255,255,.12);
            border-radius:24px;padding:22px;backdrop-filter:blur(8px);
        }
        .hero-card h3{margin:0;font-family:'Outfit',sans-serif;font-size:28px}
        .hero-card p{margin:8px 0 0;color:#e7ded5;font-size:14px;line-height:1.7}
        .hero-mini{display:grid;grid-template-columns:repeat(3,1fr);gap:10px;margin-top:16px}
        .hero-mini .box{
            padding:14px;border-radius:16px;background:rgba(255,255,255,.08);text-align:center;
            border:1px solid rgba(255,255,255,.08);
        }
        .hero-mini strong{display:block;font-family:'Outfit',sans-serif;font-size:24px}
        .hero-mini span{display:block;margin-top:6px;font-size:12px;color:#e7ded5}
        .content-wrap{padding:26px 0 46px}
        .page-grid{display:grid;gap:16px}
        .panel{
            background:var(--card);
            border:1px solid #d8dde6;
            border-radius:20px;
            box-shadow:0 16px 44px rgba(15,23,42,.06);
            overflow:hidden;
        }
        .panel-head{
            padding:20px 22px;border-bottom:1px solid #eceef2;
            display:flex;justify-content:space-between;align-items:center;gap:12px;
        }
        .panel-head h2,.panel-head h3{margin:0;font-family:'Outfit',sans-serif;font-size:28px}
        .panel-body{padding:22px}
        .stats-grid{display:grid;grid-template-columns:repeat(4,minmax(0,1fr));gap:14px}
        .stat-card{
            background:#fff;border:1px solid #d8dde6;border-radius:18px;padding:18px;
        }
        .stat-card .label{font-size:11px;text-transform:uppercase;letter-spacing:.12em;color:var(--muted);font-weight:800}
        .stat-card .value{margin-top:10px;font-family:'Outfit',sans-serif;font-size:40px;line-height:1}
        .stat-card .meta{margin-top:8px;color:var(--muted);font-size:13px}
        .form-grid{display:grid;grid-template-columns:repeat(4,minmax(0,1fr));gap:12px}
        .field{display:grid;gap:8px}
        .field label{font-size:12px;font-weight:800;text-transform:uppercase;letter-spacing:.08em;color:var(--muted)}
        .field input,.field select,.field textarea{
            width:100%;padding:14px 15px;border:1px solid #d5dbe5;border-radius:14px;background:#f8fafc;color:var(--text);
        }
        .field input:focus,.field select:focus,.field textarea:focus{
            outline:none;border-color:#f1b57f;background:#fff;
        }
        .alert{
            padding:14px 16px;border-radius:14px;font-size:14px;border:1px solid #e5e7eb;background:#fff;
        }
        .alert.error{border-color:#fecaca;background:#fef2f2;color:#991b1b}
        .alert.success{border-color:#bbf7d0;background:#f0fdf4;color:#166534}
        .alert.info{border-color:#cbd5e1;background:#f8fafc;color:#334155}
        .stepper{display:flex;align-items:center;justify-content:center;gap:10px;flex-wrap:wrap}
        .step{
            display:flex;align-items:center;gap:10px;color:#64748b;font-size:13px;font-weight:700;
        }
        .step .bubble{
            width:34px;height:34px;border-radius:999px;border:1px solid #d4dae3;background:#fff;
            display:flex;align-items:center;justify-content:center;
        }
        .step.active{color:var(--brand-dark)}
        .step.active .bubble{background:var(--brand);border-color:var(--brand);color:#fff}
        .step.done .bubble{background:var(--ok);border-color:var(--ok);color:#fff}
        .step-line{width:42px;height:1px;background:#cfd5df}
        .footer-note{padding:24px 0 32px;text-align:center;color:#6b7280;font-size:12px}
        @media (max-width:1100px){
            .hero-grid{grid-template-columns:1fr}
            .stats-grid{grid-template-columns:repeat(2,minmax(0,1fr))}
            .form-grid{grid-template-columns:repeat(2,minmax(0,1fr))}
        }
        @media (max-width:760px){
            .nav-links{display:none}
            .hero-title{font-size:42px}
            .hero-mini,.stats-grid,.form-grid{grid-template-columns:1fr}
            .guest-nav-inner{padding:14px 0}
        }
    </style>
    @stack('styles')
</head>
<body>
    <nav class="guest-nav">
        <div class="container guest-nav-inner">
            <a href="{{ route('home') }}" class="brand">{{ strtoupper(config('hotel.name', 'Mon Hotel')) }}</a>
            <div class="nav-links">
                <a href="{{ route('home') }}">Accueil</a>
                <a href="{{ route('guest.step1') }}">Reservation</a>
                <a href="{{ route('guest.cancel') }}">Gerer ma reservation</a>
                <a href="{{ route('login') }}">Connexion staff</a>
            </div>
            <div class="nav-actions">
                <a href="{{ route('visitor.portal') }}" class="btn-secondary">Visiter</a>
                <a href="{{ route('guest.step1') }}" class="btn-primary">Reserver</a>
            </div>
        </div>
    </nav>

    <section class="hero-shell">
        <div class="container hero-content">
            <div class="hero-grid">
                <div>
                    <div class="eyebrow">@yield('hero_eyebrow', 'Rufisque · Reservation en ligne')</div>
                    <h1 class="hero-title">@yield('hero_title', 'Votre sejour familial et luxueux commence ici.')</h1>
                    <p class="hero-copy">@yield('hero_copy', 'Consultez les disponibilites, choisissez votre chambre et confirmez en toute serenite : confort, prix abordables, zone calme et proche du Lac Rose.')</p>
                </div>
                <div class="hero-card">
                    <h3>@yield('card_title', 'Parcours simplifie')</h3>
                    <p>@yield('card_copy', 'Des etapes claires, un total transparent et une reservation simple, depuis ordinateur ou smartphone.')</p>
                    <div class="hero-mini">
                        @yield('hero_stats')
                    </div>
                </div>
            </div>
        </div>
    </section>

    <main class="content-wrap">
        <div class="container page-grid">
            @yield('content')
        </div>
        <div class="container footer-note">
            {{ config('hotel.name', 'Keur Ndiaye Lo') }} · Rufisque · zone calme · proche du Lac Rose · confort · prix abordables
        </div>
    </main>
    @stack('scripts')
</body>
</html>
