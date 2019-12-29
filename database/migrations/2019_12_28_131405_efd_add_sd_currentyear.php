<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class EfdAddSdCurrentyear extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('edu_function_data', function($table) {
            $table->integer('seniority_days_currentyear')->default(0);
            $table->date('seniority_days_calculated_on')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('edu_function_data', function($table) {
            $table->dropColumn('seniority_days_currentyear');
            $table->dropColumn('seniority_days_calculated_on');
        });
    }
}
