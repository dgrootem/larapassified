<?php

use Illuminate\Database\Seeder;

class SchoolTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        DB::table('school_types')->insert([
            'naam' => 'BaO'
        ]);
        DB::table('school_types')->insert([
            'naam' => 'BuO'
        ]);
        DB::table('school_types')->insert([
            'naam' => 'Special'
        ]);
    }
}
