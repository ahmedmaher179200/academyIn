<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Curriculums extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('curriculums', function (Blueprint $table) {
            $table->bigIncrements('id')->unique();
            $table->unsignedBigInteger('country_id');
            $table->tinyInteger('status')->default(1)->comment('1->active, 0-> un active');
            $table->timestamps();

            //relations
            $table->foreign('country_id')->references('id')->on('countries')->onDelete('cascade');
        });

        Schema::create('curriculums_translations', function(Blueprint $table) {
            $table->bigIncrements('id')->unique();
            $table->unsignedBigInteger('curriculum_id');
            $table->string('locale')->index();
            $table->string('name');
            $table->timestamps();
        
            $table->unique(['curriculum_id', 'locale']);
            $table->foreign('curriculum_id')->references('id')->on('curriculums')->onDelete('cascade');
        });

        Schema::create('levels', function (Blueprint $table) {
            $table->bigIncrements('id')->unique();
            $table->unsignedBigInteger('curriculum_id');
            $table->tinyInteger('status')->default(1)->comment('1->active, 0-> un active');
            $table->timestamps();

            //relations
            $table->foreign('curriculum_id')->references('id')->on('curriculums')->onDelete('cascade');
        });

        Schema::create('levels_translations', function(Blueprint $table) {
            $table->bigIncrements('id')->unique();
            $table->unsignedBigInteger('level_id');
            $table->string('locale')->index();
            $table->string('name');
            $table->timestamps();
        
            $table->unique(['level_id', 'locale']);
            $table->foreign('level_id')->references('id')->on('levels')->onDelete('cascade');
        });

        Schema::create('years', function (Blueprint $table) {
            $table->bigIncrements('id')->unique();
            $table->unsignedBigInteger('level_id');
            $table->tinyInteger('status')->default(1)->comment('1->active, 0-> un active');
            $table->timestamps();

            //relations
            $table->foreign('level_id')->references('id')->on('levels')->onDelete('cascade');
        });

        Schema::create('years_translations', function(Blueprint $table) {
            $table->bigIncrements('id')->unique();
            $table->unsignedBigInteger('year_id');
            $table->string('locale')->index();
            $table->string('name');
            $table->timestamps();
        
            $table->unique(['year_id', 'locale']);
            $table->foreign('year_id')->references('id')->on('years')->onDelete('cascade');
        });

        Schema::create('terms', function (Blueprint $table) {
            $table->bigIncrements('id')->unique();
            $table->unsignedBigInteger('year_id');
            $table->tinyInteger('status')->default(1)->comment('1->active, 0-> un active');
            $table->timestamps();

            //relations
            $table->foreign('year_id')->references('id')->on('years')->onDelete('cascade');
        });

        Schema::create('terms_translations', function(Blueprint $table) {
            $table->bigIncrements('id')->unique();
            $table->unsignedBigInteger('term_id');
            $table->string('locale')->index();
            $table->string('name');
            $table->timestamps();
        
            $table->unique(['term_id', 'locale']);
            $table->foreign('term_id')->references('id')->on('terms')->onDelete('cascade');
        });

        Schema::create('subjects', function (Blueprint $table) {
            $table->bigIncrements('id')->unique();
            $table->unsignedBigInteger('term_id');
            $table->unsignedBigInteger('main_subject_id');
            $table->integer('order_by')->default(0);
            $table->tinyInteger('status')->default(1)->comment('1->active, 0-> un active');
            $table->timestamps();

            //relations
            $table->foreign('main_subject_id')->references('id')->on('main_subjects')->onDelete('cascade');
            $table->foreign('term_id')->references('id')->on('terms')->onDelete('cascade');
        });

        Schema::create('materials', function (Blueprint $table) {
            $table->bigIncrements('id')->unique();
            $table->unsignedBigInteger('subject_id');
            $table->tinyInteger('status')->default(1)->comment('1->active, 0-> un active');
            $table->timestamps();

            //relations
            $table->foreign('subject_id')->references('id')->on('subjects')->onDelete('cascade');
        });

        Schema::create('materials_translations', function(Blueprint $table) {
            $table->bigIncrements('id')->unique();
            $table->unsignedBigInteger('material_id');
            $table->string('locale')->index();
            $table->string('name');
            $table->timestamps();
        
            $table->unique(['material_id', 'locale']);
            $table->foreign('material_id')->references('id')->on('materials')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('curriculums');
        Schema::dropIfExists('curriculums_translations');
        Schema::dropIfExists('levels');
        Schema::dropIfExists('levels_translations');
        Schema::dropIfExists('years');
        Schema::dropIfExists('years_translations');
        Schema::dropIfExists('terms');
        Schema::dropIfExists('terms_translations');
        Schema::dropIfExists('subjects');
        Schema::dropIfExists('subjects_translations');
        Schema::dropIfExists('materials');
        Schema::dropIfExists('materials_translations');
    }
}
