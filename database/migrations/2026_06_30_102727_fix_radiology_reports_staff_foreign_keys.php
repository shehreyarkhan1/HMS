<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('radiology_reports', function (Blueprint $table) {
            // Purane doctor foreign keys drop karo
            $table->dropForeign(['reported_by']);
            $table->dropForeign(['verified_by']);
            $table->dropForeign(['amended_by']);

            // Users table se link karo
            $table->foreign('reported_by')->references('id')->on('users')->nullOnDelete();
            $table->foreign('verified_by')->references('id')->on('users')->nullOnDelete();
            $table->foreign('amended_by')->references('id')->on('users')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('radiology_reports', function (Blueprint $table) {
            $table->dropForeign(['reported_by']);
            $table->dropForeign(['verified_by']);
            $table->dropForeign(['amended_by']);

            $table->foreign('reported_by')->references('id')->on('doctors')->nullOnDelete();
            $table->foreign('verified_by')->references('id')->on('doctors')->nullOnDelete();
            $table->foreign('amended_by')->references('id')->on('doctors')->nullOnDelete();
        });
    }
};
