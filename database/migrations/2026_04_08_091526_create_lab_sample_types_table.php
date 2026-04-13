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
        Schema::create('lab_sample_types', function (Blueprint $table) {
            $table->id();
            $table->string('name');                         // Blood, Urine, Stool
            $table->string('code')->unique();               // SMP-BLD
            $table->string('container_type')->nullable();   // Vacutainer, Cup, Swab
            $table->string('color_code')->nullable();       // Red, Purple, Yellow
            $table->integer('volume_required')->nullable(); // in ml
            $table->boolean('requires_fasting')->default(false);
            $table->text('collection_instructions')->nullable();
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
        Schema::dropIfExists('lab_sample_types');
    }
};
