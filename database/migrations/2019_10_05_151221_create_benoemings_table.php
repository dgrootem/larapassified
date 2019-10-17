<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBenoemingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('benoemings', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->timestamps();
            $table->bigInteger('school_id')->unsigned();
            $table->bigInteger('edu_function_data_id')->unsigned();
            $table->integer('hours'); // aantal uren waarin iemand is benoemd

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
        Schema::dropIfExists('benoemings');
    }
}
