<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAdvertisementsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('advertisements', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('location');
            $table->string('type');
            $table->string('network');
            $table->string('unit')->nullable();
            $table->string('image')->nullable();
            $table->string('link')->nullable();
            $table->unsignedSmallInteger('interval')->nullable();
            $table->timestamps();
            $table->unique(['location', 'type'], 'advertisements_location_type_unique');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('advertisements');
    }
}
