<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Backfill existing slots with location data
        DB::transaction(function () {
            // Update all slots with NULL location to have a default value
            // Use zone name + slot code as a reasonable default
            DB::statement("
                UPDATE slots 
                SET location = CONCAT(
                    (SELECT CONCAT(zones.name, ' - ') FROM zones WHERE zones.id = slots.zone_id),
                    slots.code
                )
                WHERE location IS NULL
            ");
        });

        // Now make the column NOT NULL and add index
        Schema::table('slots', function (Blueprint $table) {
            $table->string('location')->nullable(false)->change();
            $table->index('location'); // Add index for location searches
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('slots', function (Blueprint $table) {
            // Drop the index first
            $table->dropIndex(['location']);
            
            // Make column nullable again
            $table->string('location')->nullable()->change();
        });
        
        // Optionally clear the backfilled data if needed
        // DB::table('slots')->update(['location' => null]);
    }
};
