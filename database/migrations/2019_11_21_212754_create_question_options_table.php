<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateQuestionOptionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('question_options', function (Blueprint $table) {
            $table->increments('id');

            $table->integer('q_id')->unsigned()->comment('问题编号');
            $table->foreign('q_id')->references('id')->on('question');

            $table->text('content')->comment('答案内容');
            $table->integer('is_success')->default(0)->comment('是否正确 1正确 0错误');

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
        Schema::dropIfExists('question_options');
    }
}
