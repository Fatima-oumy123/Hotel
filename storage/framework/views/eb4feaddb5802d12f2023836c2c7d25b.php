<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mot de passe oublie - <?php echo e(config('hotel.name', 'Mon Hotel')); ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&family=Outfit:wght@500;600;700;800&display=swap" rel="stylesheet">
    <?php echo $__env->make('partials.vite-assets', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
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
        .notice{margin-top:16px;padding:12px 14px;border-radius:12px;font-size:14px}
        .notice.ok{background:#f0fdf4;border:1px solid #bbf7d0;color:#166534}
        .notice.err{background:#fef2f2;border:1px solid #fecaca;color:#991b1b}
        @media (max-width:900px){.shell{grid-template-columns:1fr}.intro h1{font-size:40px}}
    </style>
</head>
<body>
<div class="shell">
    <section class="intro">
        <div style="font-family:'Outfit',sans-serif;font-size:28px;font-weight:800"><?php echo e(strtoupper(config('hotel.name', 'Mon Hotel'))); ?></div>
        <h1>Recuperez votre acces sans casser l experience.</h1>
        <p>La page de recuperation suit maintenant la meme qualite visuelle que l accueil et les autres parcours du site.</p>
    </section>
    <section class="panel">
        <h2>Mot de passe oublie</h2>
        <?php if(session('success')): ?><div class="notice ok"><?php echo e(session('success')); ?></div><?php endif; ?>
        <?php if($errors->any()): ?><div class="notice err"><?php echo e($errors->first()); ?></div><?php endif; ?>
        <form action="<?php echo e(route('password.email')); ?>" method="POST">
            <?php echo csrf_field(); ?>
            <div class="field">
                <label>Adresse email</label>
                <input type="email" name="email" value="<?php echo e(old('email')); ?>" required placeholder="votre@email.com">
            </div>
            <button type="submit" class="btn">Envoyer le lien</button>
        </form>
        <div style="margin-top:16px"><a href="<?php echo e(route('login')); ?>" style="color:#8d4a0f;font-weight:700">Retour a la connexion</a></div>
    </section>
</div>
</body>
</html>
<?php /**PATH C:\Users\HP\Desktop\Mon_hotel (2)\Mon_hotel\resources\views/auth/forgot-password.blade.php ENDPATH**/ ?>