<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddStandarNumForCycle extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('question_cycle', function (Blueprint $table) {
            $table->integer('standar_num')->default(0)->common('达标人数');
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
            $table->dropColumn(['standar_num']);
        });
    }
}
