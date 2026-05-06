<?php $__env->startSection('title', 'Reservation'); ?>
<?php $__env->startSection('hero_eyebrow', 'Reservation en 1 etape'); ?>
<?php $__env->startSection('hero_title', 'Reservez votre sejour a Rufisque.'); ?>
<?php $__env->startSection('hero_copy', 'Choisissez vos dates, le type de chambre, puis laissez-nous vos informations. Nous vous proposerons automatiquement une chambre disponible.'); ?>
<?php $__env->startSection('card_title', 'Simple et rapide'); ?>
<?php $__env->startSection('card_copy', 'Une seule page pour reserver. Total clair avant paiement.'); ?>
<?php $__env->startSection('hero_stats'); ?>
    <div class="box"><strong>1</strong><span>etape</span></div>
    <div class="box"><strong>Rufisque</strong><span>zone calme</span></div>
    <div class="box"><strong><?php echo e(config('hotel.currency')); ?></strong><span>prix abordables</span></div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<?php
    $defaults = $defaults ?? [];
?>

<div class="panel">
    <div class="panel-head"><h2>Reservation en 1 etape</h2></div>
    <div class="panel-body">
        <?php if($errors->any()): ?>
            <div class="alert error" style="margin-bottom:16px">
                <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div><?php echo e($error); ?></div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        <?php endif; ?>

        <form action="<?php echo e(route('guest.book')); ?>" method="POST" style="display:grid;gap:16px">
            <?php echo csrf_field(); ?>

            <div class="panel" style="border-radius:18px">
                <div class="panel-head"><h3>Votre sejour</h3></div>
                <div class="panel-body">
                    <div class="form-grid">
                        <div class="field">
                            <label>Arrivee</label>
                            <input type="date" name="check_in" required min="<?php echo e(date('Y-m-d')); ?>" value="<?php echo e(old('check_in', $defaults['check_in'] ?? '')); ?>">
                        </div>
                        <div class="field">
                            <label>Depart</label>
                            <input type="date" name="check_out" required value="<?php echo e(old('check_out', $defaults['check_out'] ?? '')); ?>">
                        </div>
                        <div class="field">
                            <label>Adultes</label>
                            <select name="adults" required>
                                <?php for($i=1; $i<=10; $i++): ?>
                                    <option value="<?php echo e($i); ?>" <?php if((int) old('adults', $defaults['adults'] ?? 2) === $i): echo 'selected'; endif; ?>><?php echo e($i); ?></option>
                                <?php endfor; ?>
                            </select>
                        </div>
                        <div class="field">
                            <label>Enfants</label>
                            <select name="children">
                                <?php for($i=0; $i<=10; $i++): ?>
                                    <option value="<?php echo e($i); ?>" <?php if((int) old('children', $defaults['children'] ?? 0) === $i): echo 'selected'; endif; ?>><?php echo e($i); ?></option>
                                <?php endfor; ?>
                            </select>
                        </div>
                    </div>

                    <div class="field" style="margin-top:12px">
                        <label>Type de chambre</label>
                        <select name="room_type_id">
                            <option value="">Le meilleur choix pour vous (recommande)</option>
                            <?php $__currentLoopData = $roomTypes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $type): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($type->id); ?>" <?php if((string) old('room_type_id', $defaults['room_type_id'] ?? '') === (string) $type->id): echo 'selected'; endif; ?>><?php echo e($type->name); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>

                    <div class="alert info" style="margin-top:12px">
                        Nous selectionnons automatiquement une chambre disponible qui correspond a votre demande, pour vous faire gagner du temps.
                    </div>
                </div>
            </div>

            <div class="panel" style="border-radius:18px">
                <div class="panel-head"><h3>Vos informations</h3></div>
                <div class="panel-body">
                    <div class="form-grid">
                        <div class="field">
                            <label>Prenom</label>
                            <input type="text" name="guest_first_name" value="<?php echo e(old('guest_first_name')); ?>" required placeholder="Votre prenom">
                        </div>
                        <div class="field">
                            <label>Nom</label>
                            <input type="text" name="guest_last_name" value="<?php echo e(old('guest_last_name')); ?>" required placeholder="Votre nom">
                        </div>
                        <div class="field">
                            <label>Telephone</label>
                            <input type="text" name="guest_phone" value="<?php echo e(old('guest_phone')); ?>" required placeholder="+221 77 000 00 00">
                        </div>
                        <div class="field">
                            <label>Email (optionnel)</label>
                            <input type="email" name="guest_email" value="<?php echo e(old('guest_email')); ?>" placeholder="votre@email.com">
                        </div>
                        <div class="field">
                            <label>Date de naissance (optionnel)</label>
                            <input type="date" name="guest_dob" value="<?php echo e(old('guest_dob')); ?>">
                        </div>
                        <div class="field">
                            <label>Numero de piece (optionnel)</label>
                            <input type="text" name="guest_id_number" value="<?php echo e(old('guest_id_number')); ?>" placeholder="CNI, passeport...">
                        </div>
                    </div>

                    <div class="field" style="margin-top:12px">
                        <label>Demandes speciales (optionnel)</label>
                        <textarea name="special_requests" rows="4" placeholder="Lit bebe, chambre calme, preference alimentaire..."><?php echo e(old('special_requests')); ?></textarea>
                    </div>

                    <div class="alert info" style="margin-top:12px">
                        Vos donnees sont utilisees uniquement pour votre reservation. L annulation gratuite reste possible jusqu a <?php echo e(config('hotel.cancellation_hours', 48)); ?>h avant l arrivee.
                    </div>
                </div>
            </div>

            <button type="submit" class="btn-primary" style="width:100%;padding:16px 18px;font-size:15px">
                Confirmer et passer au paiement
            </button>
        </form>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.guest', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\HP\Desktop\Mon_hotel (2)\Mon_hotel\resources\views/guest/step2.blade.php ENDPATH**/ ?>