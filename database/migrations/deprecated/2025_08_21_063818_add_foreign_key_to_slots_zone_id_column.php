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
        Schema::table('slots', function (Blueprint $table) {
            // Drop the existing foreign key constraint with cascadeOnDelete
            $table->dropForeign(['zone_id']);
            
            // Add the foreign key constraint with restrictOnDelete
            $table->foreign('zone_id')->references('id')->on('zones')->restrictOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('slots', function (Blueprint $table) {
            // Drop the restrictOnDelete foreign key constraint
            $table->dropForeign(['zone_id']);
            
            // Restore the original cascadeOnDelete constraint
            $table->foreign('zone_id')->references('id')->on('zones')->cascadeOnDelete();
        });
    }
};
