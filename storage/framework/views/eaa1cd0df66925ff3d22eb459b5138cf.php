<!DOCTYPE html>
<html lang="fr" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <title><?php echo $__env->yieldContent('title', 'Tableau de bord'); ?> - <?php echo e(config('app.name')); ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&family=Outfit:wght@500;600;700;800&display=swap" rel="stylesheet">
    <?php echo $__env->make('partials.vite-assets', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    <?php echo $__env->yieldPushContent('styles'); ?>
    <style>
        :root{
            --bg:#d9d9d9;
            --sidebar:#f4f4f4;
            --surface:#ffffff;
            --surface-soft:#f8f8f8;
            --line:#ddd7d1;
            --line-strong:#d8b08d;
            --text:#171310;
            --muted:#6d6a67;
            --brand:#b05f09;
            --brand-dark:#8e4b05;
            --brand-soft:#f7ebe0;
            --success:#16a34a;
            --warning:#f59e0b;
            --danger:#dc2626;
            --navy:#131c2b;
        }

        *{box-sizing:border-box}
        body{
            margin:0;
            min-height:100vh;
            font-family:'Manrope',sans-serif;
            background:var(--bg);
            color:var(--text);
        }
        a{text-decoration:none;color:inherit}
        button,input,select,textarea{font:inherit}

        .shell{min-height:100vh;display:flex}
        .overlay{position:fixed;inset:0;background:rgba(15,23,42,.45);z-index:60}

        .sidebar{
            position:fixed;
            inset:0 auto 0 0;
            width:266px;
            background:var(--sidebar);
            border-right:1px solid var(--line);
            display:flex;
            flex-direction:column;
            z-index:70;
            transform:translateX(-100%);
            transition:transform .24s ease;
        }
        .sidebar.open{transform:translateX(0)}
        .sidebar-header{
            padding:22px 24px 18px;
            border-bottom:1px solid var(--line);
            background:#fafafa;
        }
        .brand-mark{
            font-family:'Outfit',sans-serif;
            font-size:20px;
            font-weight:800;
            letter-spacing:.01em;
        }
        .brand-sub{
            margin-top:14px;
            color:#8b8b8b;
            font-size:11px;
            letter-spacing:.18em;
            text-transform:uppercase;
            line-height:1.8;
            font-weight:700;
        }

        .nav{flex:1;overflow-y:auto;padding:14px 0}
        .nav-section{
            padding:16px 24px 8px;
            color:#9a948d;
            font-size:11px;
            font-weight:800;
            letter-spacing:.16em;
            text-transform:uppercase;
        }
        .nav-link{
            position:relative;
            display:flex;
            align-items:center;
            gap:14px;
            padding:14px 24px;
            font-size:15px;
            font-weight:800;
            color:#344154;
            transition:background .2s ease,color .2s ease;
        }
        .nav-link:hover{background:#efefef}
        .nav-link.active{
            background:#fbf7f3;
            color:var(--brand);
        }
        .nav-link.active::before{
            content:"";
            position:absolute;
            left:0;
            top:0;
            bottom:0;
            width:4px;
            background:#ff6b00;
        }
        .nav-icon{
            width:20px;
            display:flex;
            justify-content:center;
            font-size:18px;
            color:inherit;
        }

        .sidebar-cta{
            padding:16px;
            border-top:1px solid var(--line);
            background:#fafafa;
        }
        .sidebar-cta .btn-primary{
            width:100%;
            justify-content:center;
            padding:14px 16px;
        }
        .sidebar-footer{
            padding:18px 20px;
            border-top:1px solid var(--line);
            background:#fafafa;
        }
        .user-card{display:flex;align-items:center;gap:12px}
        .avatar{
            width:42px;
            height:42px;
            border-radius:999px;
            background:#ff8608;
            color:#fff;
            display:flex;
            align-items:center;
            justify-content:center;
            font-size:14px;
            font-weight:800;
        }
        .user-name{font-size:14px;font-weight:800}
        .user-role{font-size:12px;color:var(--muted)}

        .main{flex:1;min-width:0;display:flex;flex-direction:column}
        .topbar{
            position:sticky;
            top:0;
            z-index:50;
            height:68px;
            background:#fafafa;
            border-bottom:1px solid var(--line);
            padding:0 24px;
            display:flex;
            align-items:center;
            justify-content:space-between;
            gap:16px;
        }
        .topbar-left{display:flex;align-items:center;gap:14px;min-width:0}
        .menu-btn,.icon-btn{
            width:40px;
            height:40px;
            border-radius:10px;
            border:1px solid var(--line);
            background:#fff;
            color:#4b5563;
            cursor:pointer;
            display:inline-flex;
            align-items:center;
            justify-content:center;
        }
        .page-title{
            margin:0;
            font-family:'Outfit',sans-serif;
            font-size:15px;
            font-weight:700;
            line-height:1.1;
            color:#2e2b28;
        }
        .page-subtitle{
            margin-top:4px;
            color:#87817a;
            font-size:12px;
            white-space:nowrap;
            overflow:hidden;
            text-overflow:ellipsis;
            max-width:420px;
        }

        .topbar-actions{display:flex;align-items:center;gap:10px;margin-left:auto}
        .search-wrap{
            position:relative;
            width:390px;
            max-width:36vw;
        }
        .search{
            width:100%;
            padding:13px 16px 13px 42px;
            border:1px solid var(--line-strong);
            border-radius:8px;
            background:#fff;
            color:#111827;
        }
        .search:focus{
            outline:none;
            border-color:#f39b54;
            box-shadow:0 0 0 3px rgba(255,128,0,.08);
        }
        .search-icon{
            position:absolute;
            left:14px;
            top:50%;
            transform:translateY(-50%);
            color:#8a8f98;
            font-size:16px;
            pointer-events:none;
        }

        .btn-primary,.btn-secondary{
            border-radius:6px;
            padding:12px 16px;
            font-size:13px;
            font-weight:800;
            letter-spacing:.08em;
            text-transform:uppercase;
            cursor:pointer;
            display:inline-flex;
            align-items:center;
            gap:10px;
        }
        .btn-primary{
            border:1px solid var(--brand-dark);
            background:var(--brand);
            color:#fff;
        }
        .btn-primary:hover{background:var(--brand-dark)}
        .btn-secondary{
            border:1px solid #8f8a86;
            background:#5f6064;
            color:#fff;
        }
        .btn-secondary:hover{background:#4e4f53}

        .content{flex:1;padding:22px 24px 28px}
        .flash-stack{display:grid;gap:10px;margin-bottom:18px}
        .flash{
            padding:12px 14px;
            border-radius:8px;
            border:1px solid #e5e7eb;
            background:#fff;
            font-size:14px;
        }
        .flash.success{border-color:#bbf7d0;background:#f0fdf4;color:#166534}
        .flash.error{border-color:#fecaca;background:#fef2f2;color:#991b1b}

        .panel,
        .card,
        .detail-card{
            background:var(--surface);
            border:1px solid var(--line-strong);
            border-radius:0;
            box-shadow:none;
        }
        .panel-head{
            padding:18px 20px;
            border-bottom:1px solid #ebdfd3;
            display:flex;
            align-items:center;
            justify-content:space-between;
            gap:12px;
        }
        .panel-body{padding:18px 20px}

        .badge{
            display:inline-flex;
            align-items:center;
            padding:5px 10px;
            border-radius:999px;
            font-size:11px;
            font-weight:800;
            border:1px solid currentColor;
            background:#fff;
        }
        .badge-success{color:#16a34a}
        .badge-info{color:#2563eb}
        .badge-warning{color:#ea580c}
        .badge-danger{color:#b91c1c}
        .badge-secondary{color:#475569}

        .table{width:100%;border-collapse:collapse}
        .table th{
            padding:14px 16px;
            text-align:left;
            border-bottom:1px solid #eadbce;
            color:#5e554b;
            font-size:11px;
            letter-spacing:.12em;
            text-transform:uppercase;
            background:#faf1ea;
        }
        .table td{
            padding:16px;
            border-bottom:1px solid #f0e7df;
            font-size:14px;
            vertical-align:top;
        }
        .table tr:hover td{background:#fcfaf8}

        .form-input{
            width:100%;
            border:1px solid #decfc3;
            border-radius:8px;
            padding:12px 14px;
            background:#fffdfb;
            color:#111827;
            transition:border-color .2s ease, box-shadow .2s ease;
        }
        .form-input:focus{
            outline:none;
            border-color:#eea15e;
            box-shadow:0 0 0 3px rgba(245,95,10,.08);
            background:#fff;
        }

        .page-shell{display:grid;gap:18px}
        .screen-page{display:grid;gap:18px}
        .screen-header{
            display:flex;
            align-items:flex-start;
            justify-content:space-between;
            gap:14px;
            flex-wrap:wrap;
        }
        .screen-heading h2{
            margin:0;
            font-family:'Outfit',sans-serif;
            font-size:34px;
            line-height:1.05;
        }
        .screen-heading p{
            margin:8px 0 0;
            color:#6c635b;
            font-size:14px;
        }
        .screen-actions{
            display:flex;
            gap:10px;
            flex-wrap:wrap;
            align-items:center;
        }
        .metric-grid{
            display:grid;
            grid-template-columns:repeat(4,minmax(0,1fr));
            gap:16px;
        }
        .metric-tile{
            background:#fff;
            border:1px solid var(--line-strong);
            padding:22px 26px;
            min-height:156px;
        }
        .metric-title{
            color:#554c42;
            font-size:12px;
            font-weight:800;
            letter-spacing:.08em;
            text-transform:uppercase;
        }
        .metric-figure{
            margin-top:14px;
            font-family:'Outfit',sans-serif;
            font-size:28px;
            line-height:1.1;
        }
        .metric-caption{
            margin-top:12px;
            color:#6b7280;
            font-size:14px;
        }
        .metric-caption.success{color:#16a34a}
        .metric-caption.danger{color:#dc2626}
        .lx-grid{
            display:grid;
            gap:16px;
        }
        .lx-grid-main{
            display:grid;
            grid-template-columns:minmax(0,2fr) minmax(280px,.95fr);
            gap:16px;
            align-items:start;
        }
        .lx-grid-two{
            display:grid;
            grid-template-columns:1fr 1fr;
            gap:16px;
        }
        .lx-panel{
            background:#fff;
            border:1px solid var(--line-strong);
        }
        .lx-panel-head{
            padding:18px 20px;
            border-bottom:1px solid #ead9cb;
            display:flex;
            align-items:center;
            justify-content:space-between;
            gap:12px;
        }
        .lx-panel-head h3{
            margin:0;
            font-family:'Outfit',sans-serif;
            font-size:24px;
        }
        .lx-panel-body{padding:18px 20px}
        .link-accent{
            color:var(--brand);
            font-size:13px;
            font-weight:800;
            letter-spacing:.12em;
            text-transform:uppercase;
        }
        .kpi-badges{
            display:flex;
            gap:8px;
            flex-wrap:wrap;
            margin-top:12px;
        }
        .soft-tag{
            display:inline-flex;
            align-items:center;
            padding:4px 8px;
            background:#fff3e8;
            color:#d75f00;
            font-size:11px;
            font-weight:800;
            text-transform:uppercase;
        }
        .soft-tag.blue{
            background:#ecf5ff;
            color:#2563eb;
        }
        .soft-tag.gray{
            background:#eef1f5;
            color:#475569;
        }
        .soft-tag.green{
            background:#ecfdf3;
            color:#16a34a;
        }
        .soft-tag.dark{
            background:#1f2937;
            color:#fff;
        }
        .page-hero{
            position:relative;
            overflow:hidden;
            padding:28px;
            border:1px solid var(--line-strong);
            background:
                linear-gradient(135deg, rgba(21,28,39,.96) 0%, rgba(21,28,39,.88) 45%, rgba(176,95,9,.68) 100%),
                radial-gradient(circle at top right, rgba(255,255,255,.18), transparent 32%);
            color:#fff;
        }
        .page-hero::after{
            content:"";
            position:absolute;
            right:-80px;
            bottom:-80px;
            width:220px;
            height:220px;
            border-radius:999px;
            background:rgba(255,255,255,.06);
        }
        .page-hero > *{position:relative;z-index:1}
        .page-hero h2{
            margin:0;
            font-family:'Outfit',sans-serif;
            font-size:34px;
            line-height:1.02;
        }
        .page-hero p{
            margin:10px 0 0;
            color:#e7dacf;
            max-width:760px;
            line-height:1.7;
            font-size:14px;
        }
        .hero-pills{display:flex;gap:10px;flex-wrap:wrap;margin-top:18px}
        .hero-pill{
            padding:8px 12px;
            border-radius:999px;
            background:rgba(255,255,255,.09);
            border:1px solid rgba(255,255,255,.14);
            color:#f9efe7;
            font-size:12px;
            font-weight:700;
        }
        .detail-grid{display:grid;grid-template-columns:repeat(3,minmax(0,1fr));gap:16px}
        .detail-card{padding:18px}
        .detail-card h3{
            margin:0 0 14px;
            font-family:'Outfit',sans-serif;
            font-size:23px;
        }
        .section-stack{display:grid;gap:16px}

        .app-footer{
            padding:18px 24px 20px;
            border-top:1px solid var(--line);
            background:#efefef;
            display:flex;
            align-items:center;
            justify-content:space-between;
            gap:12px;
            flex-wrap:wrap;
            color:#463f39;
            font-size:12px;
        }
        .footer-badges{
            display:flex;
            gap:10px;
            flex-wrap:wrap;
        }
        .footer-chip{
            padding:8px 12px;
            border:1px solid var(--line-strong);
            background:#f8efe7;
            font-size:11px;
            letter-spacing:.14em;
            text-transform:uppercase;
        }

        @media (min-width:1024px){
            .sidebar{transform:translateX(0)}
            .main{margin-left:266px}
            .menu-btn{display:none}
        }
        @media (max-width:1023px){
            .topbar{padding:0 16px}
            .content{padding:16px}
            .app-footer{padding:16px}
        }
        @media (max-width:980px){
            .search-wrap{display:none}
            .detail-grid{grid-template-columns:1fr}
            .page-hero h2{font-size:28px}
            .metric-grid,
            .lx-grid-main,
            .lx-grid-two{grid-template-columns:1fr}
        }
        @media (max-width:640px){
            .topbar-actions .btn-secondary{display:none}
            .page-subtitle{display:none}
            .screen-heading h2{font-size:28px}
        }
    </style>
</head>
<body x-data="{ sidebarOpen: false }">
<?php
    $user = auth()->user();
    $roleName = $user?->getRoleNames()->first() ?? 'Utilisateur';
    $isReceptionist = $user?->hasRole('receptionist') ?? false;
    $isHr = $user?->hasRole('hr') ?? false;
    $isAdminLike = ($user?->hasRole('manager') ?? false) || ($user?->hasRole('admin') ?? false);

    $mainMenu = [];
    $adminMenu = [];
    $supportMenu = [];

    if ($isAdminLike) {
        $mainMenu = [
            ['label' => 'Dashboard', 'route' => 'dashboard', 'patterns' => ['dashboard'], 'icon' => '◫'],
            ['label' => 'Chambres', 'route' => 'rooms.index', 'patterns' => ['rooms.*'], 'icon' => '▤'],
            ['label' => 'Reservations', 'route' => 'reservations.index', 'patterns' => ['reservations.*'], 'icon' => '◷'],
            ['label' => 'Clients', 'route' => 'customers.index', 'patterns' => ['customers.*'], 'icon' => '◎'],
            ['label' => 'Restaurant', 'route' => 'restaurant.index', 'patterns' => ['restaurant.*'], 'icon' => '◧'],
            ['label' => 'Stocks', 'route' => 'inventory.index', 'patterns' => ['inventory.*'], 'icon' => '▥'],
            ['label' => 'Personnel', 'route' => 'employees.index', 'patterns' => ['employees.*', 'employee-schedule.*'], 'icon' => '◉'],
            ['label' => 'Finance', 'route' => 'reports.index', 'patterns' => ['reports.*', 'payments.*', 'invoices.*', 'expenses.*'], 'icon' => '◈'],
            ['label' => 'Parametres', 'route' => 'settings.index', 'patterns' => ['settings.*', 'users.*', 'audit-logs.*'], 'icon' => '⚙'],
        ];

        $adminMenu = [
            ['label' => 'Paiements', 'route' => 'payments.index', 'patterns' => ['payments.*'], 'icon' => '◇'],
            ['label' => 'Types de chambres', 'route' => 'room-types.index', 'patterns' => ['room-types.*'], 'icon' => '◭'],
            ['label' => 'Factures', 'route' => 'invoices.index', 'patterns' => ['invoices.*'], 'icon' => '◰'],
            ['label' => 'Maintenance', 'route' => 'maintenance.index', 'patterns' => ['maintenance.*'], 'icon' => '◌'],
            ['label' => 'Utilisateurs', 'route' => 'users.index', 'patterns' => ['users.*'], 'icon' => '◍'],
            ['label' => 'Journal d audit', 'route' => 'audit-logs.index', 'patterns' => ['audit-logs.*'], 'icon' => '◬'],
        ];
    } elseif ($isReceptionist) {
        $mainMenu = [
            ['label' => 'Dashboard', 'route' => 'dashboard', 'patterns' => ['dashboard'], 'icon' => '◫'],
            ['label' => 'Chambres', 'route' => 'rooms.index', 'patterns' => ['rooms.*'], 'icon' => '▤'],
            ['label' => 'Reservations', 'route' => 'reservations.index', 'patterns' => ['reservations.*'], 'icon' => '◷'],
            ['label' => 'Clients', 'route' => 'customers.index', 'patterns' => ['customers.*'], 'icon' => '◎'],
            ['label' => 'Restaurant', 'route' => 'restaurant.index', 'patterns' => ['restaurant.*'], 'icon' => '◧'],
        ];

        $supportMenu = [
            ['label' => 'Calendrier', 'route' => 'reservations.calendar', 'patterns' => ['reservations.calendar'], 'icon' => '◳'],
        ];
    } elseif ($isHr) {
        $mainMenu = [
            ['label' => 'Dashboard', 'route' => 'dashboard', 'patterns' => ['dashboard'], 'icon' => '◫'],
            ['label' => 'Personnel', 'route' => 'employees.index', 'patterns' => ['employees.*'], 'icon' => '◉'],
            ['label' => 'Planning', 'route' => 'employee-schedule.index', 'patterns' => ['employee-schedule.*'], 'icon' => '◳'],
        ];
    } else {
        $mainMenu = [
            ['label' => 'Dashboard', 'route' => 'dashboard', 'patterns' => ['dashboard'], 'icon' => '◫'],
        ];
    }
?>
<div class="shell">
    <template x-if="sidebarOpen">
        <div class="overlay" @click="sidebarOpen = false"></div>
    </template>

    <aside class="sidebar" :class="sidebarOpen ? 'open' : ''">
        <div class="sidebar-header">
            <div class="brand-mark"><?php echo e(strtoupper(config('hotel.name', 'Mon Hotel'))); ?></div>
            <div class="brand-sub">Luxecontrol Admin<br>Hotel Management</div>
        </div>

        <nav class="nav">
            <?php $__currentLoopData = $mainMenu; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <a href="<?php echo e(route($item['route'])); ?>" class="nav-link <?php echo e(request()->routeIs(...$item['patterns']) ? 'active' : ''); ?>">
                    <span class="nav-icon"><?php echo e($item['icon']); ?></span>
                    <span><?php echo e($item['label']); ?></span>
                </a>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

            <?php if(count($supportMenu)): ?>
                <div class="nav-section">Suivi</div>
                <?php $__currentLoopData = $supportMenu; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <a href="<?php echo e(route($item['route'])); ?>" class="nav-link <?php echo e(request()->routeIs(...$item['patterns']) ? 'active' : ''); ?>">
                        <span class="nav-icon"><?php echo e($item['icon']); ?></span>
                        <span><?php echo e($item['label']); ?></span>
                    </a>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            <?php endif; ?>

            <?php if(count($adminMenu)): ?>
                <div class="nav-section">Administration</div>
                <?php $__currentLoopData = $adminMenu; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <a href="<?php echo e(route($item['route'])); ?>" class="nav-link <?php echo e(request()->routeIs(...$item['patterns']) ? 'active' : ''); ?>">
                        <span class="nav-icon"><?php echo e($item['icon']); ?></span>
                        <span><?php echo e($item['label']); ?></span>
                    </a>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            <?php endif; ?>
        </nav>

        <?php if($isAdminLike || $isReceptionist): ?>
            <div class="sidebar-cta">
                <a class="btn-primary" href="<?php echo e(route('reservations.create')); ?>">Nouvelle reservation</a>
            </div>
        <?php endif; ?>

        <div class="sidebar-footer">
            <div class="user-card">
                <div class="avatar"><?php echo e(strtoupper(substr($user->name ?? 'U', 0, 1))); ?></div>
                <div>
                    <div class="user-name"><?php echo e($user->name ?? 'Utilisateur'); ?></div>
                    <div class="user-role"><?php echo e(ucfirst($roleName)); ?></div>
                </div>
            </div>
        </div>
    </aside>

    <div class="main">
        <header class="topbar">
            <div class="topbar-left">
                <button class="menu-btn" @click="sidebarOpen = !sidebarOpen">☰</button>
                <div>
                    <h1 class="page-title"><?php echo $__env->yieldContent('page_title', 'Tableau de bord'); ?></h1>
                    <div class="page-subtitle"><?php echo $__env->yieldContent('page_subtitle', now()->isoFormat('dddd D MMMM YYYY')); ?></div>
                </div>
            </div>

            <div class="topbar-actions">
                <div class="search-wrap">
                    <span class="search-icon">⌕</span>
                    <input class="search" type="text" placeholder="Rechercher une facture, un client, une chambre...">
                </div>
                <button class="icon-btn" type="button" title="Notifications">🔔</button>
                <button class="icon-btn" type="button" title="Aide">?</button>
                <form action="<?php echo e(route('logout')); ?>" method="POST">
                    <?php echo csrf_field(); ?>
                    <button class="icon-btn" type="submit" title="Deconnexion" style="background:#ff8608;color:#fff;border-color:#ff8608">◉</button>
                </form>
            </div>
        </header>

        <div class="content">
            <div class="flash-stack">
                <?php if(session('success')): ?>
                    <div class="flash success"><?php echo e(session('success')); ?></div>
                <?php endif; ?>
                <?php if(session('error')): ?>
                    <div class="flash error"><?php echo e(session('error')); ?></div>
                <?php endif; ?>
                <?php if($errors->any()): ?>
                    <div class="flash error"><?php echo e($errors->first()); ?></div>
                <?php endif; ?>
            </div>

            <?php echo $__env->yieldContent('content'); ?>
        </div>

        <footer class="app-footer">
            <div>© <?php echo e(date('Y')); ?> <?php echo e(config('hotel.name', 'Mon Hotel')); ?> Hotel Management System. Tous droits reserves.</div>
            <div class="footer-badges">
                <div class="footer-chip">Serveur central: <?php echo e(strtoupper(config('hotel.city', 'Abidjan'))); ?></div>
                <div class="footer-chip">Version 2.4.0-stable</div>
            </div>
        </footer>
    </div>
</div>
<?php echo $__env->yieldPushContent('scripts'); ?>
</body>
</html>
<?php /**PATH C:\Users\HP\Desktop\Mon_hotel (2)\Mon_hotel\resources\views/layouts/app.blade.php ENDPATH**/ ?>