<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Create the `slots` database table.
     *
     * Defines the schema for scheduling slots including:
     * - `id` (primary key)
     * - `resource_id` (foreign key referencing `resources.id`, cascade on delete)
     * - `start_at` and `end_at` (datetimes)
     * - `is_available` (boolean, default true)
     * - `created_at` and `updated_at` timestamps
     */
    public function up(): void
    {
        Schema::create('slots', function (Blueprint $table) {
            $table->id();
            $table->foreignId('resource_id')->constrained()->cascadeOnDelete();
            $table->dateTime('start_at');
            $table->dateTime('end_at');
            $table->boolean('is_available')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migration by dropping the "slots" table if it exists.
     */
    public function down(): void
    {
        Schema::dropIfExists('slots');
    }
};
