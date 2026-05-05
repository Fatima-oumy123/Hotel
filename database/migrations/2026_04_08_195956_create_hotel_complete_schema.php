<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Migration maître — crée toutes les tables du système hôtelier
 * Utilise Schema::hasTable() pour être idempotente (peut tourner plusieurs fois sans erreur)
 */
return new class extends Migration
{
    public function up(): void
    {
        // ─── 1. USERS (adapter si déjà créée par Laravel) ────────────────
        if (!Schema::hasTable('users')) {
            Schema::create('users', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('email')->unique();
                $table->string('phone', 20)->nullable();
                $table->boolean('is_active')->default(true);
                $table->timestamp('email_verified_at')->nullable();
                $table->string('password');
                $table->rememberToken();
                $table->timestamps();
                $table->softDeletes();
            });
        } else {
            // Table existe déjà → ajouter les colonnes manquantes
            Schema::table('users', function (Blueprint $table) {
                if (!Schema::hasColumn('users', 'phone')) {
                    $table->string('phone', 20)->nullable()->after('email');
                }
                if (!Schema::hasColumn('users', 'is_active')) {
                    $table->boolean('is_active')->default(true)->after('phone');
                }
                if (!Schema::hasColumn('users', 'deleted_at')) {
                    $table->softDeletes();
                }
            });
        }

        // ─── 2. PASSWORD RESET TOKENS ────────────────────────────────────
        if (!Schema::hasTable('password_reset_tokens')) {
            Schema::create('password_reset_tokens', function (Blueprint $table) {
                $table->string('email')->primary();
                $table->string('token');
                $table->timestamp('created_at')->nullable();
            });
        }

        // ─── 3. SESSIONS ─────────────────────────────────────────────────
        if (!Schema::hasTable('sessions')) {
            Schema::create('sessions', function (Blueprint $table) {
                $table->string('id')->primary();
                $table->foreignId('user_id')->nullable()->index();
                $table->string('ip_address', 45)->nullable();
                $table->text('user_agent')->nullable();
                $table->longText('payload');
                $table->integer('last_activity')->index();
            });
        }

        // ─── 4. JOBS (Queue) ─────────────────────────────────────────────
        if (!Schema::hasTable('jobs')) {
            Schema::create('jobs', function (Blueprint $table) {
                $table->id();
                $table->string('queue')->index();
                $table->longText('payload');
                $table->unsignedTinyInteger('attempts');
                $table->unsignedInteger('reserved_at')->nullable();
                $table->unsignedInteger('available_at');
                $table->unsignedInteger('created_at');
            });
        }

        if (!Schema::hasTable('failed_jobs')) {
            Schema::create('failed_jobs', function (Blueprint $table) {
                $table->id();
                $table->string('uuid')->unique();
                $table->text('connection');
                $table->text('queue');
                $table->longText('payload');
                $table->longText('exception');
                $table->timestamp('failed_at')->useCurrent();
            });
        }

        // ─── 5. ROOM TYPES ────────────────────────────────────────────────
        if (!Schema::hasTable('room_types')) {
            Schema::create('room_types', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->integer('capacity')->default(1);
                $table->decimal('base_price', 10, 2)->default(0);
                $table->text('description')->nullable();
                $table->json('amenities')->nullable();
                $table->string('image')->nullable();
                $table->timestamps();
            });
        }

        // ─── 6. ROOMS ─────────────────────────────────────────────────────
        if (!Schema::hasTable('rooms')) {
            Schema::create('rooms', function (Blueprint $table) {
                $table->id();
                $table->string('number', 10)->unique();
                $table->foreignId('room_type_id')->constrained('room_types')->onDelete('restrict');
                $table->integer('floor')->default(1);
                $table->enum('status', ['available', 'reserved', 'occupied', 'maintenance'])->default('available');
                $table->text('notes')->nullable();
                $table->timestamps();
            });
        }

        // ─── 7. RESERVATIONS ──────────────────────────────────────────────
        if (!Schema::hasTable('reservations')) {
            Schema::create('reservations', function (Blueprint $table) {
                $table->id();
                $table->string('booking_number')->unique();
                $table->foreignId('room_id')->constrained('rooms')->onDelete('restrict');
                $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null');
                $table->string('guest_first_name');
                $table->string('guest_last_name');
                $table->date('guest_dob')->nullable();
                $table->string('guest_id_number')->nullable();
                $table->string('guest_phone', 30);
                $table->string('guest_email')->nullable();
                $table->string('guest_token', 64)->nullable()->index();
                $table->date('check_in');
                $table->date('check_out');
                $table->time('actual_check_in')->nullable();
                $table->time('actual_check_out')->nullable();
                $table->decimal('price_per_night', 10, 2)->default(0);
                $table->decimal('total_amount', 10, 2)->default(0);
                $table->decimal('discount', 10, 2)->default(0);
                $table->decimal('tax_amount', 10, 2)->default(0);
                $table->decimal('final_amount', 10, 2)->default(0);
                $table->enum('status', ['pending', 'confirmed', 'checked_in', 'checked_out', 'cancelled'])->default('pending');
                $table->string('cancellation_reason')->nullable();
                $table->integer('adults')->default(1);
                $table->integer('children')->default(0);
                $table->text('special_requests')->nullable();
                $table->timestamps();
                $table->softDeletes();
            });
        }

        // ─── 8. PAYMENTS ──────────────────────────────────────────────────
        if (!Schema::hasTable('payments')) {
            Schema::create('payments', function (Blueprint $table) {
                $table->id();
                $table->foreignId('reservation_id')->constrained('reservations')->onDelete('cascade');
                $table->decimal('amount', 10, 2);
                $table->enum('method', ['card', 'cash', 'check', 'transfer'])->default('cash');
                $table->enum('status', ['pending', 'completed', 'failed', 'refunded'])->default('pending');
                $table->string('transaction_id')->nullable();
                $table->string('reference')->nullable();
                $table->text('notes')->nullable();
                $table->timestamp('paid_at')->nullable();
                $table->timestamps();
            });
        }

        // ─── 9. INVOICES ──────────────────────────────────────────────────
        if (!Schema::hasTable('invoices')) {
            Schema::create('invoices', function (Blueprint $table) {
                $table->id();
                $table->string('invoice_number')->unique();
                $table->foreignId('reservation_id')->constrained('reservations')->onDelete('restrict');
                $table->decimal('subtotal', 10, 2)->default(0);
                $table->decimal('tax_rate', 5, 2)->default(18);
                $table->decimal('tax_amount', 10, 2)->default(0);
                $table->decimal('stay_tax', 10, 2)->default(0);
                $table->decimal('total', 10, 2)->default(0);
                $table->enum('status', ['draft', 'issued', 'paid', 'cancelled'])->default('draft');
                $table->string('pdf_path')->nullable();
                $table->timestamp('issued_at')->nullable();
                $table->timestamps();
            });
        }

        // ─── 10. MAINTENANCE ──────────────────────────────────────────────
        if (!Schema::hasTable('maintenance_tickets')) {
            Schema::create('maintenance_tickets', function (Blueprint $table) {
                $table->id();
                $table->foreignId('room_id')->constrained('rooms')->onDelete('cascade');
                $table->foreignId('reported_by')->constrained('users')->onDelete('cascade');
                $table->foreignId('assigned_to')->nullable()->constrained('users')->onDelete('set null');
                $table->string('title');
                $table->text('description');
                $table->enum('priority', ['low', 'medium', 'high', 'urgent'])->default('medium');
                $table->enum('status', ['pending', 'in_progress', 'completed'])->default('pending');
                $table->timestamp('resolved_at')->nullable();
                $table->text('resolution_notes')->nullable();
                $table->timestamps();
            });
        }

        // ─── 11. EMPLOYEES ────────────────────────────────────────────────
        if (!Schema::hasTable('employees')) {
            Schema::create('employees', function (Blueprint $table) {
                $table->id();
                $table->string('first_name');
                $table->string('last_name');
                $table->string('email')->unique();
                $table->string('phone', 30);
                $table->string('position');
                $table->string('department');
                $table->decimal('salary', 10, 2)->default(0);
                $table->date('hire_date');
                $table->date('end_date')->nullable();
                $table->enum('status', ['active', 'inactive', 'on_leave'])->default('active');
                $table->string('id_number')->nullable();
                $table->string('contract_type', 20)->default('CDI');
                $table->timestamps();
                $table->softDeletes();
            });
        }

        // ─── 12. EXPENSES ─────────────────────────────────────────────────
        if (!Schema::hasTable('expenses')) {
            Schema::create('expenses', function (Blueprint $table) {
                $table->id();
                $table->string('title');
                $table->text('description')->nullable();
                $table->decimal('amount', 10, 2)->default(0);
                $table->string('category');
                $table->string('supplier')->nullable();
                $table->date('expense_date');
                $table->foreignId('approved_by')->nullable()->constrained('users')->onDelete('set null');
                $table->string('receipt_path')->nullable();
                $table->timestamps();
            });
        }

        // ─── 13. SEASONAL RATES ───────────────────────────────────────────
        if (!Schema::hasTable('seasonal_rates')) {
            Schema::create('seasonal_rates', function (Blueprint $table) {
                $table->id();
                $table->foreignId('room_type_id')->constrained('room_types')->onDelete('cascade');
                $table->string('name');
                $table->date('start_date');
                $table->date('end_date');
                $table->decimal('price_per_night', 10, 2);
                $table->decimal('discount_percent', 5, 2)->default(0);
                $table->timestamps();
            });
        }

        // ─── 14. RESTAURANT SALES ─────────────────────────────────────────
        if (!Schema::hasTable('restaurant_sales')) {
            Schema::create('restaurant_sales', function (Blueprint $table) {
                $table->id();
                $table->string('item_name');
                $table->string('category', 50);
                $table->integer('quantity')->default(1);
                $table->decimal('unit_price', 10, 2)->default(0);
                $table->decimal('total', 10, 2)->default(0);
                $table->enum('payment_method', ['cash', 'card', 'room_charge'])->default('cash');
                $table->string('table_number', 10)->nullable();
                $table->text('notes')->nullable();
                $table->enum('status', ['completed', 'cancelled'])->default('completed');
                $table->timestamp('sale_date')->nullable();
                $table->foreignId('cashier_id')->nullable()->constrained('users')->onDelete('set null');
                $table->string('cancellation_reason')->nullable();
                $table->timestamps();
            });
        }

        // ─── 15. AUDIT LOGS ───────────────────────────────────────────────
        if (!Schema::hasTable('audit_logs')) {
            Schema::create('audit_logs', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null');
                $table->string('action');
                $table->string('model_type')->nullable();
                $table->unsignedBigInteger('model_id')->nullable();
                $table->json('old_values')->nullable();
                $table->json('new_values')->nullable();
                $table->string('ip_address', 45)->nullable();
                $table->string('user_agent')->nullable();
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        // Supprime dans l'ordre inverse (dépendances)
        Schema::dropIfExists('audit_logs');
        Schema::dropIfExists('restaurant_sales');
        Schema::dropIfExists('seasonal_rates');
        Schema::dropIfExists('expenses');
        Schema::dropIfExists('employees');
        Schema::dropIfExists('maintenance_tickets');
        Schema::dropIfExists('invoices');
        Schema::dropIfExists('payments');
        Schema::dropIfExists('reservations');
        Schema::dropIfExists('rooms');
        Schema::dropIfExists('room_types');
        Schema::dropIfExists('failed_jobs');
        Schema::dropIfExists('jobs');
        Schema::dropIfExists('sessions');
        Schema::dropIfExists('password_reset_tokens');
    }
};
