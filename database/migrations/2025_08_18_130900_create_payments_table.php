<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migration to create the `payments` table.
     *
     * Creates the `payments` table with the following columns:
     * - `id` (auto-increment primary key)
     * - `invoice_id` (foreign key referencing `invoices`, cascade on delete)
     * - `method` (string)
     * - `amount` (decimal(10,2))
     * - `paid_at` (datetime)
     * - `reference` (nullable string)
     * - `created_at` and `updated_at` timestamps
     */
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('invoice_id')->constrained()->cascadeOnDelete();
            $table->string('method');
            $table->decimal('amount', 10, 2);
            $table->dateTime('paid_at');
            $table->string('reference')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migration by removing the `payments` table if it exists.
     *
     * This rolls back the migration created in `up()` by dropping the `payments`
     * table and any associated constraints.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
