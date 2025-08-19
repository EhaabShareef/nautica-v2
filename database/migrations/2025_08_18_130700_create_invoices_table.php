<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Create the `invoices` database table.
     *
     * Defines columns and constraints:
     * - id: auto-increment primary key
     * - contract_id: nullable foreign key to `contracts.id`, set null on delete
     * - booking_id: nullable foreign key to `bookings.id`, set null on delete
     * - invoice_no: nullable string
     * - status: non-nullable string
     * - currency: 3-character string, default 'USD'
     * - total: decimal(10,2), default 0
     * - issued_at: nullable datetime
     * - due_at: nullable datetime
     * - created_at / updated_at timestamps
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('contract_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('booking_id')->nullable()->constrained()->nullOnDelete();
            $table->string('invoice_no')->nullable();
            $table->string('status');
            $table->string('currency', 3)->default('USD');
            $table->decimal('total', 10, 2)->default(0);
            $table->dateTime('issued_at')->nullable();
            $table->dateTime('due_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migration by dropping the `invoices` table if it exists.
     *
     * This removes the `invoices` table and any data it contains. Safe to run
     * even if the table is absent.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoices');
    }
};
