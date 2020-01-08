<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class EfdAddStartwaardeIsbenoemd extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('edu_function_data', function($table) {
            $table->integer('startwaarde_tot')->default(0);
            //$table->integer('startwaarde_int')->default(0);
            $table->boolean('isBenoemd')->default(false);
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
            $table->dropColumn('startwaarde_tot');
            //$table->dropColumn('startwaarde_int');
            $table->dropColumn('isBenoemd');
        });
    }
}
