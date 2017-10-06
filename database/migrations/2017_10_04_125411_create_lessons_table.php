<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLessonsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lessons', function (Blueprint $table) {
            $table->increments('id')->index()->unique();
            $table->integer('day', False, True);
	        $table->integer('lesson_number_id')->unsigned();
	        $table->foreign('lesson_number_id')
	              ->references('id')->on('lesson_times')
	              ->onDelete('cascade');
	        $table->integer('group_id')->unsigned();
	        $table->foreign('group_id')
	              ->references('id')->on('groups')
	              ->onDelete('cascade');
            $table->string('classroom', 255)->nullable();
            $table->string('type', 255)->nullable();
            $table->string('teacher', 255)->nullable();
            $table->string('name', 255)->nullable();
            $table->boolean('is_odd');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('lessons');
    }
}
