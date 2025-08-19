<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migration to create the `properties` table.
     *
     * Creates a `properties` table with the following columns:
     * - `id` (auto-incrementing primary key)
     * - `name` (string, required)
     * - `code` (string, unique)
     * - `address` (string, nullable)
     * - `created_at` and `updated_at` timestamps
     */
    public function up(): void
    {
        Schema::create('properties', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('code')->unique();
            $table->string('address')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migration by dropping the "properties" table if it exists.
     *
     * This performs the rollback for the migration that creates the "properties" table.
     */
    public function down(): void
    {
        Schema::dropIfExists('properties');
    }
};
