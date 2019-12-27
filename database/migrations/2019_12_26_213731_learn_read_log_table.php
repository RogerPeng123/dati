<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class LearnReadLogTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('learn_read_log', function (Blueprint $table) {
            $table->increments('id');

            $table->integer('learn_id')->unsigned()->comment('知识点文章编号');
            $table->foreign('learn_id')->references('id')->on('learns');

            $table->integer('m_id')->unsigned()->comment('用户表');
            $table->foreign('m_id')->references('id')->on('members');

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
        Schema::dropIfExists('learn_read_log');
    }
}
