<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migration to create the `contracts` table.
     *
     * Creates the `contracts` table with an auto-incrementing primary key, a
     * foreign key `booking_id` (cascades on delete) referencing `bookings.id`,
     * and columns for `status`, `effective_from`, optional `effective_to`,
     * optional JSON `terms_json`, `total` (decimal(10,2) default 0), and
     * `created_at`/`updated_at` timestamps.
     */
    public function up(): void
    {
        Schema::create('contracts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('booking_id')->constrained()->cascadeOnDelete();
            $table->string('status');
            $table->date('effective_from');
            $table->date('effective_to')->nullable();
            $table->json('terms_json')->nullable();
            $table->decimal('total', 10, 2)->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migration by dropping the `contracts` table if it exists.
     *
     * Removes the table created in up(), including its columns and foreign key constraints.
     */
    public function down(): void
    {
        Schema::dropIfExists('contracts');
    }
};
