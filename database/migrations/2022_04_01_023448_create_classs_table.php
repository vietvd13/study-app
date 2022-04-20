<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClasssTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('classes', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->integer('level');
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('class_student', function (Blueprint $table) {
            $table->bigInteger('student_id')->unsigned();
            $table->bigInteger('class_id')->unsigned();
            $table->timestamps();

            $table->primary(['student_id', 'class_id']);

            $table->foreign('student_id')->references('id')->on('users')
            ->onDelete('cascade')
            ->onUpdate('cascade');

            $table->foreign('class_id')->references('id')->on('classes')
            ->onDelete('cascade')
            ->onUpdate('cascade');
        });

        Schema::create('class_course', function (Blueprint $table) {
            $table->bigInteger('class_id')->unsigned();
            $table->bigInteger('course_id')->unsigned();
            $table->bigInteger('teacher_id')->unsigned();
            $table->date('start_date');
            $table->date('end_date');
            $table->timestamps();

            $table->primary(['class_id', 'course_id']);

            $table->foreign('course_id')->references('id')->on('courses')
            ->onDelete('cascade')
            ->onUpdate('cascade');

            $table->foreign('class_id')->references('id')->on('classes')
            ->onDelete('cascade')
            ->onUpdate('cascade');

            $table->foreign('teacher_id')->references('id')->on('users')
            ->onDelete('cascade')
            ->onUpdate('cascade');
        });

        Schema::create('class_action', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('class_id')->unsigned();
            $table->bigInteger('teacher_id')->unsigned();
            $table->text('description');
            $table->text('name');
            $table->timestamps();

            $table->foreign('teacher_id')->references('id')->on('users')
            ->onDelete('cascade')
            ->onUpdate('cascade');

            $table->foreign('class_id')->references('id')->on('classes')
            ->onDelete('cascade')
            ->onUpdate('cascade');
        });

        Schema::create('student_handin', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('student_id')->unsigned();
            $table->bigInteger('action_id')->unsigned();
            $table->string('name');
            $table->string('file_path')->nullable();
            $table->text('description');
            $table->integer('grade')->nullable();
            $table->text('comment')->nullable();
            $table->timestamps();

            $table->foreign('action_id')->references('id')->on('class_action')
            ->onDelete('cascade')
            ->onUpdate('cascade');

            $table->foreign('student_id')->references('id')->on('users')
            ->onDelete('cascade')
            ->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('classs');
        Schema::dropIfExists('class_student');
        Schema::dropIfExists('class_course');
    }
}
