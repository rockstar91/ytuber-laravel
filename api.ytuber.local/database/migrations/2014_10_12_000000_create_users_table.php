<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('banned')->default(0);
            $table->string('ban_reason')->default(0);
            $table->string('gid')->nulleble();
            $table->string('refresh_token')->nulleble();
            $table->string('activate_hash')->nulleble();
            $table->string('avatar')->nulleble();
            $table->boolean('admin')->default(false);
            $table->boolean('moderator')->default(false);
            $table->string('username');
            $table->string('email');
            $table->string('password');
            $table->string('password_hash');
            $table->string('reset_hash');
            $table->string('forgot_token');
            $table->integer('referrer_id')->default(0);
            $table->string('channel')->nulleble();
            $table->date('channel_published')->nulleble();
            $table->boolean('channel_available')->default(false);
            $table->decimal('balance')->nulleble();
            $table->decimal('earned')->nulleble();
            $table->decimal('rub')->nulleble();
            $table->string('confirm')->nulleble();
            $table->boolean('confirm_sended')->nulleble();
            $table->integer('done')->nulleble();
            $table->integer('done_day')->nulleble();
            $table->integer('done_confirmed')->nulleble();
            $table->boolean('allow_transaction')->default(true);
            $table->string('api_key')->nulleble();
            $table->boolean('sub_news')->default(true);
            $table->boolean('sub_transaction')->default(true);
            $table->boolean('sub_statistic')->default(true);
            $table->boolean('sub_notification')->default(true);
            $table->string('soc_vk')->default(true);
            $table->string('soc_twitter')->default(true);
            $table->string('soc_fb')->default(true);
            $table->integer('domain')->default(true);
            $table->string('ip')->default(true);
            $table->ipAddress('lastip')->default(null);
            $table->integer('last_mailing_id')->default(true);
            $table->boolean('daily_bonus_received')->default(false);
            $table->integer('daily_bonus_count')->default(0);
            $table->float('recaptcha_score')->default(0);
            $table->date('time')->default(null);
            $table->date('reset_at')->default(null);
            $table->date('reset_expires')->default(null);
            $table->date('lastseen')->default(null);
            $table->integer('activity')->default(0);
            $table->date('activity_updated_at')->default(null);
            $table->date('test_time')->default(null);
            $table->boolean('test_status')->default(0);
            $table->boolean('disabled')->default(0);
            $table->integer('status')->default(1);
            $table->boolean('active')->default(true);
            $table->string('status_message')->default('active');
            $table->string('force_pass_reset')->nulleble();
            $table->rememberToken();
            $table->timestamps();
            $table->date('deleted_at')->nulleble();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('users');
    }
}
