<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AvailableClasses extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('available_classes', function (Blueprint $table) {
            $table->bigIncrements('id')->unique();
            $table->unsignedBigInteger('teacher_id');
            $table->unsignedBigInteger('subject_id');
            $table->unsignedBigInteger('class_type_id');
            $table->dateTime('from');
            $table->dateTime('to');
            $table->date('from_date');
            $table->integer('long')->comment('in minutes');
            $table->integer('company_percentage')->default(0)->comment('from 0 to 100');
            $table->integer('promoCode_percentage')->nullable();
            $table->text('note')->nullable();
            $table->float('cost');
            $table->integer('addition')->default(0);
            $table->tinyInteger('status')->default(1)->comment('1->active, 0-> un active, 2->class start, 3->finsh');
            $table->tinyInteger('teacher_mony')->default(0)->comment('0->teacher dont take mony, 1->teacher take mony');
            $table->string('agora_token')->nullable();
            $table->string('agora_rtm_token')->nullable();
            $table->string('channel_name')->nullable();
            $table->timestamps();

            //relations
            $table->foreign('teacher_id')->references('id')->on('teachers')->onDelete('cascade');
            $table->foreign('subject_id')->references('id')->on('subjects')->onDelete('cascade');
            $table->foreign('class_type_id')->references('id')->on('class_types')->onDelete('cascade');
        });

        Schema::create('student_class', function (Blueprint $table) {
            $table->unsignedBigInteger('student_id');
            $table->unsignedBigInteger('available_class_id');
            $table->dateTime('from')->nullable();
            $table->dateTime('to')->nullable();
            $table->unsignedBigInteger('promocode_id')->nullable();
            $table->integer('promocode_descount')->nullable();
            $table->tinyInteger('pay')->default(0)->comment('0->from balance, 1-> from free classes');
            $table->string('agora_rtm_token')->nullable();
            $table->tinyInteger('status')->default(0);

            //relations
            $table->foreign('student_id')->references('id')->on('students')->onDelete('cascade');
            $table->foreign('available_class_id')->references('id')->on('available_classes')->onDelete('cascade');
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
        Schema::dropIfExists('student_class');
    }
}
