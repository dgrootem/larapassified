<?php

use Illuminate\Database\Seeder;

class InterrruptionTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        
        DB::table('interruption_types')->insert([
            'omschrijving' => 'Telt wel mee als effectief gepresteerd: zwangerschapsverlof, moederschapsbescherming, bedreigde beroepsziekte',
            'telt_mee' => 0,
            'max_dat_telt' => 0,
            'max_dat_telt2' => 0

        ]);
        DB::table('interruption_types')->insert([
            'omschrijving' => 'Telt niet mee als effectief gepresteerd',
            'telt_mee' => 1,
            'max_dat_telt' => 180,
            'max_dat_telt2' => 90
        ]);
        
    }
}
