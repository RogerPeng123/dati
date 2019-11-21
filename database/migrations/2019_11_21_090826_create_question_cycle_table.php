<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateQuestionCycleTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('question_cycle', function (Blueprint $table) {
            $table->increments('id');

            $table->string('title')->comment('标题');
            $table->integer('num')->default(0)->comment('题目数量');
            $table->integer('years')->comment('年期');
            $table->integer('months')->comment('月期');
            $table->integer('cycles')->default(1)->comment('当月期数');

            $table->integer('status')->default(0)->comment('0不显示 1显示');

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
        Schema::dropIfExists('question_cycle');
    }
}
