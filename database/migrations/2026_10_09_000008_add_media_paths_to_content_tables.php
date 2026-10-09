<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddMediaPathsToContentTables extends Migration
{
    public function up()
    {
        foreach ([
            'service_categories' => ['media_path'],
            'services' => ['media_path'],
            'pricing_plans' => ['media_path'],
            'service_addons' => ['media_path'],
            'portfolios' => ['media_path'],
            'articles' => ['cover_path'],
            'videos' => ['thumbnail_path', 'video_path'],
            'courses' => ['cover_path'],
            'lessons' => ['thumbnail_path'],
        ] as $tableName => $columns) {
            Schema::table($tableName, function (Blueprint $table) use ($columns) {
                foreach ($columns as $column) {
                    $table->string($column, 500)->nullable();
                }
            });
        }
    }

    public function down()
    {
        foreach ([
            'service_categories' => ['media_path'],
            'services' => ['media_path'],
            'pricing_plans' => ['media_path'],
            'service_addons' => ['media_path'],
            'portfolios' => ['media_path'],
            'articles' => ['cover_path'],
            'videos' => ['thumbnail_path', 'video_path'],
            'courses' => ['cover_path'],
            'lessons' => ['thumbnail_path'],
        ] as $tableName => $columns) {
            Schema::table($tableName, function (Blueprint $table) use ($columns) {
                $table->dropColumn($columns);
            });
        }
    }
}
