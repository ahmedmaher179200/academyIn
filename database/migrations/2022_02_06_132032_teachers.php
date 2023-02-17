<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Teachers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('teachers', function (Blueprint $table) {
            $table->bigIncrements('id')->unique();
            $table->string('username');
            $table->string('email')->unique()->nullable();
            $table->string('dialing_code')->nullable();
            $table->string('phone')->unique();
            $table->string('password');
            $table->boolean('verified')->comment('0 ->not verified, 1 -> verified')->default(0);
            $table->boolean('status')->comment('1->active, 0->bloked')->default(1);
            $table->tinyInteger('gender')->comment('1->male, 0 ->female')->default(0);
            $table->date('birth')->nullable();
            $table->unsignedBigInteger('main_subject_id');
            $table->unsignedBigInteger('country_id');
            $table->unsignedBigInteger('curriculum_id')->nullable();
            $table->float('balance')->default(0);
            $table->boolean('online')->comment('1->online, 0 ->not')->default(0);
            $table->text('about')->nullable();
            $table->tinyInteger('type')->default(0)->comment('0->, 1-> Special');
            $table->string('token_firebase')->nullable();
            $table->timestamps();

            //relations
            $table->foreign('country_id')->references('id')->on('countries')->onDelete('cascade');
            $table->foreign('curriculum_id')->references('id')->on('curriculums')->onDelete('cascade');
            $table->foreign('main_subject_id')->references('id')->on('main_subjects')->onDelete('cascade');
        });

        Schema::create('teacher_verified', function (Blueprint $table) {
            $table->string('phone')->index();
            $table->integer('code');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('teacher_password_resets', function (Blueprint $table) {
            $table->string('phone')->index();
            $table->integer('code');
            $table->timestamp('created_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('teachers');
        Schema::dropIfExists('teacher_verified');
        Schema::dropIfExists('teacher_password_resets');
    }
}
