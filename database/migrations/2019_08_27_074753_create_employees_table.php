<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEmployeesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('employees', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->timestamps();
            $table->string('firstName');
            $table->string('lastName');
            $table->string('registrationNumber')->nullable();
            $table->date('birthDate')->nullable();
            $table->date('lastEmploymentDate')->default(null)->nullable();
            $table->boolean('isActive')->default(true);
            $table->integer('startwaardeDA')->default(0);
            $table->integer('startwaardeINT')->default(0);
            $table->boolean('oudsysteem')->default(0); //aan te vinken wanneer iemand nog moet geteld worden volgens de regelgeving tem 2018-2019.
            //$table->boolean('telVersie')->default(1);
            
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('employees');
    }
}
