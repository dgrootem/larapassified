<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInterruptionTypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('interruption_types', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->timestamps();
            $table->string('omschrijving');
            // kan deze onderbreking meetellen ? 0 = nee, 1 = ja
            $table->smallInteger('telt_mee')->default(0);
            //0 = geen limiet, ander getal is aantal dagen in een onderbreking dat meetelt voor berekening
            $table->integer('max_dat_telt')->default(0);
            //max dat mee telt 'na werkpunten'
            $table->integer('max_dat_telt2')->default(0); 
            
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('interruption_types');
    }
}
