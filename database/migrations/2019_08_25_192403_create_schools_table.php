<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSchoolsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('schools', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->timestamps();
            $table->string('name');
            $table->string('abbreviation');
            // make theses fields nullable so they don't have to be filled in
            $table->string('adres')->nullable();
            $table->integer('postcode')->nullable();
            $table->string('logo_filename')->nullable();
            $table->string('gemeente')->nullable();
            $table->bigInteger('school_type_id')->unsigned();
            $table->boolean('isActive')->default(1); //deactivate school, so it cannot be selected
            $table->boolean('useForCalculations')->default(1); //if this is 0, days in this schoo should not be counted towards TADD

            $table->foreign('school_type_id')->references('id')->on('school_types');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('schools');
    }
}
