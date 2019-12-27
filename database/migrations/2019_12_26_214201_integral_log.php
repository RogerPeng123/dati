<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class IntegralLog extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('intrgral_log', function (Blueprint $table) {
            $table->increments('id');

            $table->integer('m_id')->unsigned()->comment('用户表');
            $table->foreign('m_id')->references('id')->on('members');

            $table->integer('type')->default(1)->comment('1登录积分 2阅读文章 3题库自测 4题库学习 5收藏');

            $table->integer('num')->default(0)->comment('获取的积分数量');

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
        Schema::dropIfExists('intrgral_log');
    }
}
