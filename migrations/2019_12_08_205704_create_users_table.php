<?php

use Hyperf\Database\Schema\Schema;
use Hyperf\Database\Schema\Blueprint;
use Hyperf\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->bigIncrements('user_id');
            $table->string('username', 50)->comment('用户名');
            $table->string('password', 100)->comment('密码');
            $table->string('nick_name', 50)->comment('昵称');
            $table->string('real_name', 10)->comment('真实姓名');
            $table->tinyInteger('sex')->default(0)->comment('性别 0未知 1男 2女');
            $table->string('phone', 15);
            $table->string('avatar', 100)->nullable()->comment('头像');
            $table->timestamp('last_login_at')->nullable();
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
            $table->string('remember_token', 100);
            $table->tinyInteger('status')->default(1)->comment('0为禁用，1为正常');
            //
            $table->unique('username');
            $table->unique('phone');
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
}
