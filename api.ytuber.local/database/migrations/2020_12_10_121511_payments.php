<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Payments extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payments',function (Blueprint $table){
            $table->bigIncrements('id');
            $table->integer('user_id');
            $table->string('system');
            $table->integer('payment_system_id');
            $table->string('type');
            $table->decimal('amount');
            $table->decimal('commission');
            $table->decimal('sum');
            $table->string('detail');
            $table->boolean('status');
            $table->date('time');
            $table->date('created');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('payments');
    }
}
