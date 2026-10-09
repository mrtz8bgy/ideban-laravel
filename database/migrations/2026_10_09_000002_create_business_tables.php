<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBusinessTables extends Migration
{
    public function up()
    {
        Schema::create('service_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name_fa');
            $table->string('name_en');
            $table->string('slug')->unique();
            $table->text('description_fa')->nullable();
            $table->text('description_en')->nullable();
            $table->unsignedInteger('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('services', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->nullable()->constrained('service_categories')->nullOnDelete();
            $table->string('slug')->unique();
            $table->string('title_fa');
            $table->string('title_en');
            $table->string('summary_fa', 500)->nullable();
            $table->string('summary_en', 500)->nullable();
            $table->text('description_fa')->nullable();
            $table->text('description_en')->nullable();
            $table->json('included_fa')->nullable();
            $table->json('included_en')->nullable();
            $table->json('excluded_fa')->nullable();
            $table->json('excluded_en')->nullable();
            $table->unsignedInteger('delivery_days')->nullable();
            $table->boolean('is_featured')->default(false);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('pricing_plans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('service_id')->nullable()->constrained()->nullOnDelete();
            $table->string('slug')->unique();
            $table->string('name_fa');
            $table->string('name_en');
            $table->text('description_fa')->nullable();
            $table->text('description_en')->nullable();
            $table->json('features_fa')->nullable();
            $table->json('features_en')->nullable();
            $table->unsignedBigInteger('setup_fee')->nullable();
            $table->unsignedBigInteger('recurring_fee')->nullable();
            $table->string('recurrence_fa', 80)->nullable();
            $table->string('recurrence_en', 80)->nullable();
            $table->string('price_type', 30)->default('quote');
            $table->date('valid_until')->nullable();
            $table->boolean('is_featured')->default(false);
            $table->boolean('is_active')->default(true);
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
        });

        Schema::create('service_prices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('service_id')->nullable()->constrained()->nullOnDelete();
            $table->string('title_fa');
            $table->string('title_en');
            $table->string('unit_fa', 100)->nullable();
            $table->string('unit_en', 100)->nullable();
            $table->unsignedBigInteger('amount')->nullable();
            $table->unsignedBigInteger('minimum_amount')->nullable();
            $table->unsignedBigInteger('maximum_amount')->nullable();
            $table->string('price_type', 30)->default('quote');
            $table->unsignedSmallInteger('tariff_year')->nullable();
            $table->string('source_name')->nullable();
            $table->text('source_url')->nullable();
            $table->boolean('is_verified')->default(false);
            $table->boolean('show_amount')->default(false);
            $table->boolean('is_active')->default(true);
            $table->date('valid_from')->nullable();
            $table->date('valid_until')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        Schema::create('portfolios', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique();
            $table->string('title_fa');
            $table->string('title_en');
            $table->string('client_name')->nullable();
            $table->text('challenge_fa')->nullable();
            $table->text('challenge_en')->nullable();
            $table->text('solution_fa')->nullable();
            $table->text('solution_en')->nullable();
            $table->text('result_fa')->nullable();
            $table->text('result_en')->nullable();
            $table->json('technologies')->nullable();
            $table->text('image_url')->nullable();
            $table->text('project_url')->nullable();
            $table->date('completed_at')->nullable();
            $table->boolean('is_published')->default(false);
            $table->timestamps();
        });

        Schema::create('leads', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('company')->nullable();
            $table->string('phone', 30);
            $table->string('email')->nullable();
            $table->foreignId('service_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('plan_id')->nullable()->constrained('pricing_plans')->nullOnDelete();
            $table->string('source', 100)->default('website');
            $table->text('message')->nullable();
            $table->string('stage', 30)->default('new');
            $table->foreignId('assigned_to')->nullable()->constrained('users')->nullOnDelete();
            $table->dateTime('follow_up_at')->nullable();
            $table->unsignedBigInteger('expected_value')->nullable();
            $table->text('sales_note')->nullable();
            $table->timestamps();
            $table->index(['stage', 'follow_up_at']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('leads');
        Schema::dropIfExists('portfolios');
        Schema::dropIfExists('service_prices');
        Schema::dropIfExists('pricing_plans');
        Schema::dropIfExists('services');
        Schema::dropIfExists('service_categories');
    }
}
