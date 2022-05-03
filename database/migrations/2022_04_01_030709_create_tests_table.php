<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tests', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('created_by')->unsigned();
            $table->bigInteger('course_id')->unsigned();
            $table->bigInteger('class_id')->unsigned();
            $table->integer('limit_time');
            $table->boolean('blind_support')->default(false);
            $table->string('test_name');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('created_by')->references('id')->on('users')
            ->onDelete('cascade')
            ->onUpdate('cascade');

            $table->foreign('course_id')->references('id')->on('courses')
            ->onDelete('cascade')
            ->onUpdate('cascade');

            $table->foreign('class_id')->references('id')->on('classes')
            ->onDelete('cascade')
            ->onUpdate('cascade');
        });

        Schema::create('test_question', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('test_id')->unsigned();
            $table->text('question');
            $table->string('question_audio')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('test_id')->references('id')->on('tests')
            ->onDelete('cascade')
            ->onUpdate('cascade');
        });

        Schema::create('question_anwser', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('question_id')->unsigned();
            $table->text('answer');
            $table->boolean('is_correct')->default(false);
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('question_id')->references('id')->on('test_question')
            ->onDelete('cascade')
            ->onUpdate('cascade');
        });

        Schema::create('answer_audio', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('answer_id')->unsigned();
            $table->string('audio_file_path');

            $table->foreign('answer_id')->references('id')->on('question_anwser')
            ->onDelete('cascade')
            ->onUpdate('cascade');
        });

        Schema::create('student_answer', function (Blueprint $table) {
            $table->bigInteger('student_id')->unsigned();
            $table->bigInteger('question_id')->unsigned();
            $table->bigInteger('answer_id')->unsigned();
            $table->datetime('created_at');
            $table->datetime('updated_at');
            $table->primary(['student_id', 'question_id', 'answer_id', 'created_at'], 'pk_student_test_answer');


            $table->foreign('student_id')->references('id')->on('users')
            ->onDelete('cascade')
            ->onUpdate('cascade');

            $table->foreign('question_id')->references('id')->on('test_question')
            ->onDelete('cascade')
            ->onUpdate('cascade');

            $table->foreign('answer_id')->references('id')->on('question_anwser')
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
        Schema::dropIfExists('tests');
    }
}
