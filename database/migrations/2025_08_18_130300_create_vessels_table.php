<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Create the `vessels` database table.
     *
     * Creates columns:
     * - `id` (primary key)
     * - `client_id` (foreign key to `users.id`, cascades on delete)
     * - `name` (string)
     * - `length` (float, nullable)
     * - `width` (float, nullable)
     * - `attributes` (json, nullable)
     * - `created_at` and `updated_at` timestamps
     */
    public function up(): void
    {
        Schema::create('vessels', function (Blueprint $table) {
            $table->id();
            $table->foreignId('client_id')->constrained('users')->cascadeOnDelete();
            $table->string('name');
            $table->float('length')->nullable();
            $table->float('width')->nullable();
            $table->json('attributes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migration by dropping the 'vessels' table if it exists.
     *
     * Removes the table created by up(), including its columns and constraints.
     */
    public function down(): void
    {
        Schema::dropIfExists('vessels');
    }
};
