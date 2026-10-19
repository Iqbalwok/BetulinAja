<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->unique()->constrained()->cascadeOnDelete();
            $table->foreignId('customer_id')->constrained('users');
            $table->foreignId('partner_id')->constrained('users');
            $table->tinyInteger('rating')->unsigned();
            $table->text('comment')->nullable();
            $table->boolean('is_visible')->default(true);
            $table->timestamp('moderated_at')->nullable();
            $table->timestamps();

            $table->index('partner_id');
        });
    }

    public function down(): void {
        Schema::dropIfExists('reviews');
    }
};
