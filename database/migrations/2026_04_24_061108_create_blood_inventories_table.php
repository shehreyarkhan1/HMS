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
        Schema::create('blood_inventories', function (Blueprint $table) {
            $table->id();

            $table->string('blood_group');                       // A+, A-, B+, B-, O+, O-, AB+, AB-
            $table->enum('component', [
                'Whole Blood',
                'Packed RBC',
                'Platelets',
                'Fresh Frozen Plasma',
                'Cryoprecipitate',
            ])->default('Whole Blood');

            $table->unsignedSmallInteger('units_available')->default(0);
            $table->unsignedSmallInteger('units_reserved')->default(0);  // Cross-matched / held
            $table->unsignedSmallInteger('minimum_threshold')->default(2); // Alert if below this

            $table->timestamp('last_updated_at')->nullable();

            $table->unique(['blood_group', 'component']);
            $table->index('blood_group');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('blood_inventories');
    }
};
