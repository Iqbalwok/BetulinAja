<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Artisan;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Seeds expanded categories and 36 sample technicians on deployment.
     */
    public function up(): void
    {
        if (app()->environment('testing')) {
            return;
        }

        try {
            Artisan::call('db:seed', [
                '--class' => 'Database\\Seeders\\ServiceCategorySeeder',
                '--force' => true,
            ]);
            Artisan::call('db:seed', [
                '--class' => 'Database\\Seeders\\SampleTechniciansSeeder',
                '--force' => true,
            ]);
        } catch (\Throwable $e) {
            logger()->error('Auto-seed sample technicians error: ' . $e->getMessage());
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // No-op to preserve technician data
    }
};
