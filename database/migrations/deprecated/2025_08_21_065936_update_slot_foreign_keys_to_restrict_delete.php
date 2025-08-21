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
        // Update bookings table - change slot_id FK to restrictOnDelete
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropForeign(['slot_id']);
            $table->foreign('slot_id')->references('id')->on('slots')->restrictOnDelete();
        });

        // Update contracts table - change slot_id FK to restrictOnDelete  
        Schema::table('contracts', function (Blueprint $table) {
            $table->dropForeign(['slot_id']);
            $table->foreign('slot_id')->references('id')->on('slots')->restrictOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Restore original cascadeOnDelete constraints
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropForeign(['slot_id']);
            $table->foreign('slot_id')->references('id')->on('slots')->cascadeOnDelete();
        });

        Schema::table('contracts', function (Blueprint $table) {
            $table->dropForeign(['slot_id']);
            $table->foreign('slot_id')->references('id')->on('slots')->cascadeOnDelete();
        });
    }
};
