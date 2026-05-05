<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('room_types', function (Blueprint $table) {
            $table->id();
            $table->string('name');          // Simple, Double, Suite...
            $table->integer('capacity');
            $table->decimal('base_price', 10, 2);
            $table->text('description')->nullable();
            $table->json('amenities')->nullable(); // ["WiFi","TV","Climatisation"]
            $table->string('image')->nullable();
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('room_types'); }
};
