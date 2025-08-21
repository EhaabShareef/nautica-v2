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
        // Settings table with all enhancements
        Schema::create('settings', function (Blueprint $table) {
            $table->string('key')->primary();
            $table->string('group')->nullable();
            $table->json('value');
            $table->string('label')->nullable();
            $table->text('description')->nullable();
            $table->boolean('is_protected')->default(false);
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index(['group', 'is_active']);
            $table->index('is_protected');
        });

        // App Types table with all enhancements
        Schema::create('app_types', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('group');
            $table->string('code');
            $table->string('label');
            $table->text('description')->nullable();
            $table->integer('sort_order')->default(0);
            $table->json('extra')->nullable();
            $table->boolean('is_active')->default(true);
            $table->boolean('is_protected')->default(false);
            $table->timestamps();

            $table->unique(['group', 'code']);
            $table->index('group');
            $table->index(['group', 'is_active', 'sort_order']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('app_types');
        Schema::dropIfExists('settings');
    }
};