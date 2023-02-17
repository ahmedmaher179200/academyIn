<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class MainSubjects extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('main_subjects', function (Blueprint $table) {
            $table->bigIncrements('id')->unique();
            $table->tinyInteger('status')->default(1)->comment('1->active, 0-> un active');
            $table->timestamps();

        });

        Schema::create('main_subjects_translations', function(Blueprint $table) {
            $table->bigIncrements('id')->unique();
            $table->unsignedBigInteger('main_subject_id');
            $table->string('locale')->index();
            $table->string('name');
            $table->timestamps();
        
            $table->unique(['main_subject_id', 'locale']);
            $table->foreign('main_subject_id')->references('id')->on('main_subjects')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('main_subjects');
        Schema::dropIfExists('main_subjects_translations');

    }
}
