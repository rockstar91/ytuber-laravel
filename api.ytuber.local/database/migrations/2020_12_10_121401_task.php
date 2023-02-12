<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Task extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tasks',function (Blueprint $table){
            $table->bigIncrements('id');
            $table->integer('user_id');
            $table->integer('category_id');
            $table->integer('type_id');
            $table->integer('report');
            $table->string('url');
            $table->string('name');
            $table->string('description');
            $table->decimal('total_cost');
            $table->decimal('action_cost');
            $table->integer('action_done');
            $table->integer('action_fail');
            $table->integer('action_refund');
            $table->integer('targetTime');
            $table->integer('hour_limit');
            $table->integer('hour_done');
            $table->integer('channelAgeLimit');
            $table->integer('videosCountLimit');
            $table->integer('youtube');
            $table->integer('youtube_initial');
            $table->text('youtube_extend');
            $table->longtext('extend');
            $table->decimal('order');
            $table->date('added');
            $table->date('updated');
            $table->date('completion');
            $table->boolean('geo')->default(0);
            $table->boolean('disabled')->default(0);
            $table->boolean('removed')->default(0);
            $table->date('deleted_at')->nulleble();
            $table->date('completed_at')->nulleble();
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
        Schema::drop('task');
    }
}
