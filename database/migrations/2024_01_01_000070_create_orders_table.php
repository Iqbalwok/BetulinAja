<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_number', 30)->unique();
            $table->foreignId('customer_id')->constrained('users');
            $table->foreignId('partner_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('service_id')->constrained('services');
            $table->foreignId('address_id')->nullable()->constrained('addresses')->nullOnDelete();
            $table->text('problem_description');
            $table->text('address_text')->nullable();
            $table->string('landmark')->nullable();
            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();
            $table->timestamp('scheduled_at')->nullable();
            $table->enum('assignment_method', ['auto', 'manual'])->default('auto');
            $table->enum('status', [
                'pending',
                'searching_technician',
                'assigned',
                'accepted',
                'on_the_way',
                'arrived',
                'in_progress',
                'awaiting_customer_confirmation',
                'completed',
                'cancelled'
            ])->default('pending');
            // Price snapshot at order creation
            $table->decimal('base_price', 12, 2)->default(0);
            $table->decimal('distance_km', 8, 2)->default(0);
            $table->decimal('travel_fee', 10, 2)->default(0);
            $table->enum('difficulty_level', ['light', 'medium', 'heavy'])->default('medium');
            $table->decimal('difficulty_fee', 10, 2)->default(0);
            $table->decimal('additional_fee', 10, 2)->default(0);
            $table->decimal('total_estimate', 12, 2)->default(0);
            $table->decimal('final_price', 12, 2)->nullable();
            $table->decimal('platform_commission', 10, 2)->nullable();
            $table->decimal('partner_earning', 10, 2)->nullable();
            $table->string('cancellation_reason')->nullable();
            $table->enum('cancelled_by', ['customer', 'partner', 'admin'])->nullable();
            $table->timestamp('accepted_at')->nullable();
            $table->timestamp('started_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['customer_id', 'status']);
            $table->index(['partner_id', 'status']);
            $table->index('status');
            $table->index('order_number');
        });
    }

    public function down(): void {
        Schema::dropIfExists('orders');
    }
};
