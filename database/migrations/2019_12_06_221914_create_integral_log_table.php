<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateIntegralLogTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('members_integral_log', function (Blueprint $table) {
            $table->increments('id');

            $table->integer('m_id')->unsigned()->comment('用户编号');
            $table->foreign('m_id')->references('id')->on('members');

            $table->integer('q_a_id')->unsigned()->comment('答题记录编号');
            $table->foreign('q_a_id')->references('id')->on('question_answer');

            $table->integer('q_o_id')->unsigned()->comment('答对的题目编号');
            $table->foreign('q_o_id')->references('id')->on('question_options');

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
        Schema::dropIfExists('member_integral_log');
    }
}
