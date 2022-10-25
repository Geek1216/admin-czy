<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class MakeIndexesToSpeedUpAggregation extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('devices', function (Blueprint $table) {
            $table->index('platform', 'devices_platform_index');
        });
        Schema::table('likes', function (Blueprint $table) {
            $table->index('created_at', 'likes_created_at_index');
        });
        Schema::table('views', function (Blueprint $table) {
            $table->index('viewed_at', 'views_viewed_at_index');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('devices', function (Blueprint $table) {
            $table->dropIndex('devices_platform_index');
        });
        Schema::table('likes', function (Blueprint $table) {
            $table->dropIndex('likes_created_at_index');
        });
        Schema::table('views', function (Blueprint $table) {
            $table->dropIndex('views_viewed_at_index');
        });
    }
}
