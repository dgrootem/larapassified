<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class EfdAddComment extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('edu_function_data', function($table) {
            $table->string('comment')->nullable();
            $table->date('comment_modified')->nullable();
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
            $table->dropColumn('comment');
            $table->dropColumn('comment_modified');
        });
    }
}
