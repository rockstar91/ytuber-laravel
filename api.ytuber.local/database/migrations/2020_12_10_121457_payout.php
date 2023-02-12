<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Payout extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payouts',function (Blueprint $table){
            $table->bigIncrements('id');
            $table->integer('user_id');
            $table->string('system');
            $table->string('account');
            $table->decimal('rub');
            $table->date('payed');
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
        Schema::drop('payouts');
    }
}
