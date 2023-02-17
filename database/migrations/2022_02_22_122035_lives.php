<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Lives extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lives', function (Blueprint $table) {
            $table->bigIncrements('id')->unique();
            $table->unsignedBigInteger('teacher_id');
            $table->unsignedBigInteger('subject_id');
            $table->text('title')->nullable();
            $table->float('cost');
            $table->integer('company_percentage')->default(0)->comment('from 0 to 100');
            $table->dateTime('from');
            $table->string('agora_token')->nullable();
            $table->tinyInteger('status')->default(1);
            $table->timestamps();

            //relations
            $table->foreign('teacher_id')->references('id')->on('teachers')->onDelete('cascade');
            $table->foreign('subject_id')->references('id')->on('subjects')->onDelete('cascade');
        });

        Schema::create('student_live', function (Blueprint $table) {
            $table->unsignedBigInteger('live_id');
            $table->unsignedBigInteger('student_id');
            $table->integer('promocode_descount')->default(0);
            $table->tinyInteger('status');

            //relations
            $table->foreign('student_id')->references('id')->on('students')->onDelete('cascade');
            $table->foreign('live_id')->references('id')->on('lives')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('available_classes');
        Schema::dropIfExists('student_live');
    }
}
