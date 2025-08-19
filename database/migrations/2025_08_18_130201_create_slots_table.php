<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('slots', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('zone_id')->constrained()->cascadeOnDelete();
            $table->string('code');
            $table->string('location')->nullable();
            $table->decimal('max_loa_m', 5, 2)->nullable();
            $table->decimal('max_beam_m', 5, 2)->nullable();
            $table->decimal('max_draft_m', 5, 2)->nullable();
            $table->boolean('shore_power')->default(false);
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->unique(['zone_id', 'code']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('slots');
    }
};
