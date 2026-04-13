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
        Schema::create('beds', function (Blueprint $table) {
            $table->id();
            $table->string('bed_number');
            $table->foreignId('ward_id')->constrained('wards')->cascadeOnDelete();
            $table->enum('type', ['Standard', 'Semi-Private', 'Private', 'ICU'])->default('Standard');
            $table->enum('status', ['Available', 'Occupied', 'Reserved', 'Maintenance'])->default('Available');
            $table->foreignId('patient_id')->nullable()->constrained('patients')->nullOnDelete();
            $table->date('admitted_at')->nullable();
            $table->date('discharged_at')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->unique(['bed_number', 'ward_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('beds');
    }
};
