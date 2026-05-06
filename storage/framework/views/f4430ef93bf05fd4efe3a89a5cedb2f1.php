<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo e(config('hotel.name', 'Keur Ndiaye Lo')); ?> - Luxe familial a Rufisque</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&family=Outfit:wght@500;600;700;800&display=swap" rel="stylesheet">
    <?php echo $__env->make('partials.vite-assets', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    <style>
        :root{
            --sand:#f4efe9;
            --light:#fffaf5;
            --ink:#151515;
            --muted:#645e57;
            --brand:#b6691d;
            --brand-dark:#8c4f12;
            --line:rgba(255,255,255,.16);
        }
        *{box-sizing:border-box}
        body{margin:0;font-family:'Manrope',sans-serif;background:#17120f;color:#fff}
        a{text-decoration:none;color:inherit}
        .container{width:min(1220px,calc(100% - 28px));margin:0 auto}
        .hero{
            min-height:100vh;
            background:
                linear-gradient(90deg,rgba(17,12,10,.88) 0%,rgba(17,12,10,.68) 45%,rgba(17,12,10,.48) 100%),
                url('https://images.unsplash.com/photo-1566073771259-6a8506099945?auto=format&fit=crop&w=1800&q=80') center/cover;
        }
        .nav{
            padding:18px 0;
            display:flex;
            align-items:center;
            justify-content:space-between;
            gap:16px;
        }
        .brand{font-family:'Outfit',sans-serif;font-size:25px;font-weight:800}
        .nav-links{display:flex;gap:22px;color:#efe6db;font-size:13px}
        .nav-actions{display:flex;gap:10px;flex-wrap:wrap}
        .btn,.btn-outline{
            padding:12px 18px;
            border-radius:999px;
            font-size:13px;
            font-weight:800;
            letter-spacing:.03em;
        }
        .btn{background:var(--brand);color:#fff}
        .btn:hover{background:var(--brand-dark)}
        .btn-outline{border:1px solid rgba(255,255,255,.28);color:#fff}
        .hero-grid{
            display:grid;
            grid-template-columns:1.2fr .8fr;
            gap:26px;
            align-items:end;
            padding:50px 0 72px;
        }
        .hero-copy{max-width:710px}
        .tag{
            display:inline-flex;
            align-items:center;
            gap:8px;
            padding:8px 12px;
            border-radius:999px;
            background:rgba(255,255,255,.09);
            border:1px solid var(--line);
            font-size:12px;
            color:#f7ead7;
        }
        h1{
            margin:18px 0 0;
            font-family:'Outfit',sans-serif;
            font-size:68px;
            line-height:.95;
            letter-spacing:-.03em;
        }
        .hero-copy p{
            margin:18px 0 0;
            max-width:620px;
            color:#eadfd3;
            font-size:16px;
            line-height:1.7;
        }
        .cta-row{display:flex;gap:12px;flex-wrap:wrap;margin-top:24px}
        .hero-card{
            background:rgba(15,15,17,.58);
            border:1px solid rgba(255,255,255,.12);
            backdrop-filter:blur(10px);
            border-radius:22px;
            padding:22px;
        }
        .hero-card h2{margin:0;font-family:'Outfit',sans-serif;font-size:28px}
        .hero-card p{margin:8px 0 0;color:#e8ddd1;font-size:14px;line-height:1.6}
        .stats{display:grid;grid-template-columns:repeat(3,1fr);gap:12px;margin-top:18px}
        .stat{
            padding:16px;
            border-radius:16px;
            background:rgba(255,255,255,.07);
            border:1px solid rgba(255,255,255,.1);
        }
        .stat strong{display:block;font-size:30px;font-family:'Outfit',sans-serif}
        .stat span{font-size:12px;color:#e8ddd1}

        .quick-book{
            margin-top:22px;
            background:#fff;
            color:#141414;
            border-radius:18px;
            padding:18px;
            box-shadow:0 18px 48px rgba(0,0,0,.22);
        }
        .quick-book h3{margin:0 0 14px;font-size:18px;font-family:'Outfit',sans-serif}
        .booking-grid{display:grid;grid-template-columns:repeat(4,1fr);gap:10px}
        .field{border:1px solid #d8d8d8;border-radius:12px;padding:10px 12px}
        .field label{display:block;margin-bottom:6px;font-size:11px;font-weight:800;color:#6b7280;text-transform:uppercase}
        .field input,.field select{width:100%;border:none;outline:none;background:transparent}
        .book-submit{
            border:none;
            border-radius:12px;
            background:#171717;
            color:#fff;
            font-weight:800;
            cursor:pointer;
        }

        .section-light{background:var(--sand);color:var(--ink);padding:78px 0}
        .section-dark{background:#201510;padding:78px 0}
        .head{text-align:center;max-width:760px;margin:0 auto 32px}
        .head span{display:block;color:var(--brand);font-size:12px;font-weight:800;letter-spacing:.18em;text-transform:uppercase}
        .head h2{margin:10px 0 0;font-family:'Outfit',sans-serif;font-size:50px;line-height:1.05}
        .head p{margin:12px 0 0;color:#625b55;line-height:1.7}

        .services{display:grid;grid-template-columns:repeat(3,1fr);gap:16px}
        .service{
            background:#fff;
            border:1px solid #eadfd6;
            border-radius:18px;
            padding:22px;
            min-height:220px;
        }
        .service h3{margin:14px 0 0;font-family:'Outfit',sans-serif;font-size:28px}
        .service p{margin:10px 0 0;color:#5f5b57;line-height:1.7;font-size:14px}

        .rooms-grid{display:grid;grid-template-columns:repeat(4,1fr);gap:14px}
        .room{
            background:#fff;
            color:var(--ink);
            border-radius:18px;
            overflow:hidden;
            border:1px solid rgba(255,255,255,.08);
        }
        .room-photo{height:220px;background-size:cover;background-position:center}
        .room-body{padding:18px}
        .room-body h3{margin:0;font-family:'Outfit',sans-serif;font-size:24px}
        .room-meta{margin-top:8px;color:#655e58;font-size:13px}
        .room-price{margin-top:14px;font-size:14px;font-weight:800;color:var(--brand-dark)}

        .split{
            display:grid;
            grid-template-columns:1.05fr .95fr;
            gap:18px;
            align-items:stretch;
        }
        .visual{
            min-height:420px;
            border-radius:24px;
            background:
                linear-gradient(180deg,rgba(0,0,0,.08),rgba(0,0,0,.36)),
                url('https://images.unsplash.com/photo-1522798514-97ceb8c4f1c8?auto=format&fit=crop&w=1600&q=80') center/cover;
        }
        .panel{
            background:#fff;
            color:var(--ink);
            border-radius:24px;
            padding:28px;
        }
        .panel h3{margin:0;font-family:'Outfit',sans-serif;font-size:38px}
        .panel ul{list-style:none;padding:0;margin:20px 0 0;display:grid;gap:14px}
        .panel li{
            padding:16px 18px;
            border-radius:16px;
            background:#faf6f1;
            border:1px solid #eadfd6;
        }
        .panel li strong{display:block;font-size:16px}
        .panel li span{display:block;margin-top:6px;color:#625b55;font-size:14px;line-height:1.6}

        .footer{background:#130d0a;padding:28px 0;color:#d6cbc0}
        .footer-grid{display:grid;grid-template-columns:1.2fr 1fr 1fr;gap:16px}
        .footer h4{margin:0 0 10px;font-family:'Outfit',sans-serif;font-size:18px;color:#fff}
        .footer p,.footer a{display:block;margin:6px 0;font-size:13px;color:#d6cbc0}
        .copy{margin-top:20px;padding-top:14px;border-top:1px solid rgba(255,255,255,.1);font-size:12px;color:#a5978a}

        @media (max-width:1080px){
            .hero-grid,.split{grid-template-columns:1fr}
            .rooms-grid{grid-template-columns:repeat(2,1fr)}
        }
        @media (max-width:860px){
            .nav-links{display:none}
            .booking-grid,.services,.footer-grid{grid-template-columns:1fr}
            .stats{grid-template-columns:1fr 1fr}
            h1{font-size:48px}
            .head h2{font-size:38px}
            .rooms-grid{grid-template-columns:1fr}
        }
    </style>
</head>
<body>
    <section class="hero">
        <div class="container">
            <div class="nav">
                <div class="brand"><?php echo e(strtoupper(config('hotel.name', 'Mon Hotel'))); ?></div>
                <div class="nav-links">
                    <a href="#accueil">Accueil</a>
                    <a href="#services">Services</a>
                    <a href="#chambres">Chambres</a>
                    <a href="#experience">Experience</a>
                    <a href="#contact">Contact</a>
                </div>
                <div class="nav-actions">
                    <a href="<?php echo e(route('login')); ?>" class="btn-outline">Connexion</a>
                    <a href="<?php echo e(route('guest.step1')); ?>" class="btn">Reserver</a>
                </div>
            </div>

            <div class="hero-grid" id="accueil">
                <div class="hero-copy">
                    <div class="tag">RUFISQUE · LUXE FAMILIAL</div>
                    <h1>Le confort d un bel hotel, l accueil d une maison.</h1>
                    <p>
                        Bienvenue a <strong><?php echo e(config('hotel.name', 'Keur Ndiaye Lo')); ?></strong> : un hotel familial et elegant a Rufisque,
                        dans une zone calme, non loin du Lac Rose. Confort, prix abordables et environnement agreable pour toute la famille.
                    </p>

                    <div class="cta-row">
                        <a href="<?php echo e(route('visitor.portal')); ?>" class="btn">Visiter</a>
                        <a href="<?php echo e(route('guest.step1')); ?>" class="btn-outline">Reserver</a>
                    </div>

                    <div class="quick-book">
                        <h3>Trouvez votre chambre a Rufisque</h3>

                        <?php if($databaseUnavailable ?? false): ?>
                            <div style="margin-bottom:12px;padding:12px 14px;border-radius:12px;background:#fff4e5;border:1px solid #ffd8a8;color:#8b5e2a;font-size:13px">
                                La base de donnees est indisponible pour le moment. L accueil publique reste visible, mais la reservation sera active une fois MySQL operationnel.
                            </div>
                        <?php endif; ?>

                        <form action="<?php echo e(route('guest.step1')); ?>" method="GET">
                            <div class="booking-grid">
                                <div class="field">
                                    <label>Arrivee</label>
                                    <input type="date" name="check_in" value="<?php echo e(now()->addDay()->toDateString()); ?>" min="<?php echo e(now()->toDateString()); ?>" required>
                                </div>
                                <div class="field">
                                    <label>Depart</label>
                                    <input type="date" name="check_out" value="<?php echo e(now()->addDays(2)->toDateString()); ?>" min="<?php echo e(now()->addDay()->toDateString()); ?>" required>
                                </div>
                                <div class="field">
                                    <label>Voyageurs</label>
                                    <select name="adults" required>
                                        <option value="1">1 adulte</option>
                                        <option value="2" selected>2 adultes</option>
                                        <option value="3">3 adultes</option>
                                        <option value="4">4 adultes</option>
                                    </select>
                                </div>
                                <div class="field">
                                    <label>Type de chambre</label>
                                    <select name="room_type_id">
                                        <option value="">Toutes</option>
                                        <?php $__currentLoopData = $roomTypes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $roomType): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($roomType->id); ?>"><?php echo e($roomType->name); ?></option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                </div>
                            </div>
                            <button class="book-submit" style="margin-top:12px;width:100%;padding:14px 16px" type="submit">Voir les disponibilites</button>
                        </form>
                    </div>
                </div>

                <div class="hero-card">
                    <h2>Pourquoi vous allez aimer</h2>
                    <div class="stats">
                        <div class="stat"><strong>Confort</strong><span>Chambres soignees, repos garanti</span></div>
                        <div class="stat"><strong>Prix</strong><span>Accessibles, sans surprises</span></div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="section-light" id="services">
        <div class="container">
            <div class="head">
                <span>Notre promesse</span>
                <h2>Un luxe doux, pense pour les familles</h2>
                <p>Confort, prix abordables et environnement agreable : tout est reuni pour un sejour serein a Rufisque, non loin du Lac Rose.</p>
            </div>

            <div class="services">
                <article class="service">
                    <div style="font-size:28px">🛏</div>
                    <h3>Confort premium</h3>
                    <p>Des chambres soignees et accueillantes, avec une ambiance calme pour un vrai repos.</p>
                </article>
                <article class="service">
                    <div style="font-size:28px">📅</div>
                    <h3>Reservation facile</h3>
                    <p>Choisissez vos dates, comparez, confirmez. Rapide et simple, meme depuis un telephone.</p>
                </article>
                <article class="service">
                    <div style="font-size:28px">💳</div>
                    <h3>Prix abordables</h3>
                    <p>Un excellent niveau de confort, avec des tarifs accessibles et une transparence totale.</p>
                </article>
            </div>
        </div>
    </section>

    <section class="section-dark" id="chambres">
        <div class="container">
            <div class="head" style="color:#fff">
                <span>Chambres & suites</span>
                <h2>Elegantes, chaleureuses, familiales</h2>
                <p style="color:#d9ccc0">Des espaces pensés pour se reposer, se retrouver, et profiter d un environnement agreable a Rufisque.</p>
            </div>

            <div class="rooms-grid">
                <?php $__empty_1 = true; $__currentLoopData = $featuredRooms; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $room): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <?php
                        $images = [
                            'https://images.unsplash.com/photo-1590490360182-c33d57733427?auto=format&fit=crop&w=900&q=80',
                            'https://images.unsplash.com/photo-1618773928121-c32242e63f39?auto=format&fit=crop&w=900&q=80',
                            'https://images.unsplash.com/photo-1505693416388-ac5ce068fe85?auto=format&fit=crop&w=900&q=80',
                            'https://images.unsplash.com/photo-1578683010236-d716f9a3f461?auto=format&fit=crop&w=900&q=80',
                        ];
                    ?>
                    <article class="room">
                        <div class="room-photo" style="background-image:url('<?php echo e($images[$index % count($images)]); ?>')"></div>
                        <div class="room-body">
                            <h3><?php echo e($room->roomType->name ?? 'Chambre'); ?></h3>
                            <div class="room-meta">Chambre <?php echo e($room->number); ?> · Statut <?php echo e(ucfirst($room->status)); ?></div>
                            <div class="room-price">Disponible a partir de <?php echo e(number_format((float) ($room->base_price ?? 0), 0, ',', ' ')); ?> <?php echo e(config('hotel.currency')); ?></div>
                        </div>
                    </article>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <article class="room" style="grid-column:1/-1">
                        <div class="room-body">
                            <h3>Decouvrez nos chambres</h3>
                            <div class="room-meta">Une selection sera affichee ici des qu elles seront disponibles.</div>
                        </div>
                    </article>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <section class="section-light" id="experience">
        <div class="container split">
            <div class="visual"></div>
            <div class="panel">
                <h3>Une experience familiale, tout en elegance</h3>
                <ul>
                    <li>
                        <strong>Un accueil qui prend soin</strong>
                        <span>Une equipe attentionnee pour vous accompagner, en famille comme en couple.</span>
                    </li>
                    <li>
                        <strong>Tarifs accessibles</strong>
                        <span>Un sejour confortable a prix abordable, avec un total clair avant validation.</span>
                    </li>
                    <li>
                        <strong>Un environnement agreable</strong>
                        <span>Une atmosphere paisible pour se detendre et partager de bons moments.</span>
                    </li>
                </ul>
                <div class="cta-row" style="margin-top:24px">
                    <a href="<?php echo e(route('visitor.portal')); ?>" class="btn">Voir les chambres</a>
                    <a href="<?php echo e(route('login')); ?>" class="btn-outline" style="color:#171717;border-color:#d4c7b9">Espace staff</a>
                </div>
            </div>
        </div>
    </section>

    <footer class="footer" id="contact">
        <div class="container">
            <div class="footer-grid">
                <div>
                    <h4><?php echo e(config('hotel.name', 'Keur Ndiaye Lo')); ?></h4>
                    <p><?php echo e(config('hotel.address', 'DAKAR, Keur Ndiaye Lo')); ?></p>
                    <p><?php echo e(config('hotel.phone', '+221 33 662 63 93')); ?></p>
                    <p><?php echo e(config('hotel.email', 'contact@hotel.com')); ?></p>
                </div>
                <div>
                    <h4>Parcours</h4>
                    <a href="<?php echo e(route('visitor.portal')); ?>">Visiter l hotel</a>
                    <a href="<?php echo e(route('guest.step1')); ?>">Reserver une chambre</a>
                    <a href="<?php echo e(route('login')); ?>">Connexion staff</a>
                </div>
                <div>
                    <h4>Modules</h4>
                    <p>Reservations</p>
                    <p>Restaurant</p>
                    <p>Stocks</p>
                    <p>Comptabilite</p>
                </div>
            </div>
            <div class="copy">© <?php echo e(date('Y')); ?> <?php echo e(strtoupper(config('hotel.name', 'Mon Hotel'))); ?>. Plateforme hoteliere moderne adaptee à tous.</div>
        </div>
    </footer>
</body>
</html>
<?php /**PATH C:\Users\binet\Desktop\xammp\htdocs\Mon_hotel\resources\views/welcome.blade.php ENDPATH**/ ?>