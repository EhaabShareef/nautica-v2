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
        // Drop existing tables that conflict with our consolidated migrations
        // These will be recreated by the consolidated migrations
        
        $tablesToDrop = [
            'activities', 'payments', 'invoice_lines', 'invoices', 
            'contracts', 'booking_logs', 'bookings', 'vessels',
            'slots', 'zones', 'blocks', 'properties', 
            'app_types', 'settings'
        ];

        foreach ($tablesToDrop as $table) {
            if (Schema::hasTable($table)) {
                Schema::dropIfExists($table);
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // This migration cannot be reversed as it would lose data
        // The consolidated migrations that follow will recreate the tables
        throw new \Exception('Cannot reverse the table consolidation migration');
    }
};