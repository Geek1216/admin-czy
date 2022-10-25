<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangePriceColumnToDoubleTypeInRedemptionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('redemptions', function (Blueprint $table) {
            $table->unsignedDecimal('amount', 16, 8)->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('redemptions', function (Blueprint $table) {
            $table->unsignedInteger('amount')->change();
        });
    }
}
