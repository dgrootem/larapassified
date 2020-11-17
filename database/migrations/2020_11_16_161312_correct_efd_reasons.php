<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CorrectEfdReasons extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('efd_archive_reason');
        Schema::create('efd_archive_reasons', function (Blueprint $table) {
            $table->unsignedBigInteger('edu_function_data_id');
            $table->timestamps();
            $table->unsignedBigInteger('setting_id');

            $table->foreign('setting_id')->references('id')->on('settings');            
            $table->foreign('edu_function_data_id')->references('id')->on('edu_function_data');
            $table->primary(['edu_function_data_id','setting_id']);

        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // IRIVERSIBLE!!
        Schema::dropIfExists('efd_archive_reasons');

    }
}
