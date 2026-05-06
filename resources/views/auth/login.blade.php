<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion - {{ config('hotel.name', 'Mon Hotel') }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&family=Outfit:wght@500;600;700;800&display=swap" rel="stylesheet">
    @include('partials.vite-assets')
    <style>
        :root{
            --bg:#141313;
            --card:#ffffff;
            --muted:#6b7280;
            --text:#171717;
            --brand:#b56416;
            --brand-dark:#8b470d;
        }
        *{box-sizing:border-box}
        body{
            margin:0;
            min-height:100vh;
            font-family:'Manrope',sans-serif;
            color:#fff;
            background:
                linear-gradient(90deg,rgba(18,12,9,.88),rgba(18,12,9,.64)),
                url('https://images.unsplash.com/photo-1551882547-ff40c63fe5fa?auto=format&fit=crop&w=1800&q=80') center/cover;
            display:flex;
            align-items:center;
            justify-content:center;
            padding:24px;
        }
        .shell{
            width:min(1080px,100%);
            display:grid;
            grid-template-columns:1.05fr .95fr;
            background:rgba(12,12,14,.28);
            border:1px solid rgba(255,255,255,.1);
            backdrop-filter:blur(10px);
            border-radius:28px;
            overflow:hidden;
            box-shadow:0 24px 70px rgba(0,0,0,.28);
        }
        .intro{padding:42px 38px 34px}
        .intro .brand{font-family:'Outfit',sans-serif;font-size:28px;font-weight:800}
        .intro h1{margin:56px 0 0;font-family:'Outfit',sans-serif;font-size:58px;line-height:.98}
        .intro p{margin:16px 0 0;max-width:460px;color:#eadfd4;line-height:1.7}
        .intro-list{display:grid;gap:12px;margin-top:28px}
        .intro-list div{
            width:fit-content;
            padding:10px 14px;
            border-radius:999px;
            border:1px solid rgba(255,255,255,.16);
            background:rgba(255,255,255,.06);
            color:#f4e9df;
            font-size:13px;
            font-weight:700;
        }

        .panel{
            background:#f8f6f3;
            color:var(--text);
            padding:38px 34px;
            display:flex;
            flex-direction:column;
            justify-content:center;
        }
        .panel h2{margin:0;font-family:'Outfit',sans-serif;font-size:42px}
        .panel p{margin:10px 0 0;color:var(--muted)}
        .notice{
            margin-top:18px;
            padding:12px 14px;
            border-radius:12px;
            font-size:14px;
            border:1px solid #e5e7eb;
            background:#fff;
        }
        .notice.err{border-color:#fecaca;background:#fef2f2;color:#991b1b}
        .notice.ok{border-color:#bbf7d0;background:#f0fdf4;color:#166534}
        form{margin-top:22px}
        .field{margin-top:16px}
        .field label{display:block;margin-bottom:8px;font-size:13px;font-weight:800;color:#374151}
        .input{
            width:100%;
            border:1px solid #d8d8d8;
            border-radius:14px;
            padding:14px 16px;
            background:#fff;
        }
        .input:focus{outline:none;border-color:#fdba74}
        .pass-wrap{position:relative}
        .pass-btn{
            position:absolute;
            top:50%;
            right:12px;
            transform:translateY(-50%);
            border:none;
            background:transparent;
            cursor:pointer;
            color:#6b7280;
        }
        .row{
            margin-top:14px;
            display:flex;
            justify-content:space-between;
            gap:14px;
            align-items:center;
            flex-wrap:wrap;
        }
        .link{color:var(--brand-dark);font-weight:700}
        .submit{
            margin-top:18px;
            width:100%;
            border:none;
            border-radius:14px;
            background:var(--brand);
            color:#fff;
            padding:15px 16px;
            font-size:15px;
            font-weight:800;
            cursor:pointer;
        }
        .submit:hover{background:var(--brand-dark)}
        .back{
            margin-top:18px;
            display:flex;
            gap:10px;
            flex-wrap:wrap;
        }
        .back a{
            padding:11px 14px;
            border-radius:12px;
            background:#fff;
            border:1px solid #e5e7eb;
            font-size:13px;
            font-weight:700;
            color:#374151;
        }
        .demo{
            margin-top:18px;
            padding:16px;
            border-radius:16px;
            background:#fff;
            border:1px solid #ebe5de;
            font-size:13px;
            color:#4b5563;
            line-height:1.7;
        }
        @media (max-width:900px){
            .shell{grid-template-columns:1fr}
            .intro h1{font-size:42px}
            .panel h2{font-size:34px}
        }
    </style>
</head>
<body>
    <div class="shell">
        <section class="intro">
            <div class="brand">{{ strtoupper(config('hotel.name', 'Mon Hotel')) }}</div>
            <h1>Connectez votre equipe a une gestion hoteliere claire et rapide.</h1>
            <p>Acces securise pour l administrateur, la reception et les autres profils autorises de l hotel.</p>
            <div class="intro-list">
                <div>Reservations, check-in et check-out</div>
                <div>Chambres, restaurant, stocks et personnel</div>
                <div>Paiements locaux et rapports en francais</div>
            </div>
        </section>

        <section class="panel">
            <h2>Connexion</h2>
            <p>Entrez vos identifiants pour acceder a l interface interne.</p>

            @if(session('success'))
                <div class="notice ok">{{ session('success') }}</div>
            @endif
            @if($errors->any())
                <div class="notice err">{{ $errors->first() }}</div>
            @endif

            <form action="{{ route('login') }}" method="POST">
                @csrf
                <div class="field">
                    <label for="email">Adresse email</label>
                    <input class="input" type="email" id="email" name="email" value="{{ old('email') }}" placeholder="admin@hotel.com" required autofocus>
                </div>

                <div class="field">
                    <label for="password">Mot de passe</label>
                    <div class="pass-wrap">
                        <input class="input" type="password" id="password" name="password" placeholder="••••••••" required>
                        <button class="pass-btn" type="button" id="togglePass">Afficher</button>
                    </div>
                </div>

                <div class="row">
                    <a class="link" href="{{ route('password.request') }}">Mot de passe oublie ?</a>
                    <a class="link" href="{{ route('visitor.portal') }}">Entrer comme visiteur</a>
                </div>

                <button class="submit" type="submit">Se connecter</button>
            </form>

            <div class="back">
                <a href="{{ route('home') }}">Retour a l accueil</a>
                <a href="{{ route('guest.step1') }}">Reservation publique</a>
            </div>

            <div class="demo">
                Comptes de demonstration si vos seeders sont charges :
                <br>Admin : <strong>admin@hotel.com</strong> / <strong>password123</strong>
                <br>Reception : <strong>reception@hotel.com</strong> / <strong>password123</strong>
            </div>
        </section>
    </div>

    <script>
        document.getElementById('togglePass')?.addEventListener('click', function () {
            const input = document.getElementById('password');
            if (!input) return;
            input.type = input.type === 'password' ? 'text' : 'password';
            this.textContent = input.type === 'password' ? 'Afficher' : 'Masquer';
        });
    </script>
</body>
</html>
