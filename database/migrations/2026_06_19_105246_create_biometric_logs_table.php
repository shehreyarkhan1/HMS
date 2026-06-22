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
        Schema::create('biometric_logs', function (Blueprint $table) {
            $table->id();
            $table->string('machine_serial')->index();
            $table->string('enroll_number')->index();
            $table->foreignId('employee_id')->nullable()->constrained('employees')->nullOnDelete();
            $table->timestamp('punch_time')->index();
            $table->tinyInteger('in_out_mode')->default(0)->comment('0=check-in, 1=check-out, 4=break');
            $table->tinyInteger('verify_mode')->default(1)->comment('1=finger, 4=card, 15=face');
            $table->boolean('is_processed')->default(false)->index();
            $table->string('error_note')->nullable();
            $table->timestamps();

            // Prevent duplicate punches from same machine at same time
            $table->unique(['enroll_number', 'punch_time', 'machine_serial'], 'unique_punch');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('biometric_logs');
    }
};
