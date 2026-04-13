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
        Schema::create('medicines', function (Blueprint $table) {
            $table->id();
            $table->string('medicine_code')->unique();        // MED-00001
            $table->string('name');                           // Panadol
            $table->string('generic_name')->nullable();       // Paracetamol
            $table->string('brand')->nullable();              // GSK
            $table->enum('category', [
                'Tablet',
                'Capsule',
                'Syrup',
                'Injection',
                'Cream',
                'Drops',
                'Inhaler',
                'Powder',
                'Other'
            ])->default('Tablet');
            $table->string('unit')->default('Tablet');        // Tablet, ml, mg, strip
            $table->decimal('purchase_price', 10, 2)->default(0);
            $table->decimal('sale_price', 10, 2)->default(0);
            $table->integer('reorder_level')->default(10);    // Low stock alert threshold
            $table->integer('total_stock')->default(0);       // Auto calculated from batches
            $table->boolean('requires_prescription')->default(false);
            $table->string('storage_condition')->nullable();  // Room temp, Refrigerate
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();

            $table->index('name');
            $table->index('category');
            $table->index('is_active');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('medicines');
    }
};
