<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class GeoCountry extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('geo_country', function(Blueprint $table){
            $table->bigIncrements('id');
            $table->string('name');
            $table->string('name_english');
            $table->string('iso_2');
            $table->string('iso_3');
            $table->integer('iso_n');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('geo_country');
    }
}
