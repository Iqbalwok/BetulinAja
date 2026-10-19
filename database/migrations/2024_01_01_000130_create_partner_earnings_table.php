<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('partner_earnings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('partner_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('order_id')->nullable()->constrained()->nullOnDelete();
            $table->enum('type', ['earning', 'withdrawal', 'adjustment'])->default('earning');
            $table->decimal('amount', 15, 2)->default(0);
            $table->decimal('platform_commission', 10, 2)->default(0);
            $table->decimal('net_amount', 15, 2)->default(0);
            $table->string('description')->nullable();
            $table->enum('status', ['pending', 'settled', 'withdrawn'])->default('settled');
            $table->timestamps();
            $table->index('partner_id');
        });
    }

    public function down(): void {
        Schema::dropIfExists('partner_earnings');
    }
};
