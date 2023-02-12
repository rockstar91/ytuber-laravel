<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class GeoToTask extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('go_to_task', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('country_id');
            $table->integer('region_id');
            $table->integer('city_id');
            $table->integer('task_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
       // Schema::table('go_to_task', function (Blueprint $table) {
            //
       // });
    }
}
