<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Notifications extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('student_notifications', function(Blueprint $table) {
            $table->bigIncrements('id')->unique();
            $table->unsignedBigInteger('student_id');
            $table->unsignedBigInteger('teacher_id')->nullable();
            $table->unsignedBigInteger('answer_id')->nullable();
            $table->unsignedBigInteger('available_class_id')->nullable();
            $table->string('title')->nullable();
            $table->text('content')->nullable();
            $table->integer('type')->nullable()->comment('1->student book class ,2->canel class ,3->join class, 4-> some one answer');
            $table->text('agora_token')->nullable();
            $table->text('agora_rtm_token')->nullable();
            $table->text('agora_channel_name')->nullable();
            $table->tinyInteger('seen')->default(0);
            $table->timestamps();

            //relations
            $table->foreign('student_id')->references('id')->on('students')->onDelete('cascade');
            $table->foreign('teacher_id')->references('id')->on('teachers')->onDelete('cascade');
            $table->foreign('answer_id')->references('id')->on('answers')->onDelete('cascade');
            $table->foreign('available_class_id')->references('id')->on('available_classes')->onDelete('cascade');
        });

        Schema::create('teacher_notifications', function(Blueprint $table) {
            $table->bigIncrements('id')->unique();
            $table->unsignedBigInteger('student_id')->nullable();
            $table->unsignedBigInteger('teacher_id');
            $table->unsignedBigInteger('answer_id')->nullable();
            $table->unsignedBigInteger('available_class_id')->nullable();
            $table->string('title')->nullable();
            $table->text('content')->nullable();
            $table->integer('type')->nullable()->comment('1->join class, 2->canel class');
            $table->text('agora_token')->nullable();
            $table->text('agora_rtm_token')->nullable();
            $table->text('agora_channel_name')->nullable();
            $table->tinyInteger('seen')->default(0);
            $table->timestamps();

            //relations
            $table->foreign('teacher_id')->references('id')->on('teachers')->onDelete('cascade');
            $table->foreign('student_id')->references('id')->on('students')->onDelete('cascade');
            $table->foreign('available_class_id')->references('id')->on('available_classes')->onDelete('cascade');
            $table->foreign('answer_id')->references('id')->on('answers')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('student_notifications');
        Schema::dropIfExists('teacher_notifications');

    }
}
