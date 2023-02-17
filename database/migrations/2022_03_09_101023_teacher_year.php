<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class TeacherYear extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('teacher_year', function (Blueprint $table) {
            $table->unsignedBigInteger('year_id');
            $table->unsignedBigInteger('teacher_id');
            $table->timestamps();

            $table->unique(['year_id', 'teacher_id']);
            //relations
            $table->foreign('year_id')->references('id')->on('years')->onDelete('cascade');
            $table->foreign('teacher_id')->references('id')->on('teachers')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('teacher_year');
    }
}
