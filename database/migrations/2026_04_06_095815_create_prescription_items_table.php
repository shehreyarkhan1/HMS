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
        Schema::create('prescription_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('prescription_id')->constrained()->cascadeOnDelete();
            $table->foreignId('medicine_id')->constrained()->cascadeOnDelete();
            $table->string('dosage');                         // 500mg, 10ml
            $table->string('frequency');                      // 1-0-1, TDS, BD
            $table->integer('duration_days')->default(1);
            $table->integer('quantity');                      // Total qty to dispense
            $table->integer('dispensed_qty')->default(0);     // How much dispensed
            $table->string('instructions')->nullable();        // After meal, before sleep
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('prescription_items');
    }
};
