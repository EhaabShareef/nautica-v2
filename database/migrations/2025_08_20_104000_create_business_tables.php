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
        // Vessels table
        Schema::create('vessels', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignId('owner_client_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('renter_client_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('name');
            $table->string('registration_number')->unique();
            $table->string('type')->nullable();
            $table->decimal('length', 8, 2)->nullable();
            $table->decimal('width', 8, 2)->nullable();
            $table->decimal('draft', 8, 2)->nullable();
            $table->json('specifications')->nullable();
            $table->boolean('is_active')->default(true);
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['owner_client_id', 'is_active']);
            $table->index('renter_client_id');
            $table->index('type');
        });

        // Bookings table (with restrictOnDelete for slots from the start)
        Schema::create('bookings', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('booking_number')->unique();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignUuid('vessel_id')->constrained()->cascadeOnDelete();
            $table->foreignUuid('slot_id')->constrained()->restrictOnDelete();
            $table->datetime('start_date');
            $table->datetime('end_date');
            $table->string('status')->default('pending');
            $table->decimal('total_amount', 10, 2)->default(0);
            $table->json('additional_data')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index(['user_id', 'status']);
            $table->index(['slot_id', 'start_date', 'end_date']);
        });

        // Booking Logs table
        Schema::create('booking_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignUuid('booking_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained();
            $table->string('action');
            $table->string('old_status')->nullable();
            $table->string('new_status')->nullable();
            $table->json('changes')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index('booking_id');
        });

        // Contracts table (with restrictOnDelete for slots from the start)
        Schema::create('contracts', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('contract_number')->unique();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignUuid('slot_id')->constrained()->restrictOnDelete();
            $table->date('start_date');
            $table->date('end_date');
            $table->string('status')->default('active');
            $table->decimal('monthly_rate', 10, 2);
            $table->json('terms')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index(['user_id', 'status']);
            $table->index(['slot_id', 'status']);
        });

        // Invoices table
        Schema::create('invoices', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('invoice_number')->unique();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->nullableUuidMorphs('invoiceable');
            $table->date('issue_date');
            $table->date('due_date');
            $table->string('status')->default('pending');
            $table->decimal('subtotal', 10, 2)->default(0);
            $table->decimal('tax_amount', 10, 2)->default(0);
            $table->decimal('total_amount', 10, 2)->default(0);
            $table->json('billing_details')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index(['user_id', 'status']);
            $table->index(['status', 'due_date']);
        });

        // Invoice Lines table
        Schema::create('invoice_lines', function (Blueprint $table) {
            $table->id();
            $table->foreignUuid('invoice_id')->constrained()->cascadeOnDelete();
            $table->string('description');
            $table->integer('quantity')->default(1);
            $table->decimal('unit_price', 10, 2);
            $table->decimal('total', 10, 2);
            $table->json('metadata')->nullable();
            $table->timestamps();

            $table->index('invoice_id');
        });

        // Payments table
        Schema::create('payments', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('payment_number')->unique();
            $table->foreignUuid('invoice_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->decimal('amount', 10, 2);
            $table->string('method')->default('card');
            $table->string('status')->default('pending');
            $table->datetime('processed_at')->nullable();
            $table->string('transaction_id')->nullable();
            $table->longText('gateway_response')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index(['invoice_id', 'status']);
            $table->index(['user_id', 'status']);
        });

        // Activities table
        Schema::create('activities', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('type');
            $table->string('description');
            $table->nullableUuidMorphs('subject');
            $table->json('properties')->nullable();
            $table->timestamp('occurred_at')->useCurrent();
            $table->timestamps();

            $table->index(['user_id', 'type']);
            $table->index('occurred_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('activities');
        Schema::dropIfExists('payments');
        Schema::dropIfExists('invoice_lines');
        Schema::dropIfExists('invoices');
        Schema::dropIfExists('contracts');
        Schema::dropIfExists('booking_logs');
        Schema::dropIfExists('bookings');
        Schema::dropIfExists('vessels');
    }
};