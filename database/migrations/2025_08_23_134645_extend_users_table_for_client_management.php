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
        Schema::table('users', function (Blueprint $table) {
            $table->enum('user_type', ['admin', 'client'])->default('client')->after('email');
            $table->string('phone', 20)->nullable()->after('email_verified_at');
            $table->string('id_card', 50)->nullable()->unique()->after('phone');
            $table->text('address')->nullable()->after('id_card');
            $table->boolean('is_active')->default(true)->after('address');
            $table->timestamp('last_login_at')->nullable()->after('is_active');
            
            // Add indexes for better performance
            $table->index(['user_type', 'is_active']);
            $table->index('id_card');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropIndex(['user_type', 'is_active']);
            $table->dropIndex(['id_card']);
            $table->dropColumn([
                'user_type', 'phone', 'id_card', 'address', 'is_active', 'last_login_at'
            ]);
        });
    }
};