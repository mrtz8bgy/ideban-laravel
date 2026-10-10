<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('resumes', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique();
            $table->string('name_fa');
            $table->string('name_en');
            $table->string('job_title_fa')->nullable();
            $table->string('job_title_en')->nullable();
            $table->text('bio_fa')->nullable();
            $table->text('bio_en')->nullable();
            $table->string('email')->nullable();
            $table->string('phone', 30)->nullable();
            $table->string('location_fa')->nullable();
            $table->string('location_en')->nullable();
            $table->string('photo_path')->nullable();
            $table->boolean('is_sample')->default(false);
            $table->boolean('is_published')->default(true)->index();
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
        });

        Schema::create('resume_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('resume_id')->constrained('resumes')->cascadeOnDelete();
            // education | experience | skill | certificate
            $table->string('type', 20)->index();
            $table->string('title_fa');
            $table->string('title_en');
            $table->string('organization_fa')->nullable();
            $table->string('organization_en')->nullable();
            $table->string('period', 60)->nullable();
            $table->text('description_fa')->nullable();
            $table->text('description_en')->nullable();
            $table->string('url')->nullable();
            // Skill proficiency 0-100 (used only for skills).
            $table->unsignedTinyInteger('level')->nullable();
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('resume_items');
        Schema::dropIfExists('resumes');
    }
};
