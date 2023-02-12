<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class GeoCity extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('geo_city', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('country_id');
            $table->integer('region_id');
            $table->string('name');
            $table->string('country_code');
            $table->string('region_code');
            $table->integer('population');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
       // Schema::drop('geo_city');
    }
}
