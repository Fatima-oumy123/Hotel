<?php $__env->startSection('title', 'Tableau de bord'); ?>
<?php $__env->startSection('page_title', 'Console interne'); ?>
<?php $__env->startSection('page_subtitle', 'Pilotage quotidien de l hotel'); ?>

<?php
    $user = auth()->user();
    $isReceptionist = $user?->hasRole('receptionist') ?? false;
    $isAdminLike = ($user?->hasRole('manager') ?? false) || ($user?->hasRole('admin') ?? false);
    $isHr = $user?->hasRole('hr') ?? false;
    $weeklyChart = $revenueChart->take(7)->reverse()->values();
    $chartMax = max(1, $weeklyChart->max('revenue'));
    $roomsLegend = [
        'available' => ['label' => 'Libre', 'class' => 'green'],
        'occupied' => ['label' => 'Occupe', 'class' => 'orange'],
        'maintenance' => ['label' => 'Menage', 'class' => 'dark'],
    ];
?>

<?php $__env->startSection('content'); ?>
<style>
    .dashboard-page{display:grid;gap:18px}
    .dashboard-head{
        display:flex;
        align-items:flex-start;
        justify-content:space-between;
        gap:14px;
        flex-wrap:wrap;
    }
    .dashboard-title h2{
        margin:0;
        font-family:'Outfit',sans-serif;
        font-size:42px;
        line-height:1.05;
    }
    .dashboard-title p{
        margin:10px 0 0;
        color:#6c625a;
        font-size:15px;
    }
    .dashboard-tools{
        display:flex;
        gap:10px;
        flex-wrap:wrap;
    }
    .dash-kpis{
        display:grid;
        grid-template-columns:repeat(4,minmax(0,1fr));
        gap:18px;
    }
    .dash-kpi{
        background:#fff;
        border:1px solid #ddbba0;
        padding:26px 28px;
        min-height:168px;
    }
    .dash-kpi .label{
        color:#5e554b;
        font-size:12px;
        font-weight:800;
        letter-spacing:.08em;
        text-transform:uppercase;
    }
    .dash-kpi .value{
        margin-top:14px;
        font-family:'Outfit',sans-serif;
        font-size:35px;
        line-height:1.05;
    }
    .dash-kpi .meta{
        margin-top:14px;
        font-size:15px;
        color:#66615b;
    }
    .dash-kpi .meta.success{color:#16a34a}
    .dash-kpi .meta.danger{color:#dc2626}
    .dash-main{
        display:grid;
        grid-template-columns:minmax(0,2fr) minmax(320px,.95fr);
        gap:18px;
        align-items:start;
    }
    .dash-panel{
        background:#fff;
        border:1px solid #ddbba0;
    }
    .dash-panel-head{
        padding:22px 28px;
        border-bottom:1px solid #ead9cb;
        display:flex;
        align-items:center;
        justify-content:space-between;
        gap:12px;
    }
    .dash-panel-head h3{
        margin:0;
        font-family:'Outfit',sans-serif;
        font-size:27px;
    }
    .dash-panel-body{padding:24px 28px}
    .revenue-wrap{
        height:330px;
        display:flex;
        align-items:flex-end;
        gap:10px;
    }
    .revenue-col{
        flex:1;
        position:relative;
        height:100%;
        display:flex;
        align-items:flex-end;
        justify-content:center;
        background:linear-gradient(to top, #f4f5f7 0%, #ffffff 75%);
    }
    .revenue-bar{
        width:88%;
        background:#eceff3;
        position:relative;
        display:flex;
        align-items:flex-end;
        justify-content:center;
    }
    .revenue-bar.active{background:#a55a00}
    .revenue-day{
        position:absolute;
        top:-28px;
        left:50%;
        transform:translateX(-50%);
        color:#9ca3af;
        font-size:12px;
        font-weight:800;
        text-transform:uppercase;
    }
    .revenue-bar.active .revenue-day{color:#a55a00}
    .chart-legend{
        display:flex;
        gap:18px;
        flex-wrap:wrap;
        color:#433d38;
        font-size:14px;
    }
    .chart-legend span{
        display:inline-flex;
        align-items:center;
        gap:8px;
    }
    .chart-legend i{
        width:13px;
        height:13px;
        border-radius:999px;
        display:inline-block;
    }
    .ussd-box h3{
        margin:0;
        font-family:'Outfit',sans-serif;
        font-size:25px;
    }
    .ussd-box .form-label{
        display:block;
        margin-bottom:8px;
        color:#5d544b;
        font-size:12px;
        font-weight:800;
        letter-spacing:.08em;
        text-transform:uppercase;
    }
    .operator-list{
        display:grid;
        grid-template-columns:repeat(3,minmax(0,1fr));
        gap:8px;
        margin:12px 0 18px;
    }
    .operator-card{
        border:1px solid #d9c3af;
        min-height:68px;
        display:grid;
        place-items:center;
        background:#fff;
        font-size:12px;
        color:#57514b;
        gap:6px;
        padding:8px;
    }
    .operator-card.active{border-color:#9d4c00;box-shadow:inset 0 0 0 1px #9d4c00}
    .operator-mark{
        width:28px;
        height:28px;
        background:#ff7a00;
    }
    .operator-mark.wave{background:#d7d7d7}
    .operator-mark.mtn{background:#efefef}
    .phone-input{
        display:grid;
        grid-template-columns:64px 1fr;
    }
    .phone-prefix{
        display:flex;
        align-items:center;
        justify-content:center;
        border:1px solid #decfc3;
        border-right:none;
        background:#fceee3;
        border-radius:8px 0 0 8px;
        font-weight:700;
    }
    .phone-input .form-input{border-radius:0 8px 8px 0}
    .dash-table th{background:#faf1ea}
    .dash-table td{font-size:15px}
    .guest-line strong{display:block;font-size:15px}
    .guest-line small{display:block;margin-top:4px;color:#8b8b8b}
    .room-line{
        display:flex;
        align-items:center;
        gap:8px;
    }
    .row-menu{
        color:#777;
        font-size:22px;
        font-weight:700;
        text-align:right;
    }
    .foot-grid{
        display:grid;
        grid-template-columns:1.1fr 1fr;
        gap:18px;
    }
    .room-blocks{
        display:grid;
        grid-template-columns:repeat(5,minmax(0,1fr));
        gap:10px;
        margin-top:4px;
    }
    .room-block{
        min-height:74px;
        padding:10px 6px;
        border:2px solid #dadada;
        text-align:center;
        font-weight:800;
        display:grid;
        align-content:center;
        gap:4px;
        background:#fff;
    }
    .room-block.green{border-color:#22c55e;background:#effcf4;color:#15803d}
    .room-block.orange{border-color:#b05f09;background:#fff7ed;color:#b45309}
    .room-block.dark{border-color:#1f2937;background:#1f2937;color:#fff}
    .room-block small{
        font-size:10px;
        letter-spacing:.06em;
        text-transform:uppercase;
    }
    .room-legend{
        display:flex;
        gap:18px;
        flex-wrap:wrap;
        margin-top:18px;
        color:#6b6b6b;
        font-size:13px;
    }
    .room-legend span{
        display:inline-flex;
        align-items:center;
        gap:8px;
    }
    .room-legend i{
        width:10px;
        height:10px;
        border-radius:999px;
        display:inline-block;
    }
    .stock-card-list{display:grid;gap:18px}
    .stock-item{
        display:grid;
        grid-template-columns:1fr auto;
        gap:12px;
        align-items:center;
    }
    .stock-item strong{display:block;font-size:15px}
    .stock-item small{display:block;margin-top:6px;color:#8a8179}
    .stock-stepper{
        display:grid;
        grid-template-columns:44px 70px 44px;
        border:1px solid #decfc3;
        min-height:40px;
    }
    .stock-stepper span,
    .stock-stepper button{
        display:flex;
        align-items:center;
        justify-content:center;
        background:#fff;
        border:none;
        font:inherit;
    }
    .stock-stepper span{
        border-left:1px solid #decfc3;
        border-right:1px solid #decfc3;
        font-size:30px;
        line-height:1;
        color:#3b342e;
    }
    .stock-stepper.alert{
        border-color:#ef4444;
        color:#dc2626;
    }
    @media (max-width:1180px){
        .dash-kpis{grid-template-columns:repeat(2,minmax(0,1fr))}
        .dash-main,.foot-grid{grid-template-columns:1fr}
    }
    @media (max-width:760px){
        .dash-kpis,.room-blocks{grid-template-columns:1fr}
        .dashboard-title h2{font-size:34px}
    }
</style>

<div class="dashboard-page">
    <div class="dashboard-head">
        <div class="dashboard-title">
            <h2>Tableau de Bord</h2>
            <p>Vue d'ensemble de l'activite du <?php echo e(now()->translatedFormat('d M Y')); ?></p>
        </div>
        <div class="dashboard-tools">
            <?php if($isAdminLike): ?>
                <a href="<?php echo e(route('reports.export', ['month' => now()->month, 'year' => now()->year, 'type' => 'csv'])); ?>" class="btn-secondary">Exporter</a>
            <?php endif; ?>
            <a href="<?php echo e(route('reservations.index')); ?>" class="btn-secondary" style="background:#5d5f63;border-color:#5d5f63">Filtrer</a>
        </div>
    </div>

    <div class="dash-kpis">
        <article class="dash-kpi">
            <div class="label">Occupation actuelle</div>
            <div class="value"><?php echo e(number_format($stats['occupancy_rate'], 0, ',', ' ')); ?>%</div>
            <div class="meta success">↗ <?php echo e(max(1, round($stats['occupancy_rate'] / 16, 1))); ?>% vs hier</div>
        </article>

        <article class="dash-kpi">
            <div class="label">Revenus du jour (<?php echo e(config('hotel.currency', 'XOF')); ?>)</div>
            <div class="value"><?php echo e(number_format($stats['revenue_today'], 0, ',', ' ')); ?></div>
            <div class="kpi-badges">
                <span class="soft-tag">Orange Money</span>
                <span class="soft-tag blue">Wave</span>
            </div>
        </article>

        <article class="dash-kpi">
            <div class="label"><?php echo e($isHr ? 'Equipe active' : 'Arrivees prevues'); ?></div>
            <div class="value"><?php echo e($isHr ? ($stats['total_rooms'] ?? 0) : $stats['arrivals_today']); ?></div>
            <div class="meta">⦿ <?php echo e($stats['pending_reservations']); ?> deja enregistrees</div>
        </article>

        <article class="dash-kpi">
            <div class="label"><?php echo e($isHr ? 'Taches RH' : 'Taches en attente'); ?></div>
            <div class="value" style="color:#a55a00"><?php echo e($stats['pending_reservations']); ?></div>
            <div class="meta danger">! <?php echo e(max(1, $stats['pending_reservations'])); ?> urgentes</div>
        </article>
    </div>

    <div class="dash-main">
        <section class="dash-panel">
            <div class="dash-panel-head">
                <h3><?php echo e($isReceptionist ? 'Flux d activite hebdomadaire' : 'Flux de Revenus Hebdomadaire'); ?></h3>
                <div class="chart-legend">
                    <span><i style="background:#a55a00"></i> Chambres</span>
                    <span><i style="background:#7c7f85"></i> Restaurant</span>
                </div>
            </div>
            <div class="dash-panel-body">
                <div class="revenue-wrap">
                    <?php $__currentLoopData = $weeklyChart; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $entry): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php
                            $height = max(34, round(($entry['revenue'] / $chartMax) * 100));
                            $active = $loop->iteration === 5;
                        ?>
                        <div class="revenue-col">
                            <div class="revenue-bar <?php echo e($active ? 'active' : ''); ?>" style="height:<?php echo e($height); ?>%">
                                <span class="revenue-day"><?php echo e(\Carbon\Carbon::createFromFormat('d/m', $entry['date'])->locale('fr')->isoFormat('ddd')); ?></span>
                            </div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            </div>
        </section>

        <section class="dash-panel ussd-box">
            <div class="dash-panel-body">
                <h3>Paiement Mobile Money</h3>
                <div style="margin-top:22px">
                    <label class="form-label">Operateur</label>
                    <div class="operator-list">
                        <div class="operator-card active">
                            <div class="operator-mark"></div>
                            <span>Orange</span>
                        </div>
                        <div class="operator-card">
                            <div class="operator-mark wave"></div>
                            <span>Wave</span>
                        </div>
                        <div class="operator-card">
                            <div class="operator-mark mtn"></div>
                            <span>MTN</span>
                        </div>
                    </div>
                </div>

                <div style="margin-top:10px">
                    <label class="form-label">Numero de telephone</label>
                    <div class="phone-input">
                        <div class="phone-prefix">+221</div>
                        <input class="form-input" type="text" placeholder="77 000 00 00">
                    </div>
                </div>

                <div style="margin-top:16px">
                    <label class="form-label">Montant (<?php echo e(config('hotel.currency', 'XOF')); ?>)</label>
                    <input class="form-input" type="text" placeholder="45,000">
                </div>

                <div style="margin-top:18px">
                    <button class="btn-primary" type="button" style="width:100%;justify-content:center">Lancer le Push USSD</button>
                </div>
            </div>
        </section>
    </div>

    <section class="dash-panel">
        <div class="dash-panel-head">
            <h3>Reservations Recentes</h3>
            <a href="<?php echo e(route('reservations.index')); ?>" class="link-accent">Voir tout</a>
        </div>
        <div style="overflow:auto">
            <table class="table dash-table">
                <thead>
                    <tr>
                        <th>Guest</th>
                        <th>Chambre</th>
                        <th>Sejour</th>
                        <th>Statut</th>
                        <th>Montant</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $recentReservations->take(5); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $reservation): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr>
                            <td>
                                <div class="guest-line">
                                    <strong><?php echo e($reservation->guest_full_name); ?></strong>
                                    <small>ID: <?php echo e($reservation->booking_number); ?></small>
                                </div>
                            </td>
                            <td>
                                <div class="room-line">
                                    <span>◫</span>
                                    <span><?php echo e($reservation->room?->roomType?->name ?? 'Chambre'); ?> <?php echo e($reservation->room?->number); ?></span>
                                </div>
                            </td>
                            <td><?php echo e($reservation->check_in?->translatedFormat('d M')); ?> - <?php echo e($reservation->check_out?->translatedFormat('d M')); ?></td>
                            <td>
                                <?php
                                    $badgeClass = match($reservation->status) {
                                        'confirmed' => 'soft-tag green',
                                        'checked_in' => 'soft-tag dark',
                                        default => 'soft-tag',
                                    };
                                ?>
                                <span class="<?php echo e($badgeClass); ?>"><?php echo e(strtoupper($reservation->status_label)); ?></span>
                            </td>
                            <td><?php echo e(number_format($reservation->final_amount, 0, ',', ' ')); ?> <?php echo e(config('hotel.currency', 'XOF')); ?></td>
                            <td class="row-menu">⋮</td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="6" style="text-align:center">Aucune reservation recente.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </section>

    <div class="foot-grid">
        <section class="dash-panel">
            <div class="dash-panel-head">
                <h3>Status des Chambres (Etage 1)</h3>
            </div>
            <div class="dash-panel-body">
                <div class="room-blocks">
                    <?php $__empty_1 = true; $__currentLoopData = $roomPreview; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $room): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <?php
                            $status = $roomsLegend[$room->status] ?? ['label' => ucfirst($room->status), 'class' => 'orange'];
                        ?>
                        <div class="room-block <?php echo e($status['class']); ?>">
                            <div><?php echo e($room->number); ?></div>
                            <small><?php echo e($status['label']); ?></small>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <div class="room-block green"><div>101</div><small>OK</small></div>
                        <div class="room-block orange"><div>102</div><small>Occupe</small></div>
                        <div class="room-block dark"><div>103</div><small>Menage</small></div>
                        <div class="room-block green"><div>104</div><small>OK</small></div>
                        <div class="room-block orange"><div>105</div><small>Occupe</small></div>
                    <?php endif; ?>
                </div>

                <div class="room-legend">
                    <span><i style="background:#22c55e"></i> Libre (<?php echo e($stats['available_rooms']); ?>)</span>
                    <span><i style="background:#a55a00"></i> Occupe (<?php echo e($stats['occupied_rooms']); ?>)</span>
                    <span><i style="background:#6b7280"></i> Menage (<?php echo e($stats['maintenance_rooms']); ?>)</span>
                </div>
            </div>
        </section>

        <section class="dash-panel">
            <div class="dash-panel-head">
                <h3>Stocks Critiques</h3>
            </div>
            <div class="dash-panel-body">
                <div class="stock-card-list">
                    <div class="stock-item">
                        <div>
                            <strong>Eaux minerales (Cartons)</strong>
                            <small>Stock actuel: 12</small>
                        </div>
                        <div class="stock-stepper">
                            <button type="button">−</button>
                            <span>12</span>
                            <button type="button">+</button>
                        </div>
                    </div>

                    <div class="stock-item">
                        <div>
                            <strong>Linge de lit (Unites)</strong>
                            <small style="color:#dc2626">Stock critique: 4</small>
                        </div>
                        <div class="stock-stepper alert">
                            <button type="button">−</button>
                            <span>04</span>
                            <button type="button">+</button>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\binet\Desktop\xammp\htdocs\Mon_hotel\resources\views/dashboard/index.blade.php ENDPATH**/ ?>