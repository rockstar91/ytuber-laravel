<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Done extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('completions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('user_id');
            $table->integer('task_id');
            $table->integer('type_id');
            $table->binary('ip_bin');
            $table->decimal('action_cost');
            $table->integer('cost_rule');
            $table->integer('youtube');
            $table->integer('time');
            $table->integer('check_count');
            $table->integer('check_time');
            $table->integer('expires');
            $table->integer('timeout');
            $table->integer('data');
            $table->integer('domain');
            $table->boolean('status');
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
        Schema::drop('comment');
    }
}
