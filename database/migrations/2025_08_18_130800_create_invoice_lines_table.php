<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Create the `invoice_lines` database table.
     *
     * The table stores individual line items for invoices and includes:
     * - `id` (PK)
     * - `invoice_id` (foreign key to `invoices`, cascades on delete)
     * - `description`
     * - `qty` (unsigned integer)
     * - `unit_price` (decimal(10,2))
     * - `tax_rate` (decimal(5,2), default 0)
     * - `amount` (decimal(10,2))
     * - `created_at` and `updated_at` timestamps
     */
    public function up(): void
    {
        Schema::create('invoice_lines', function (Blueprint $table) {
            $table->id();
            $table->foreignId('invoice_id')->constrained()->cascadeOnDelete();
            $table->string('description');
            $table->unsignedInteger('qty');
            $table->decimal('unit_price', 10, 2);
            $table->decimal('tax_rate', 5, 2)->default(0);
            $table->decimal('amount', 10, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migration by dropping the `invoice_lines` table if it exists.
     *
     * Called when rolling back the migration; safely removes the table created in up().
     */
    public function down(): void
    {
        Schema::dropIfExists('invoice_lines');
    }
};
