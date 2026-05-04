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
        Schema::create('blood_crossmatches', function (Blueprint $table) {
            $table->id();

            $table->string('crossmatch_id')->unique();          // CRM-00001

            $table->foreignId('blood_request_id')
                ->constrained('blood_requests')
                ->cascadeOnDelete();

            $table->foreignId('blood_donation_id')
                ->constrained('blood_donations')
                ->cascadeOnDelete();

            $table->enum('result', [
                'Pending',
                'Compatible',
                'Incompatible',
            ])->default('Pending');

            $table->enum('method', [
                'Immediate Spin',
                'AHG',
                'Electronic',
                'Saline',
            ])->default('Immediate Spin');

            $table->timestamp('performed_at')->nullable();

            $table->foreignId('performed_by')
                ->nullable()
                ->constrained('employees')
                ->nullOnDelete();

            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index('blood_request_id');
            $table->index('result');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('blood_crossmatches');
    }
};
