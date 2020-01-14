<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class MoveOnderbrekingStartwaarde extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('edu_function_data', function($table) {
            $table->integer('startwaarde_int')->default(0);
        });
        Schema::table('employees', function($table) {
            $table->dropColumn('startwaardeINT');
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
            $table->dropColumn('startwaarde_int');
        });
        Schema::table('employees', function($table) {
            $table->integer('startwaardeINT')->default(0);
        });
    }
}
