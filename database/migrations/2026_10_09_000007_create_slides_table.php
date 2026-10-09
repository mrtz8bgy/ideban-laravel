<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSlidesTable extends Migration
{
    public function up()
    {
        Schema::create('slides', function (Blueprint $table) {
            $table->id();
            $table->string('title_fa', 190);
            $table->string('title_en', 190);
            $table->string('subtitle_fa', 500)->nullable();
            $table->string('subtitle_en', 500)->nullable();
            $table->string('button_text_fa', 80)->nullable();
            $table->string('button_text_en', 80)->nullable();
            $table->string('button_url', 255)->nullable();
            $table->string('image_path', 255);
            $table->boolean('is_sample')->default(false);
            $table->boolean('is_active')->default(true);
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('slides');
    }
}
