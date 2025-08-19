<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Create the `bookings` database table.
     *
     * The table includes:
     * - id (primary key)
     * - client_id -> users (cascade on delete)
     * - vessel_id -> vessels (nullable, set null on delete)
     * - property_id -> properties (cascade on delete)
     * - resource_id -> resources (nullable, set null on delete)
     * - slot_id -> slots (nullable, set null on delete)
     * - start_at, end_at (dateTime)
     * - status (string)
     * - type, priority (nullable strings)
     * - hold_expires_at (nullable dateTime)
     * - notes, admin_notes (nullable text)
     * - timestamps (created_at, updated_at)
     */
    public function up(): void
    {
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('client_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('vessel_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('property_id')->constrained()->cascadeOnDelete();
            $table->foreignId('resource_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('slot_id')->nullable()->constrained()->nullOnDelete();
            $table->dateTime('start_at');
            $table->dateTime('end_at');
            $table->string('status');
            $table->string('type')->nullable();
            $table->string('priority')->nullable();
            $table->dateTime('hold_expires_at')->nullable();
            $table->text('notes')->nullable();
            $table->text('admin_notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migration by dropping the `bookings` table if it exists.
     *
     * Removes the table created in up(), allowing this migration to be rolled back safely.
     */
    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
