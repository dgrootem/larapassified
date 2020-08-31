<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class EfdAddArchived extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('edu_function_data', function($table) {
            $table->boolean('archived_final')->default(false);
            $table->boolean('archived_temporary')->default(false);
            $table->boolean('archived_auto')->default(false);
        });

        Schema::create('efd_archive_reason', function (Blueprint $table) {
            $table->unsignedBigInteger('efd_id');
            $table->timestamps();
            $table->unsignedBigInteger('settings_id');

            $table->foreign('settings_id')->references('id')->on('settings');            
            $table->foreign('efd_id')->references('id')->on('edu_function_data');
            $table->primary(['efd_id','settings_id']);

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
            $table->dropColumn('archived_final');
            $table->dropColumn('archived_temporary');
            $table->dropColumn('archived_auto');
        });

        Schema::dropIfExists('efd_archive_reason'); 
    }
}
