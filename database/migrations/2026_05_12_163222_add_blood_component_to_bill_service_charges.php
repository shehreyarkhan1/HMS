<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('bill_service_charges', function (Blueprint $table) {
            $table->string('blood_component')->nullable()->after('category');
            // Exact values: 'Whole Blood', 'Packed RBC', 'Platelets', 
            //               'Fresh Frozen Plasma', 'Cryoprecipitate'
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bill_service_charges', function (Blueprint $table) {
            //
        });
    }
};
