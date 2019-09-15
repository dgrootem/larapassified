<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEmploymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('employments', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->timestamps();
            $table->date('beginDate');
            $table->date('endDate');
            $table->integer('hours');

            $table->bigInteger('school_id')->unsigned();
            $table->bigInteger('edu_function_data_id')->unsigned();

            $table->foreign('school_id')->references('id')->on('schools');
            $table->foreign('edu_function_data_id')->references('id')->on('edu_function_data');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('employments');
    }
}
