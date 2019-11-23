<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateQuestionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('question', function (Blueprint $table) {
            $table->increments('id');

            $table->string('title')->comment('题目');
            $table->integer('type')->default(1)->comment('类型 1判断题 2选择题');

            $table->integer('qc_id')->unsigned()->comment('所属期题');
            $table->foreign('qc_id')->references('id')->on('question_cycle');

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
        Schema::dropIfExists('question');
    }
}
