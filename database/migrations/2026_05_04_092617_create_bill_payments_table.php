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
        Schema::create('bill_payments', function (Blueprint $table) {
            $table->id();
            $table->string('payment_number')->unique();
            $table->foreignId('bill_id')->constrained('bills')->onDelete('cascade');
            $table->foreignId('received_by')->nullable()->constrained('users')->onDelete('set null');
            $table->decimal('amount', 12, 2);
            $table->enum('payment_method', [
                'Cash',
                'Card',
                'Bank Transfer',
                'Cheque',
                'Insurance',
                'Online'
            ])->default('Cash');
            $table->date('payment_date');
            $table->string('reference_number')->nullable(); // cheque no / transaction id
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index('bill_id');
            $table->index('payment_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bill_payments');
    }
};
