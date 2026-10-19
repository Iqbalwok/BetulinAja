<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('order_photos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->cascadeOnDelete();
            $table->string('file_path');
            $table->string('original_name')->nullable();
            $table->enum('photo_type', ['damage', 'before_work', 'after_work', 'evidence'])->default('damage');
            $table->bigInteger('file_size')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('order_photos');
    }
};
