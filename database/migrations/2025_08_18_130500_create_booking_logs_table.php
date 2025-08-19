<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Create the `booking_logs` table.
     *
     * The table includes:
     * - `id`: auto-increment primary key.
     * - `booking_id`: foreign key to `bookings.id`, cascades on delete.
     * - `status`: string.
     * - `notes`: nullable text.
     * - `created_at`: timestamp defaulting to the current time.
     */
    public function up(): void
    {
        Schema::create('booking_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('booking_id')->constrained()->cascadeOnDelete();
            $table->string('status');
            $table->text('notes')->nullable();
            $table->timestamp('created_at')->useCurrent();
        });
    }

    /**
     * Reverse the migration by dropping the `booking_logs` table if it exists.
     *
     * This removes the table from the database; safe to run when the table is not present.
     */
    public function down(): void
    {
        Schema::dropIfExists('booking_logs');
    }
};
