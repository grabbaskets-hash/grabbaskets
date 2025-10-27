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
        Schema::create('warehouse_stock_movements', function (Blueprint $table) {
            $table->id();
            
            // References
            $table->foreignId('warehouse_product_id')->constrained()->cascadeOnDelete();
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->foreignId('order_id')->nullable()->constrained()->nullOnDelete();
            
            // Movement details
            $table->enum('movement_type', [
                'stock_in',           // New stock received
                'stock_out',          // Stock sold/dispatched
                'reserved',           // Stock reserved for order
                'released',           // Reserved stock released (cancelled order)
                'expired',            // Stock expired and removed
                'damaged',            // Stock damaged and removed
                'adjustment',         // Manual stock adjustment
                'transfer_out',       // Transferred to another warehouse
                'transfer_in',        // Received from another warehouse
                'returned'            // Customer return
            ]);
            
            $table->integer('quantity_before')->default(0);
            $table->integer('quantity_changed'); // Positive for IN, Negative for OUT
            $table->integer('quantity_after')->default(0);
            
            // Movement context
            $table->string('reason')->nullable();
            $table->text('notes')->nullable();
            $table->string('reference_number')->nullable(); // PO number, invoice, etc.
            
            // Staff tracking
            $table->string('performed_by'); // Staff member name/ID
            $table->string('approved_by')->nullable(); // Manager approval for adjustments
            
            // Supplier/Customer info (for stock in/returns)
            $table->string('supplier_name')->nullable();
            $table->string('customer_name')->nullable();
            
            // Financial tracking
            $table->decimal('unit_cost', 10, 2)->nullable();
            $table->decimal('total_value', 12, 2)->nullable();
            
            $table->timestamps();
            
            // Indexes
            $table->index(['warehouse_product_id', 'movement_type'], 'idx_wh_product_movement');
            $table->index(['product_id', 'created_at'], 'idx_product_created');
            $table->index(['movement_type', 'created_at'], 'idx_movement_created');
            $table->index('performed_by', 'idx_performed_by');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('warehouse_stock_movements');
    }
};