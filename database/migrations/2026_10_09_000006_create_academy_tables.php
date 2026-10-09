<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAcademyTables extends Migration
{
    public function up()
    {
        Schema::create('courses', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique();
            $table->string('title_fa');
            $table->string('title_en');
            $table->string('instructor_fa', 190)->nullable();
            $table->string('instructor_en', 190)->nullable();
            $table->text('summary_fa')->nullable();
            $table->text('summary_en')->nullable();
            $table->longText('description_fa')->nullable();
            $table->longText('description_en')->nullable();
            $table->string('category', 80)->nullable();
            $table->string('level', 30)->default('beginner');
            $table->unsignedInteger('duration_minutes')->nullable();
            $table->string('prerequisite_fa', 255)->nullable();
            $table->string('prerequisite_en', 255)->nullable();
            $table->unsignedBigInteger('price')->default(0);
            $table->boolean('is_free')->default(false);
            $table->boolean('is_published')->default(false);
            $table->text('cover_url')->nullable();
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
        });

        Schema::create('discount_codes', function (Blueprint $table) {
            $table->id();
            $table->string('code', 40)->unique();
            $table->string('type', 10)->default('percent');
            $table->unsignedInteger('value');
            $table->foreignId('course_id')->nullable()->constrained('courses')->cascadeOnDelete();
            $table->dateTime('expires_at')->nullable();
            $table->unsignedInteger('max_uses')->nullable();
            $table->unsignedInteger('used_count')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('lessons', function (Blueprint $table) {
            $table->id();
            $table->foreignId('course_id')->constrained('courses')->cascadeOnDelete();
            $table->string('title_fa');
            $table->string('title_en');
            $table->unsignedInteger('sort_order')->default(0);
            $table->string('source', 20)->default('none');
            $table->string('file_path')->nullable();
            $table->text('external_url')->nullable();
            $table->unsignedInteger('duration_seconds')->nullable();
            $table->boolean('is_free_preview')->default(false);
            $table->boolean('is_published')->default(true);
            $table->timestamps();
        });

        Schema::create('enrollments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('course_id')->constrained('courses')->cascadeOnDelete();
            $table->string('source', 20)->default('free');
            $table->foreignId('payment_id')->nullable()->constrained('payments')->nullOnDelete();
            $table->foreignId('last_lesson_id')->nullable()->constrained('lessons')->nullOnDelete();
            $table->dateTime('granted_at');
            $table->timestamps();
            $table->unique(['user_id', 'course_id']);
        });

        Schema::table('invoices', function (Blueprint $table) {
            $table->foreign('course_id')->references('id')->on('courses')->nullOnDelete();
        });

        Schema::create('lesson_progress', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('lesson_id')->constrained('lessons')->cascadeOnDelete();
            $table->dateTime('completed_at')->nullable();
            $table->unsignedInteger('last_position_seconds')->default(0);
            $table->timestamps();
            $table->unique(['user_id', 'lesson_id']);
        });
    }

    public function down()
    {
        Schema::table('invoices', function (Blueprint $table) {
            $table->dropForeign(['course_id']);
        });
        Schema::dropIfExists('lesson_progress');
        Schema::dropIfExists('enrollments');
        Schema::dropIfExists('lessons');
        Schema::dropIfExists('discount_codes');
        Schema::dropIfExists('courses');
    }
}
