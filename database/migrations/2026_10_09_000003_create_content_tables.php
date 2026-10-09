<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateContentTables extends Migration
{
    public function up()
    {
        Schema::create('articles', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique();
            $table->string('title_fa');
            $table->string('title_en');
            $table->string('excerpt_fa', 500)->nullable();
            $table->string('excerpt_en', 500)->nullable();
            $table->longText('body_fa')->nullable();
            $table->longText('body_en')->nullable();
            $table->string('category', 80)->nullable();
            $table->json('tags')->nullable();
            $table->text('cover_url')->nullable();
            $table->string('author_name', 120)->nullable();
            $table->foreignId('service_id')->nullable()->constrained('services')->nullOnDelete();
            $table->string('meta_title', 190)->nullable();
            $table->string('meta_description', 320)->nullable();
            $table->boolean('is_published')->default(false);
            $table->dateTime('published_at')->nullable();
            $table->timestamps();
            $table->index(['is_published', 'published_at']);
        });

        Schema::create('videos', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique();
            $table->string('title_fa');
            $table->string('title_en');
            $table->text('description_fa')->nullable();
            $table->text('description_en')->nullable();
            $table->text('video_url');
            $table->text('thumbnail_url')->nullable();
            $table->unsignedInteger('duration_seconds')->nullable();
            $table->string('category', 80)->nullable();
            $table->json('tags')->nullable();
            $table->foreignId('service_id')->nullable()->constrained('services')->nullOnDelete();
            $table->boolean('is_published')->default(false);
            $table->dateTime('published_at')->nullable();
            $table->timestamps();
            $table->index(['is_published', 'published_at']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('videos');
        Schema::dropIfExists('articles');
    }
}
