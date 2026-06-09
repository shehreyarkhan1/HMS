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
        Schema::create('leave_balances', function (Blueprint $table) {
             $table->id();

            $table->foreignId('employee_id')->constrained()->cascadeOnDelete();
            $table->foreignId('leave_type_id')->constrained()->cascadeOnDelete();
            $table->year('year');

            $table->decimal('entitled_days', 5, 1)->default(0);   // Total allowed
            $table->decimal('used_days', 5, 1)->default(0);        // Used so far
            $table->decimal('pending_days', 5, 1)->default(0);     // Pending approval
            $table->decimal('remaining_days', 5, 1)->default(0);   // Available
            $table->decimal('carried_forward', 5, 1)->default(0);  // From last year
            $table->decimal('encashed_days', 5, 1)->default(0);    // Encashed

            $table->timestamps();
            $table->unique(['employee_id', 'leave_type_id', 'year']);
            $table->index(['employee_id', 'year']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('leave_balances');
    }
};
