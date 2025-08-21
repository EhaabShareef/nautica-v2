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
        Schema::create('app_types', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('group');
            $table->string('code');
            $table->string('label');
            $table->json('extra')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->unique(['group', 'code']);
            $table->index('group');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('app_types');
    }
};
