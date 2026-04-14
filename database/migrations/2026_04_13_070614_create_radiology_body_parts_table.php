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
        Schema::create('radiology_body_parts', function (Blueprint $table) {
            $table->id();
            $table->string('name');                         // Chest, Brain, Abdomen, Pelvis
            $table->string('code')->unique();               // BP-CHEST, BP-BRAIN
            $table->string('region')->nullable();           // Head & Neck, Thorax, Abdomen, Extremities, Spine
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('radiology_body_parts');
    }
};
