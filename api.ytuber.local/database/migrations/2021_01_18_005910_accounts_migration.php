<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AccountsMigration extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('accounts', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('user_id');
            $table->integer('account_type_id');
            $table->string('url');
            $table->string('image')->nullable();
            $table->string('image_high')->nullable();
            $table->string('title')->nullable();
            $table->string('description')->nullable();
            $table->date('publishedAt')->nullable();
            $table->integer('viewCount')->nullable();
            $table->integer('videoCount')->nullable();
            $table->boolean('hiddenSubscriberCount')->default(false);
            $table->boolean('active');
            $table->timestamps();
            $table->date('deleted_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('accounts');
    }
}
