<?php

use Illuminate\Database\Seeder;

class RatingTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('rating_types')->insert([ 'omschrijving' => 'Werkpunten?',
                                            'omschrijving_lang' => 'Heeft werkpunten gekregen bij einde tijdelijke aanstelling?',
                                            'omschrijving_pos' => 'Geen werkpunten',
                                            'omschrijving_neg' => 'Wel werkpunten'
                                            ]);
        DB::table('rating_types')->insert([ 'omschrijving' => 'offic. beoordeling',
                                            'omschrijving_lang' => 'Uitgebreide beoordeling',
                                            'omschrijving_pos' => 'Positief beoordeeld',
                                            'omschrijving_neg' => 'Negatief beoordeeld']);
    }
}
