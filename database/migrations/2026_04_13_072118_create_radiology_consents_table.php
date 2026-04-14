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
        Schema::create('radiology_consents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('radiology_order_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->string('consent_type');                 // Contrast, Radiation, General
            $table->boolean('is_signed')->default(false);
            $table->string('signed_by')->nullable();        // Patient / guardian name
            $table->string('relationship')->nullable();     // Self, Parent, Guardian
            $table->timestamp('signed_at')->nullable();
            $table->string('witness')->nullable();
            $table->string('signature_path')->nullable();   // Digital signature image
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('radiology_consents');
    }
};
