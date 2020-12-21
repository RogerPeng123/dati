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
        Schema::table('question_cycle', function (Blueprint $table) {
            $table->integer('class_type')->default(1)->comment('支持用户类别');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('question_cycle', function (Blueprint $table) {
            $table->dropColumn(['class_type']);
        });
    }
}
