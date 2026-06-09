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
        Schema::create('holidays', function (Blueprint $table) {
            $table->id();

            $table->string('name');                          // Eid ul Fitr
            $table->date('date');
            $table->date('date_to')->nullable();             // Multi-day holiday
            $table->integer('total_days')->default(1);
            $table->enum('type', [
                'Public Holiday',
                'National Holiday',
                'Religious Holiday',
                'Hospital Holiday',
                'Optional',
            ])->default('Public Holiday');
            $table->year('year');
            $table->boolean('is_recurring')->default(false); // Every year same date
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index(['year', 'date']);
            $table->index('date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('holidays');
    }
};
