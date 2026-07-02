<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('bill_items', function (Blueprint $table) {
            $table->foreignId('service_charge_id')->nullable()
                ->after('bill_id')
                ->constrained('bill_service_charges')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('bill_items', function (Blueprint $table) {
            $table->dropForeign(['service_charge_id']);
            $table->dropColumn('service_charge_id');
        });
    }
};
