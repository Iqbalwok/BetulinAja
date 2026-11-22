<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Artisan;
use App\Models\ServiceCategory;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Automatically seeds the database on production/staging deployment if tables are empty.
     * Skips during PHPUnit testing to allow isolated test setups.
     */
    public function up(): void
    {
        if (app()->environment('testing')) {
            return;
        }

        try {
            if (ServiceCategory::count() === 0) {
                Artisan::call('db:seed', [
                    '--class' => 'Database\\Seeders\\DatabaseSeeder',
                    '--force' => true,
                ]);
            }
        } catch (\Throwable $e) {
            logger()->error('Auto-seed migration error: ' . $e->getMessage());
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Safe no-op to protect customer data
    }
};
