<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Offices extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('offices', function (Blueprint $table) {
            $table->bigIncrements('id')->unique();
            $table->string('username');
            $table->string('email')->unique()->nullable();
            $table->string('dialing_code')->nullable();
            $table->string('phone')->unique();
            $table->string('password');
            $table->boolean('verified')->comment('0 ->not verified, 1 -> verified')->default(0);
            $table->boolean('status')->comment('1->active, 0->bloked')->default(1);
            $table->unsignedBigInteger('country_id')->nullable();
            $table->float('balance')->default(0);
            $table->timestamps();
        });
        Schema::create('office_teacher', function (Blueprint $table) {
            $table->bigIncrements('id')->unique();
            $table->unsignedBigInteger('office_id');
            $table->unsignedBigInteger('teacher_id');
            $table->timestamps();

            $table->unique(['office_id', 'teacher_id']);
            //relations
            $table->foreign('office_id')->references('id')->on('offices')->onDelete('cascade');
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
        Schema::dropIfExists('offices');
        Schema::dropIfExists('office_teacher');
    }
}
