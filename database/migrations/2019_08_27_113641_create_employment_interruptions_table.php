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
            $table->integer('type')->default(1);

            $table->bigInteger('employee_id')->unsigned();

            $table->foreign('employee_id')->references('id')->on('employees');
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
