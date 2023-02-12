<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Penalty extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('penaltys',function (Blueprint $table){
            $table->bigIncrements('id');
            $table->integer('user_id');
            $table->string('comment_id');
            $table->integer('task_id');
            $table->text('data');
            $table->decimal('cost');
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
        Schema::drop('penaltys');
    }
}
