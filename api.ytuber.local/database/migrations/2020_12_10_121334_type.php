<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Type extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('type',function (Blueprint $table){
            $table->bigIncrements('id');
            $table->string('name');
            $table->string('name_english');
            $table->text('extend');
            $table->integer('hour_limit');
            $table->integer('complete_days');
            $table->integer('complete_waiting_timeout');
            $table->string('window_pattern');
            $table->string('noty_icon');
            $table->string('noty');
            $table->integer('order');
            $table->date('time');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('type');
    }
}
