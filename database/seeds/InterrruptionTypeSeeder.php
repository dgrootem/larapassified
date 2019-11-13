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
            'omschrijving' => 'Type 1',
            'telt_mee' => 1,
            'max_dat_telt' => 180,
            'max_dat_telt2' => 90
        ]);
        DB::table('interruption_types')->insert([
            'omschrijving' => 'Type 2',
            'telt_mee' => 0,
            'max_dat_telt' => 0,
            'max_dat_telt2' => 0

        ]);
    }
}
