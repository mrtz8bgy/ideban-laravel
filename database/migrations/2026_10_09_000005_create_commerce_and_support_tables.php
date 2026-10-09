<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCommerceAndSupportTables extends Migration
{
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('phone', 30)->nullable()->after('email');
            $table->string('company', 190)->nullable()->after('phone');
        });

        Schema::create('service_addons', function (Blueprint $table) {
            $table->id();
            $table->foreignId('service_id')->nullable()->constrained('services')->nullOnDelete();
            $table->string('name_fa');
            $table->string('name_en');
            $table->text('description_fa')->nullable();
            $table->text('description_en')->nullable();
            $table->unsignedBigInteger('amount')->nullable();
            $table->string('price_type', 30)->default('quote');
            $table->boolean('is_active')->default(true);
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
        });

        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('reference', 20)->unique();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('lead_id')->nullable()->constrained('leads')->nullOnDelete();
            $table->foreignId('service_id')->nullable()->constrained('services')->nullOnDelete();
            $table->foreignId('plan_id')->nullable()->constrained('pricing_plans')->nullOnDelete();
            $table->string('status', 30)->default('requested');
            $table->unsignedTinyInteger('progress_percent')->default(0);
            $table->text('customer_note')->nullable();
            $table->text('staff_note')->nullable();
            $table->json('addon_ids')->nullable();
            $table->unsignedBigInteger('estimate_setup')->nullable();
            $table->unsignedBigInteger('estimate_recurring')->nullable();
            $table->timestamps();
            $table->index(['status', 'created_at']);
        });

        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->string('number', 30)->unique();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('order_id')->nullable()->constrained('orders')->nullOnDelete();
            // Foreign key to courses is added in the academy migration, which runs after courses exists.
            $table->unsignedBigInteger('course_id')->nullable()->index();
            $table->string('type', 20)->default('invoice');
            $table->string('status', 20)->default('issued');
            $table->json('items')->nullable();
            $table->unsignedBigInteger('subtotal')->default(0);
            $table->unsignedBigInteger('discount')->default(0);
            $table->unsignedBigInteger('extra_costs')->default(0);
            $table->unsignedBigInteger('total')->default(0);
            $table->date('valid_until')->nullable();
            $table->dateTime('paid_at')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('invoice_id')->constrained('invoices')->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->unsignedBigInteger('amount');
            $table->string('method', 30);
            $table->string('gateway', 30)->nullable();
            $table->string('status', 20)->default('pending');
            $table->string('authority', 120)->nullable()->unique();
            $table->string('reference_id', 120)->nullable();
            $table->string('receipt_path')->nullable();
            $table->dateTime('paid_at')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        Schema::create('tickets', function (Blueprint $table) {
            $table->id();
            $table->string('reference', 20)->unique();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->string('subject', 190);
            $table->string('category', 50)->default('general');
            $table->string('priority', 20)->default('normal');
            $table->string('status', 20)->default('open');
            $table->foreignId('assigned_to')->nullable()->constrained('users')->nullOnDelete();
            $table->dateTime('first_response_at')->nullable();
            $table->dateTime('closed_at')->nullable();
            $table->dateTime('last_reply_at')->nullable();
            $table->timestamps();
            $table->index(['status', 'created_at']);
        });

        Schema::create('ticket_messages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ticket_id')->constrained('tickets')->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->text('body');
            $table->string('attachment_path')->nullable();
            $table->string('attachment_name', 190)->nullable();
            $table->boolean('is_staff')->default(false);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('ticket_messages');
        Schema::dropIfExists('tickets');
        Schema::dropIfExists('payments');
        Schema::dropIfExists('invoices');
        Schema::dropIfExists('orders');
        Schema::dropIfExists('service_addons');
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['phone', 'company']);
        });
    }
}
