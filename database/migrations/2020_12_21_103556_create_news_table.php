<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNewsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('news', function (Blueprint $table) {
            $table->increments('id');

            $table->string('title', '100')->nullable(false)->comment('标题');
            $table->string('abstract', '100')->nullable(false)->comment('摘要');
            $table->text('content')->comment('内容');

            $table->integer('admin_id')->unsigned()->comment('管理员id');
            $table->foreign('admin_id')->references('id')->on('users');

            $table->integer('status')->default(0)->comment('0未发布 1发布');

            $table->integer('type')->comment('新闻类别');

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
        Schema::dropIfExists('news');
    }
}
