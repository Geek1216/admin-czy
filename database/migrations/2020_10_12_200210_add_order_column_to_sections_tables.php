<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddOrderColumnToSectionsTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('article_sections', function (Blueprint $table) {
            $table->unsignedInteger('order');
        });
        Schema::table('clip_sections', function (Blueprint $table) {
            $table->unsignedInteger('order');
        });
        Schema::table('song_sections', function (Blueprint $table) {
            $table->unsignedInteger('order');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('article_sections', function (Blueprint $table) {
            $table->dropColumn('order');
        });
        Schema::table('clip_sections', function (Blueprint $table) {
            $table->dropColumn('order');
        });
        Schema::table('song_sections', function (Blueprint $table) {
            $table->dropColumn('order');
        });
    }
}
