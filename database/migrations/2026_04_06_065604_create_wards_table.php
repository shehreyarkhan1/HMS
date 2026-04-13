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
        Schema::create('wards', function (Blueprint $table) {
            $table->id();
            $table->string('name');                          // General Ward, ICU, etc
            $table->string('ward_code')->unique();           // W-001
            $table->enum('type', [
                'General',
                'ICU',
                'CCU',
                'NICU',
                'Surgical',
                'Maternity',
                'Pediatric',
                'Orthopedic',
                'Private',
                'Semi-Private'
            ]);
            $table->integer('total_beds')->default(0);
            $table->string('floor')->nullable();             // Ground, 1st, 2nd
            $table->string('block')->nullable();             // A, B, C
            $table->decimal('bed_charges', 10, 2)->default(0); // Per day charges
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('wards');
    }
};
