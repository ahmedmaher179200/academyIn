<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Settings extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->bigIncrements('id')->unique();
            $table->float('cost_students_number')->default(1);
            $table->float('cost_level')->default(1);
            $table->float('cost_country')->default(1);
            $table->float('cost_company_percentage')->default(1);
            $table->float('cost_year')->default(1);
            $table->float('video_company_percentage')->default(5);
            $table->float('live_company_percentage')->default(5);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('settings');
    }
}
