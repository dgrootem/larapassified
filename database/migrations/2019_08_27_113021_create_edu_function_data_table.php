<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEduFunctionDataTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('edu_function_data', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->timestamps();

            $table->bigInteger('employee_id')->unsigned();
            
            $table->bigInteger('educational_function_id')->unsigned();

            $table->foreign('employee_id')->references('id')->on('employees');
            
            $table->foreign('educational_function_id')->references('id')->on('educational_functions');

            
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('edu_function_data');
    }
}
