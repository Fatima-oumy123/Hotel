<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo e(config('hotel.name', config('app.name'))); ?> — Réservation</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@300;400;500;600;700&family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">
    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css','resources/js/app.js']); ?>
    <style>
        :root {
            --gold: #C9A84C;
            --gold-light: #E8CC7E;
            --dark: #0F0F0F;
            --dark-2: #1A1A1A;
        }
        body { font-family: 'Inter', sans-serif; }
        .font-serif { font-family: 'Cormorant Garamond', serif; }

        /* Hero */
        .hero-bg {
            background-image: linear-gradient(to bottom, rgba(0,0,0,0.55) 0%, rgba(0,0,0,0.3) 50%, rgba(0,0,0,0.7) 100%),
                url('https://images.unsplash.com/photo-1445019980597-93fa8acb246c?w=1800&q=90');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
        }

        /* Nav */
        .nav-link-guest {
            color: rgba(255,255,255,0.85);
            font-size: 0.8rem;
            font-weight: 500;
            letter-spacing: 0.05em;
            text-transform: uppercase;
            text-decoration: none;
            transition: color 0.2s;
        }
        .nav-link-guest:hover { color: var(--gold-light); }

        /* Étoiles */
        .star { color: var(--gold); font-size: 1.1rem; }

        /* Bouton gold */
        .btn-gold {
            background: var(--gold);
            color: #fff;
            font-family: 'Inter', sans-serif;
            font-size: 0.75rem;
            font-weight: 600;
            letter-spacing: 0.12em;
            text-transform: uppercase;
            padding: 0.9rem 2.5rem;
            border: none;
            cursor: pointer;
            transition: background 0.2s, transform 0.1s;
            display: inline-block;
            text-decoration: none;
        }
        .btn-gold:hover { background: var(--gold-light); color: #000; transform: translateY(-1px); }

        .btn-gold-outline {
            border: 1.5px solid var(--gold);
            color: var(--gold);
            background: transparent;
            font-size: 0.75rem;
            font-weight: 600;
            letter-spacing: 0.1em;
            text-transform: uppercase;
            padding: 0.7rem 2rem;
            cursor: pointer;
            transition: all 0.2s;
            text-decoration: none;
            display: inline-block;
        }
        .btn-gold-outline:hover { background: var(--gold); color: #fff; }

        /* Booking form */
        .booking-form {
            background: rgba(255,255,255,0.97);
            backdrop-filter: blur(10px);
        }
        .booking-input {
            border: none;
            border-bottom: 1.5px solid #ddd;
            border-radius: 0;
            padding: 0.5rem 0;
            font-size: 0.875rem;
            outline: none;
            transition: border-color 0.2s;
            background: transparent;
            width: 100%;
        }
        .booking-input:focus { border-bottom-color: var(--gold); }

        /* Room card */
        .room-card { transition: transform 0.3s, box-shadow 0.3s; }
        .room-card:hover { transform: translateY(-6px); box-shadow: 0 20px 60px rgba(0,0,0,0.15); }

        /* Section label */
        .section-label {
            font-size: 0.7rem;
            font-weight: 600;
            letter-spacing: 0.2em;
            text-transform: uppercase;
            color: var(--gold);
        }

        /* Divider gold */
        .gold-line {
            width: 60px;
            height: 2px;
            background: var(--gold);
            margin: 0 auto;
        }

        /* Stat card */
        .stat-number {
            font-family: 'Cormorant Garamond', serif;
            font-size: 3.5rem;
            font-weight: 300;
            color: #1a1a1a;
            line-height: 1;
        }
        .stat-plus { color: var(--gold); }

        /* Scrollbar */
        ::-webkit-scrollbar { width: 4px; }
        ::-webkit-scrollbar-track { background: #f1f1f1; }
        ::-webkit-scrollbar-thumb { background: var(--gold); border-radius: 2px; }
    </style>
</head>
<body class="bg-white">


<nav class="fixed top-0 left-0 right-0 z-50 flex items-center justify-between px-8 py-5"
     style="background: linear-gradient(to bottom, rgba(0,0,0,0.6), transparent)">

    
    <a href="<?php echo e(route('guest.step1')); ?>" class="flex items-center gap-3">
        <div class="w-8 h-8 border border-yellow-400/60 flex items-center justify-center">
            <svg class="w-4 h-4 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                      d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-2 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
            </svg>
        </div>
        <span class="font-serif text-white text-xl font-light tracking-widest uppercase">
            <?php echo e(config('app.name')); ?>

        </span>
    </a>

    
    <div class="hidden md:flex items-center gap-8">
        <a href="#about" class="nav-link-guest">À propos</a>
        <a href="#rooms" class="nav-link-guest">Chambres</a>
        <a href="#contact" class="nav-link-guest">Contact</a>
        <a href="<?php echo e(route('login')); ?>" class="nav-link-guest">Personnel</a>
    </div>

    
    <a href="#booking" class="btn-gold hidden md:inline-block">
        Réserver
    </a>
</nav>


<section class="hero-bg min-h-screen flex flex-col items-center justify-center relative px-4" id="home">
    <div class="text-center text-white max-w-4xl mx-auto">

        
        <div class="flex items-center justify-center gap-1 mb-4">
            <?php for($i=0; $i<5; $i++): ?>
            <svg class="w-4 h-4" style="color: var(--gold)" fill="currentColor" viewBox="0 0 20 20">
                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
            </svg>
            <?php endfor; ?>
            <span class="text-sm text-white/80 ml-2">(5.0)</span>
        </div>

        <p class="section-label mb-4 text-yellow-400/80 tracking-widest">
            ✦ Luxe Moderne & Art de Vivre Intemporel ✦
        </p>

        <h1 class="font-serif text-5xl md:text-7xl font-light leading-tight mb-6">
            Bienvenue dans notre<br>
            <span style="color: var(--gold-light)">Hôtel de Luxe</span>
        </h1>

        <p class="text-white/70 text-lg font-light mb-10 max-w-2xl mx-auto">
            Une expérience hôtelière d'exception, où chaque détail est pensé pour votre confort et votre bien-être.
        </p>

        <div class="flex flex-col sm:flex-row gap-4 justify-center">
            <a href="#booking" class="btn-gold">Réserver une chambre</a>
            <a href="#rooms" class="btn-gold-outline border-white/60 text-white hover:border-white">
                Découvrir les chambres
            </a>
        </div>
    </div>

    
    <div class="absolute bottom-8 left-1/2 transform -translate-x-1/2 flex flex-col items-center gap-2">
        <span class="text-white/50 text-xs tracking-widest uppercase">Défiler</span>
        <div class="w-px h-12 bg-linear from-white/50 to-transparent"></div>
    </div>
</section>


<section id="booking" class="py-0 -mt-1">
    <div class="max-w-5xl mx-auto px-4">
        <div class="booking-form shadow-2xl rounded-none md:rounded-2xl overflow-hidden -mt-16 relative z-10">
            <div class="p-8">
                <h2 class="font-serif text-2xl font-light text-center text-gray-900 mb-6">
                    Vérifier les disponibilités
                </h2>

                <?php if(session('error')): ?>
                <div class="mb-4 p-3 bg-red-50 border-l-4 border-red-400 text-red-700 text-sm">
                    <?php echo e(session('error')); ?>

                </div>
                <?php endif; ?>
                <?php if($errors->any()): ?>
                <div class="mb-4 p-3 bg-red-50 border-l-4 border-red-400 text-red-700 text-sm">
                    <?php echo e($errors->first()); ?>

                </div>
                <?php endif; ?>

                <form action="<?php echo e(route('guest.search')); ?>" method="POST">
                    <?php echo csrf_field(); ?>
                    <div class="grid grid-cols-2 md:grid-cols-5 gap-6 items-end">
                        <div>
                            <label class="block text-xs font-semibold text-gray-400 uppercase tracking-widest mb-2">
                                Arrivée
                            </label>
                            <input type="date" name="check_in" required
                                   min="<?php echo e(date('Y-m-d')); ?>"
                                   value="<?php echo e(old('check_in')); ?>"
                                   class="booking-input">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-400 uppercase tracking-widest mb-2">
                                Départ
                            </label>
                            <input type="date" name="check_out" required
                                   value="<?php echo e(old('check_out')); ?>"
                                   class="booking-input">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-400 uppercase tracking-widest mb-2">
                                Adultes
                            </label>
                            <select name="adults" class="booking-input">
                                <?php for($i=1; $i<=6; $i++): ?>
                                <option value="<?php echo e($i); ?>" <?php if(old('adults',1)==$i): echo 'selected'; endif; ?>><?php echo e($i); ?> adulte<?php echo e($i>1?'s':''); ?></option>
                                <?php endfor; ?>
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-400 uppercase tracking-widest mb-2">
                                Type de chambre
                            </label>
                            <select name="room_type_id" class="booking-input">
                                <option value="">Tous les types</option>
                                <?php $__currentLoopData = $roomTypes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $type): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($type->id); ?>" <?php if(old('room_type_id')==$type->id): echo 'selected'; endif; ?>>
                                    <?php echo e($type->name); ?>

                                </option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>
                        <div>
                            <button type="submit" class="btn-gold w-full text-center">
                                Rechercher
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>


<section id="about" class="py-24 bg-white">
    <div class="max-w-6xl mx-auto px-6">
        <div class="grid md:grid-cols-2 gap-16 items-center">
            <div>
                <p class="section-label mb-3">✦ À propos de nous</p>
                <div class="gold-line mb-6" style="margin: 0 0 1.5rem 0"></div>
                <h2 class="font-serif text-4xl font-light text-gray-900 mb-6 leading-tight">
                    Une Expérience<br>
                    <em>Incomparable</em>
                </h2>
                <p class="text-gray-500 leading-relaxed mb-4">
                    Depuis notre ouverture, nous nous efforçons d'offrir à chaque client une expérience unique.
                    Notre équipe passionnée combine un service impeccable avec une attention aux moindres détails.
                </p>
                <p class="text-gray-500 leading-relaxed mb-8">
                    De nos suites élégantes à notre restaurant gastronomique, chaque espace a été conçu
                    pour créer des souvenirs inoubliables.
                </p>
                <a href="#rooms" class="btn-gold-outline">Découvrir l'hôtel</a>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <img src="https://images.unsplash.com/photo-1631049307264-da0ec9d70304?w=400&q=80"
                     alt="Chambre luxe" class="rounded-lg w-full h-48 object-cover">
                <img src="https://images.unsplash.com/photo-1582719478250-c89cae4dc85b?w=400&q=80"
                     alt="Suite" class="rounded-lg w-full h-48 object-cover mt-8">
                <img src="https://images.unsplash.com/photo-1571896349842-33c89424de2d?w=400&q=80"
                     alt="Piscine" class="rounded-lg w-full h-48 object-cover">
                <img src="https://images.unsplash.com/photo-1414235077428-338989a2e8c0?w=400&q=80"
                     alt="Restaurant" class="rounded-lg w-full h-48 object-cover mt-8">
            </div>
        </div>
    </div>
</section>


<section class="py-16 border-y border-gray-100 bg-gray-50">
    <div class="max-w-5xl mx-auto px-6">
        <p class="section-label text-center mb-10">✦ Par les chiffres</p>
        <div class="grid grid-cols-3 gap-8 text-center">
            <div>
                <div class="stat-number">98<span class="stat-plus">%</span><span class="text-gray-400 text-2xl">+</span></div>
                <p class="text-gray-500 text-sm mt-2 font-medium">Avis positifs</p>
                <p class="text-gray-400 text-xs mt-1">Retours de clients satisfaits</p>
            </div>
            <div>
                <div class="stat-number">15<span class="stat-plus">+</span></div>
                <p class="text-gray-500 text-sm mt-2 font-medium">Années d'expertise</p>
                <p class="text-gray-400 text-xs mt-1">D'excellence hôtelière</p>
            </div>
            <div>
                <div class="stat-number">25<span style="font-size:2rem">K</span><span class="stat-plus">+</span></div>
                <p class="text-gray-500 text-sm mt-2 font-medium">Clients heureux</p>
                <p class="text-gray-400 text-xs mt-1">Nous font confiance</p>
            </div>
        </div>
    </div>
</section>


<section id="rooms" class="py-24 bg-white">
    <div class="max-w-6xl mx-auto px-6">

        <div class="text-center mb-14">
            <p class="section-label mb-3">✦ Chambres & Suites</p>
            <h2 class="font-serif text-4xl font-light text-gray-900 mb-4">
                Nos Exquises Collections
            </h2>
            <div class="gold-line"></div>
        </div>

        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
            <?php
            $roomImages = [
                'https://images.unsplash.com/photo-1631049307264-da0ec9d70304?w=600&q=80',
                'https://images.unsplash.com/photo-1582719478250-c89cae4dc85b?w=600&q=80',
                'https://images.unsplash.com/photo-1590490360182-c33d57733427?w=600&q=80',
                'https://images.unsplash.com/photo-1611892440504-42a792e24d32?w=600&q=80',
                'https://images.unsplash.com/photo-1618773928121-c32242e63f39?w=600&q=80',
                'https://images.unsplash.com/photo-1560347876-aeef00ee58a1?w=600&q=80',
            ];
            ?>

            <?php $__empty_1 = true; $__currentLoopData = $roomTypes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $type): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <div class="room-card overflow-hidden bg-white border border-gray-100 shadow-md">
                
                <div class="relative overflow-hidden">
                    <img src="<?php echo e($roomImages[$index % count($roomImages)]); ?>"
                         alt="<?php echo e($type->name); ?>"
                         class="w-full h-56 object-cover transition-transform duration-500 hover:scale-105">
                    
                    <div class="absolute bottom-3 right-3 px-3 py-1.5"
                         style="background: var(--gold)">
                        <span class="text-white text-sm font-semibold">
                            <?php echo e(number_format($type->base_price, 0, ',', ' ')); ?> FCFA<span class="text-white/80 text-xs">/nuit</span>
                        </span>
                    </div>
                </div>

                
                <div class="p-5">
                    <h3 class="font-serif text-xl font-light text-gray-900 mb-1"><?php echo e($type->name); ?></h3>

                    
                    <div class="flex items-center gap-4 text-xs text-gray-400 mb-3">
                        <span class="flex items-center gap-1">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M4 8V4m0 0h4M4 4l5 5m11-1V4m0 0h-4m4 0l-5 5M4 16v4m0 0h4m-4 0l5-5m11 5l-5-5m5 5v-4m0 4h-4"/>
                            </svg>
                            90 m²
                        </span>
                        <span class="flex items-center gap-1">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            1 lit
                        </span>
                        <span class="flex items-center gap-1">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                            <?php echo e($type->capacity); ?> pers.
                        </span>
                    </div>

                    <?php if($type->description): ?>
                    <p class="text-gray-500 text-sm leading-relaxed mb-4 line-clamp-2">
                        <?php echo e($type->description); ?>

                    </p>
                    <?php endif; ?>

                    
                    <?php if($type->amenities && count($type->amenities) > 0): ?>
                    <div class="flex flex-wrap gap-1 mb-4">
                        <?php $__currentLoopData = array_slice($type->amenities, 0, 3); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $amenity): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <span class="text-xs text-gray-500 bg-gray-50 border border-gray-100 px-2 py-0.5">
                            <?php echo e($amenity); ?>

                        </span>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                    <?php endif; ?>

                    <a href="<?php echo e(route('guest.step1')); ?>#booking" class="btn-gold w-full text-center block text-xs">
                        Réserver ce type
                    </a>
                </div>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            
            <?php $__currentLoopData = [
                ['Royal Sapphire Suite', '$300', 'Suite présidentielle avec vue panoramique'],
                ['Golden Horizon Room', '$200', 'Chambre double avec terrasse privée'],
                ['Pearl Orchid Suite', '$150', 'Suite junior avec salon séparé'],
            ]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $fakeRoom): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="room-card overflow-hidden bg-white border border-gray-100 shadow-md">
                <div class="relative overflow-hidden">
                    <img src="https://images.unsplash.com/photo-1631049307264-da0ec9d70304?w=600&q=80"
                         alt="<?php echo e($fakeRoom[0]); ?>" class="w-full h-56 object-cover">
                    <div class="absolute bottom-3 right-3 px-3 py-1.5" style="background: var(--gold)">
                        <span class="text-white text-sm font-semibold"><?php echo e($fakeRoom[1]); ?>/nuit</span>
                    </div>
                </div>
                <div class="p-5">
                    <h3 class="font-serif text-xl font-light mb-2"><?php echo e($fakeRoom[0]); ?></h3>
                    <p class="text-gray-500 text-sm mb-4"><?php echo e($fakeRoom[2]); ?></p>
                    <a href="#booking" class="btn-gold w-full text-center block text-xs">Réserver</a>
                </div>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            <?php endif; ?>
        </div>
    </div>
</section>


<section class="relative h-96 overflow-hidden">
    <img src="https://images.unsplash.com/photo-1566073771259-6a8506099945?w=1800&q=90"
         alt="Vue piscine" class="w-full h-full object-cover">
    <div class="absolute inset-0 bg-black/50 flex items-center justify-center">
        <div class="text-center text-white">
            <p class="section-label text-yellow-400/80 mb-4">✦ Découvrez nos meilleures offres</p>
            <h2 class="font-serif text-4xl font-light mb-6">Nos Hébergements Premium</h2>
            <a href="#booking" class="btn-gold">Réserver maintenant</a>
        </div>
    </div>
</section>


<section id="contact" class="py-16 bg-gray-900 text-white">
    <div class="max-w-6xl mx-auto px-6">
        <div class="grid md:grid-cols-3 gap-12 mb-12">
            <div>
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-8 h-8 border border-yellow-400/40 flex items-center justify-center">
                        <svg class="w-4 h-4 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                  d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5"/>
                        </svg>
                    </div>
                    <span class="font-serif text-xl font-light tracking-widest"><?php echo e(config('app.name')); ?></span>
                </div>
                <p class="text-gray-400 text-sm leading-relaxed">
                    Une expérience hôtelière d'exception depuis plus de 15 ans.
                </p>
            </div>
            <div>
                <h4 class="text-xs font-semibold uppercase tracking-widest text-yellow-400/80 mb-4">Contact</h4>
                <div class="space-y-2 text-gray-400 text-sm">
                    <p><?php echo e(config('hotel.address', 'Dakar, Sénégal')); ?></p>
                    <p><?php echo e(config('hotel.phone', '+221 33 000 00 00')); ?></p>
                    <p><?php echo e(config('hotel.email', 'contact@hotel.com')); ?></p>
                </div>
            </div>
            <div>
                <h4 class="text-xs font-semibold uppercase tracking-widest text-yellow-400/80 mb-4">Liens rapides</h4>
                <div class="space-y-2">
                    <a href="#rooms" class="block text-gray-400 hover:text-yellow-400 text-sm transition-colors">Chambres & Suites</a>
                    <a href="#booking" class="block text-gray-400 hover:text-yellow-400 text-sm transition-colors">Réserver</a>
                    <a href="<?php echo e(route('guest.cancel')); ?>" class="block text-gray-400 hover:text-yellow-400 text-sm transition-colors">Gérer ma réservation</a>
                    <a href="<?php echo e(route('login')); ?>" class="block text-gray-400 hover:text-yellow-400 text-sm transition-colors">Espace personnel</a>
                </div>
            </div>
        </div>
        <div class="border-t border-gray-700/50 pt-8 flex flex-col md:flex-row items-center justify-between gap-4">
            <p class="text-gray-500 text-xs">© <?php echo e(date('Y')); ?> <?php echo e(config('app.name')); ?> · Tous droits réservés</p>
            <div class="flex items-center gap-4">
                <a href="<?php echo e(route('guest.cancel')); ?>"
                   class="text-xs text-gray-500 hover:text-yellow-400 transition-colors">
                    Annuler ma réservation
                </a>
                <span class="text-gray-700">·</span>
                <a href="<?php echo e(route('login')); ?>"
                   class="text-xs text-gray-500 hover:text-yellow-400 transition-colors">
                    Connexion staff
                </a>
            </div>
        </div>
    </div>
</section>

<script>
// Smooth scroll
document.querySelectorAll('a[href^="#"]').forEach(a => {
    a.addEventListener('click', e => {
        const target = document.querySelector(a.getAttribute('href'));
        if (target) {
            e.preventDefault();
            target.scrollIntoView({ behavior: 'smooth', block: 'start' });
        }
    });
});

// Navbar scroll effect
window.addEventListener('scroll', () => {
    const nav = document.querySelector('nav');
    if (window.scrollY > 80) {
        nav.style.background = 'rgba(15,15,15,0.95)';
        nav.style.backdropFilter = 'blur(10px)';
        nav.style.paddingTop = '1rem';
        nav.style.paddingBottom = '1rem';
    } else {
        nav.style.background = 'linear-gradient(to bottom, rgba(0,0,0,0.6), transparent)';
        nav.style.backdropFilter = '';
        nav.style.paddingTop = '1.25rem';
        nav.style.paddingBottom = '1.25rem';
    }
});
</script>

</body>
</html>
<?php /**PATH C:\Users\binet\Desktop\xammp\htdocs\Mon_hotel\resources\views/guest/step1.blade.php ENDPATH**/ ?>