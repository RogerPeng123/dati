<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateQuestionAnswerTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('question_answer', function (Blueprint $table) {
            $table->increments('id');

            $table->integer('m_id')->unsigned()->comment('用户标示');
            $table->foreign('m_id')->references('id')->on('members');

            $table->integer('qc_id')->unsigned()->comment('题期标示');
            $table->foreign('qc_id')->references('id')->on('question_cycle');

            $table->integer('success_questions')->default(0)->comment('正确题目数量');
            $table->integer('errors_questions')->default(0)->comment('错误题目数量');
            $table->decimal('correct',8,2)->default(0)->comment('正确率');

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
        Schema::dropIfExists('question_answer');
    }
}
