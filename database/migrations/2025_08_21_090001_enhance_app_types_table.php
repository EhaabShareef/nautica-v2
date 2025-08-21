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
        Schema::table('app_types', function (Blueprint $table) {
            $table->text('description')->nullable()->after('label');
            $table->integer('sort_order')->default(0)->after('description');
            $table->boolean('is_protected')->default(false)->after('is_active');

            $table->index(['group', 'is_active', 'sort_order']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('app_types', function (Blueprint $table) {
            $table->dropIndex(['group', 'is_active', 'sort_order']);

            $table->dropColumn(['description', 'sort_order', 'is_protected']);
        });
    }
};
