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
        Schema::create('radiology_modalities', function (Blueprint $table) {
            $table->id();
            $table->string('name');                         // X-Ray, CT Scan, MRI, Ultrasound, PET, Mammography
            $table->string('code')->unique();               // MOD-XRAY, MOD-CT
            $table->string('description')->nullable();
            $table->boolean('requires_contrast')->default(false);
            $table->boolean('requires_preparation')->default(false);
            $table->text('preparation_instructions')->nullable(); // Fasting, bowel prep, etc.
            $table->integer('average_duration_minutes')->default(30);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('radiology_modalities');
    }
};
