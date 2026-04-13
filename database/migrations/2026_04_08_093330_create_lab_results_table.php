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
        Schema::create('lab_results', function (Blueprint $table) {
            $table->id();

            $table->foreignId('lab_order_item_id')
                ->constrained()
                ->cascadeOnDelete();

            // ── Result value ──
            $table->string('result_value')->nullable();           // 5.6, Positive, Negative
            $table->string('unit')->nullable();                   // mg/dL, g/dL
            $table->string('normal_range')->nullable();           // Copied at time of result

            $table->enum('flag', [
                'Normal',
                'High',
                'Low',
                'Critical High',
                'Critical Low',
            ])->nullable();

            // ── NEW: Abnormal quick check ──
            $table->boolean('is_abnormal')->default(false);

            // ── NEW: Previous result comparison ──
            $table->string('previous_value')->nullable();         // Last time ka value
            $table->date('previous_date')->nullable();            // Last test date

            // ── Remarks ──
            $table->text('remarks')->nullable();

            // ── Reporting ──
            $table->dateTime('reported_at')->nullable();

            // ── NEW: Verification (pathologist sign-off) ──
            $table->unsignedBigInteger('verified_by')->nullable(); // users table FK
            $table->timestamp('verified_at')->nullable();
            $table->boolean('is_verified')->default(false);

            $table->timestamps();

            $table->index('is_abnormal');
            $table->index('is_verified');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lab_results');
    }
};
