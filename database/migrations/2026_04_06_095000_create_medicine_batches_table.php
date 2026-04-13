<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('medicine_batches', function (Blueprint $table) {
            $table->id();
            $table->foreignId('medicine_id')->constrained()->cascadeOnDelete();
            $table->string('batch_number');
            $table->date('expiry_date');
            $table->date('manufacture_date')->nullable();
            $table->integer('quantity_received');             // Original quantity
            $table->integer('quantity_in_stock');             // Current remaining
            $table->decimal('purchase_price', 10, 2);        // This batch price
            $table->string('supplier_name')->nullable();
            $table->string('supplier_invoice')->nullable();
            $table->enum('status', ['Active', 'Expired', 'Exhausted'])->default('Active');
            $table->timestamps();
            $table->unique(['medicine_id', 'batch_number']);
            $table->index('expiry_date');
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('medicine_batches');
    }
};
