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
        Schema::create('radiology_exams', function (Blueprint $table) {
            $table->id();
            $table->string('name');                         // Chest X-Ray PA View, HRCT Chest, MRI Brain with Contrast
            $table->string('exam_code')->unique();          // RAD-001
            $table->foreignId('modality_id')
                ->constrained('radiology_modalities')
                ->restrictOnDelete();
            $table->foreignId('body_part_id')
                ->nullable()
                ->constrained('radiology_body_parts')
                ->nullOnDelete();

            $table->decimal('price', 10, 2);
            $table->boolean('requires_contrast')->default(false);
            $table->string('contrast_type')->nullable();    // Gadolinium, Iodine, Barium
            $table->boolean('requires_preparation')->default(false);
            $table->text('preparation_instructions')->nullable();
            $table->integer('turnaround_hours')->default(24); // Report expected within
            $table->integer('duration_minutes')->default(30); // Scan duration
            $table->text('clinical_indications')->nullable(); // When to order
            $table->text('contraindications')->nullable();    // Pacemaker, pregnancy, allergy
            $table->boolean('requires_consent')->default(false);
            $table->boolean('is_active')->default(true);
            $table->text('description')->nullable();
            $table->timestamps();

            $table->index('exam_code');
            $table->index(['modality_id', 'is_active']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('radiology_exams');
    }
};
