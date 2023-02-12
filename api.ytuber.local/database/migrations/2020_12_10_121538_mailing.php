<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Mailing extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mailing', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('subject');
            $table->longText('body');
            $table->boolean('admin');
            $table->date('date');
            $table->integer('sended');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('mailing', function (Blueprint $table) {
            //
        });
    }
}
