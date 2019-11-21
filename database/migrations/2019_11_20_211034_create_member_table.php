<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMemberTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('members', function (Blueprint $table) {
            $table->increments('id');

            $table->string('username', 20)->comment('用户账号,手机号');
            $table->string('nickname', 50)->comment('用户昵称');
            $table->string('password', 255)->comment('用户密码');
            $table->integer('integral')->default(0)->comment('用户积分');
            $table->integer('questions_num')->default(0)->comment('答题次数');

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('members');
    }
}
