<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Countries extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('countries', function (Blueprint $table) {
            $table->bigIncrements('id')->unique();
            $table->string('dialing_code');
            $table->tinyInteger('status')->default(1)->comment('1->active, 0-> un active');
            $table->timestamps();
        });

        Schema::create('countries_translations', function(Blueprint $table) {
            $table->bigIncrements('id')->unique();
            $table->unsignedBigInteger('country_id');
            $table->string('locale')->index();
            $table->string('name');
            $table->timestamps();
        
            $table->unique(['country_id', 'locale']);
            $table->foreign('country_id')->references('id')->on('countries')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('countries');
        Schema::dropIfExists('countries_translations');
    }
}
