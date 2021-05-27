<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRatingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ratings', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->timestamps();
            $table->bigInteger('rating_type_id')->unsigned();;
            $table->bigInteger('edu_function_data_id')->unsigned();;
            $table->integer('positief');
            $table->date('datum');

            $table->foreign('rating_type_id')->references('id')->on('rating_types');
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
        Schema::dropIfExists('ratings');
    }
}
