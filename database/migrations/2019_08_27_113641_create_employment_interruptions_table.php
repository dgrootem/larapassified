<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEmploymentInterruptionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('employment_interruptions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->timestamps();
            $table->date('beginDate');
            $table->date('endDate');
            $table->bigInteger('interruption_type_id')->unsigned()->default(1);

            $table->bigInteger('employee_id')->unsigned();

            $table->foreign('employee_id')->references('id')->on('employees');
            $table->foreign('interruption_type_id')->references('id')->on('interruption_types');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('employment_interruptions');
    }
}
