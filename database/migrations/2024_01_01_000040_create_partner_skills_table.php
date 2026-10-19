<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('partner_skills', function (Blueprint $table) {
            $table->id();
            $table->foreignId('partner_id')->constrained('partner_profiles')->cascadeOnDelete();
            $table->foreignId('category_id')->constrained('service_categories')->cascadeOnDelete();
            $table->integer('experience_years')->default(0);
            $table->timestamps();
            $table->unique(['partner_id', 'category_id']);
        });
    }

    public function down(): void {
        Schema::dropIfExists('partner_skills');
    }
};
