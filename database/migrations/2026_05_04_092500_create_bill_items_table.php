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
        Schema::create('bill_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('bill_id')->constrained('bills')->onDelete('cascade');
            $table->enum('service_type', [
                'Consultation',
                'Lab',
                'Radiology',
                'Pharmacy',
                'Bed Charges',
                'OT Charges',
                'Blood Bank',
                'Death Certificate Fee',
                'Mortuary storage',
                'Service',
                'Other',
            ]);
            $table->string('description');
            // Polymorphic-style reference to source record
            $table->string('reference_type')->nullable(); // e.g. 'lab_orders', 'radiology_orders', 'dispensings'
            $table->unsignedBigInteger('reference_id')->nullable();
            $table->decimal('quantity', 8, 2)->default(1.00);
            $table->decimal('unit_price', 10, 2)->default(0.00);
            $table->decimal('discount', 10, 2)->default(0.00);
            $table->decimal('total_price', 10, 2)->default(0.00);
            $table->timestamps();

            $table->index('bill_id');
            $table->index(['reference_type', 'reference_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bill_items');
    }
};
