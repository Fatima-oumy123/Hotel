<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('reservations', function (Blueprint $table) {
            $table->id();
            $table->string('booking_number')->unique(); // REZ-20240001
            $table->foreignId('room_id')->constrained()->onDelete('restrict');
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
            // Client sans compte (Guest)
            $table->string('guest_first_name');
            $table->string('guest_last_name');
            $table->date('guest_dob')->nullable();
            $table->string('guest_id_number')->nullable();
            $table->string('guest_phone');
            $table->string('guest_email')->nullable();
            $table->string('guest_token')->nullable(); // Pour annulation sans compte
            // Dates
            $table->date('check_in');
            $table->date('check_out');
            $table->time('actual_check_in')->nullable();
            $table->time('actual_check_out')->nullable();
            $table->integer('nights')->storedAs('DATEDIFF(check_out, check_in)');
            // Tarification
            $table->decimal('price_per_night', 10, 2);
            $table->decimal('total_amount', 10, 2);
            $table->decimal('discount', 10, 2)->default(0);
            $table->decimal('tax_amount', 10, 2)->default(0);
            $table->decimal('final_amount', 10, 2);
            // Statut
            $table->enum('status', ['pending','confirmed','checked_in','checked_out','cancelled'])
                  ->default('pending');
            $table->string('cancellation_reason')->nullable();
            $table->integer('adults')->default(1);
            $table->integer('children')->default(0);
            $table->text('special_requests')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }
    public function down(): void { Schema::dropIfExists('reservations'); }
};
