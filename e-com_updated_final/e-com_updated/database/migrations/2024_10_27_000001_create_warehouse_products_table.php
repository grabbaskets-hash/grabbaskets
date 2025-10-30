<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('warehouse_products', function (Blueprint $table) {
            $table->id();
            
            // Product relationship
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            
            // Stock management
            $table->integer('stock_quantity')->default(0);
            $table->integer('reserved_quantity')->default(0); // For pending orders
            $table->integer('available_quantity')->virtualAs('stock_quantity - reserved_quantity');
            $table->integer('minimum_stock_level')->default(5);
            $table->integer('maximum_stock_level')->default(100);
            
            // Physical location in warehouse
            $table->string('aisle')->nullable(); // A, B, C, etc.
            $table->string('rack')->nullable(); // 1, 2, 3, etc.
            $table->string('shelf')->nullable(); // A, B, C, etc.
            $table->string('location_code')->nullable(); // A1A, B2C, etc.
            
            // Product condition and expiry
            $table->date('expiry_date')->nullable();
            $table->integer('days_until_expiry')->nullable();
            $table->enum('condition', ['excellent', 'good', 'fair', 'damaged'])->default('excellent');
            
            // Pricing and costs
            $table->decimal('cost_price', 10, 2)->default(0);
            $table->decimal('selling_price', 10, 2)->default(0);
            $table->decimal('margin_amount', 10, 2)->default(0);
            $table->decimal('margin_percentage', 5, 2)->default(0);
            
            // Stock status and flags
            $table->boolean('is_available_for_quick_delivery')->default(true);
            $table->boolean('is_low_stock')->default(false);
            $table->boolean('is_expired')->default(false);
            $table->boolean('needs_reorder')->default(false);
            
            // Tracking information
            $table->string('supplier')->nullable();
            $table->string('batch_number')->nullable();
            $table->date('received_date')->nullable();
            $table->timestamp('last_updated_at')->nullable();
            $table->string('updated_by')->nullable(); // Staff member who updated
            
            // Delivery optimization
            $table->integer('weight_grams')->nullable(); // For delivery planning
            $table->enum('fragility', ['low', 'medium', 'high'])->default('low');
            $table->boolean('requires_cold_storage')->default(false);
            $table->text('handling_notes')->nullable();
            
            $table->timestamps();
            
            // Indexes for performance
            $table->index(['product_id', 'is_available_for_quick_delivery'], 'idx_product_quick_delivery');
            $table->index(['is_low_stock', 'needs_reorder'], 'idx_stock_alerts');
            $table->index(['expiry_date', 'is_expired'], 'idx_expiry_status');
            $table->index('location_code', 'idx_location_code');
            $table->index('aisle', 'idx_aisle');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('warehouse_products');
    }
};