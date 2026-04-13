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
        Schema::create('lab_tests', function (Blueprint $table) {
            $table->id();
            $table->string('name');                          // CBC, Blood Sugar, LFT
            $table->string('test_code')->unique();           // T-001
            $table->foreignId('category_id')
                ->constrained('lab_test_categories')
                ->cascadeOnDelete();

            // ── NEW: Sample type link ──
            $table->foreignId('sample_type_id')
                ->nullable()
                ->constrained('lab_sample_types')
                ->nullOnDelete();

            $table->decimal('price', 10, 2);
            $table->string('unit')->nullable();              // mg/dL, g/dL, %

            // ── Normal ranges — gender & age specific ──
            $table->string('normal_range')->nullable();           // General / fallback
            $table->string('normal_range_male')->nullable();      // Male specific
            $table->string('normal_range_female')->nullable();    // Female specific
            $table->string('normal_range_child')->nullable();     // Child (<12 yrs)
            $table->string('normal_range_elderly')->nullable();   // Elderly (>60 yrs)

            // ── NEW: Turnaround time ──
            $table->integer('turnaround_hours')->default(24);     // Result expected in hrs

            // ── NEW: Method used ──
            $table->string('method')->nullable();                 // ELISA, PCR, Microscopy

            $table->boolean('requires_fasting')->default(false);
            $table->boolean('is_active')->default(true);
            $table->text('description')->nullable();
            $table->timestamps();

            $table->index('test_code');
            $table->index('is_active');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lab_tests');
    }
};
