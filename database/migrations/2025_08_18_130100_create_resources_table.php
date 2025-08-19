<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Create the `resources` database table.
     *
     * The table includes an auto-incrementing `id`, a `property_id` foreign key
     * (references the related properties table with cascade on delete), `name`
     * (string), `capacity` (unsigned integer, nullable), `attributes` (JSON, nullable),
     * and Laravel's `created_at` / `updated_at` timestamps.
     */
    public function up(): void
    {
        Schema::create('resources', function (Blueprint $table) {
            $table->id();
            $table->foreignId('property_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->unsignedInteger('capacity')->nullable();
            $table->json('attributes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migration by dropping the `resources` table if it exists.
     *
     * This method is used when rolling back the migration created in `up()`.
     */
    public function down(): void
    {
        Schema::dropIfExists('resources');
    }
};
