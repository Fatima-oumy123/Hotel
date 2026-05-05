<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nouveau mot de passe - {{ config('hotel.name', 'Mon Hotel') }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&family=Outfit:wght@500;600;700;800&display=swap" rel="stylesheet">
    @include('partials.vite-assets')
    <style>
        body{margin:0;min-height:100vh;font-family:'Manrope',sans-serif;background:linear-gradient(120deg,#1b212c,#212631 48%,#b56518 160%);display:flex;align-items:center;justify-content:center;padding:24px;color:#fff}
        .shell{width:min(980px,100%);display:grid;grid-template-columns:1fr 1fr;border-radius:28px;overflow:hidden;background:rgba(255,255,255,.08);border:1px solid rgba(255,255,255,.12);backdrop-filter:blur(12px)}
        .intro{padding:36px}
        .intro h1{margin:58px 0 0;font-family:'Outfit',sans-serif;font-size:52px;line-height:.96}
        .intro p{margin:14px 0 0;color:#e7ded5;line-height:1.7}
        .panel{background:#f8f6f3;color:#171717;padding:36px}
        .panel h2{margin:0;font-family:'Outfit',sans-serif;font-size:38px}
        .field{margin-top:16px}
        .field label{display:block;margin-bottom:8px;font-size:12px;font-weight:800;text-transform:uppercase;color:#64748b}
        .field input{width:100%;padding:14px 15px;border:1px solid #d5dbe5;border-radius:14px;background:#fff}
        .field input:focus{outline:none;border-color:#f1b57f}
        .btn{margin-top:18px;width:100%;padding:15px;border:none;border-radius:14px;background:#b56518;color:#fff;font-weight:800}
        .notice{margin-top:16px;padding:12px 14px;border-radius:12px;background:#fef2f2;border:1px solid #fecaca;color:#991b1b;font-size:14px}
        @media (max-width:900px){.shell{grid-template-columns:1fr}.intro h1{font-size:40px}}
    </style>
</head>
<body>
<div class="shell">
    <section class="intro">
        <div style="font-family:'Outfit',sans-serif;font-size:28px;font-weight:800">{{ strtoupper(config('hotel.name', 'Mon Hotel')) }}</div>
        <h1>Definissez un nouveau mot de passe en toute simplicite.</h1>
        <p>Le parcours d authentification a lui aussi ete aligne sur la direction visuelle generale du site, avec une presentation plus moderne et moins rigide.</p>
    </section>
    <section class="panel">
        <h2>Nouveau mot de passe</h2>
        @if($errors->any())<div class="notice">{{ $errors->first() }}</div>@endif
        <form action="{{ route('password.update') }}" method="POST">
            @csrf
            <input type="hidden" name="token" value="{{ $token }}">
            <div class="field">
                <label>Adresse email</label>
                <input type="email" name="email" value="{{ old('email') }}" required placeholder="votre@email.com">
            </div>
            <div class="field">
                <label>Nouveau mot de passe</label>
                <input type="password" name="password" required placeholder="••••••••">
            </div>
            <div class="field">
                <label>Confirmation</label>
                <input type="password" name="password_confirmation" required placeholder="••••••••">
            </div>
            <button type="submit" class="btn">Reinitialiser le mot de passe</button>
        </form>
        <div style="margin-top:16px"><a href="{{ route('login') }}" style="color:#8d4a0f;font-weight:700">Retour a la connexion</a></div>
    </section>
</div>
</body>
</html>
