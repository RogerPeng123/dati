<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSpecialForQuestionCycleTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('question_cycle', function (Blueprint $table) {
            $table->integer('special')->default(1)->comment('1普通 2专项');
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
            $table->dropColumn(['special']);
        });
    }
}
