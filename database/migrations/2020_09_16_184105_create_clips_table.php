<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClipsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('clips', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('song_id')->nullable();
            $table->tinyInteger('media_type');
            $table->string('video');
            $table->string('screenshot');
            $table->string('preview');
            $table->text('description')->nullable();
            $table->char('language', 3);
            $table->boolean('private');
            $table->boolean('comments');
            $table->unsignedSmallInteger('duration');
            $table->timestamps();
            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('song_id')->references('id')->on('songs');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('clips');
    }
}
