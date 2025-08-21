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
        Schema::table('settings', function (Blueprint $table) {
            $table->string('group')->nullable()->after('key');
            $table->string('label')->nullable()->after('value');
            $table->text('description')->nullable()->after('label');
            $table->boolean('is_protected')->default(false)->after('description');
            $table->boolean('is_active')->default(true)->after('is_protected');

            $table->index(['group', 'is_active']);
            $table->index('is_protected');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('settings', function (Blueprint $table) {
            $table->dropIndex(['group', 'is_active']);
            $table->dropIndex(['is_protected']);

            $table->dropColumn(['group', 'label', 'description', 'is_protected', 'is_active']);
        });
    }
};
