<?php

use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            'name' => 'test',
            'email' => 'test@test.com',
            'password' => '$2y$10$20UE6r4yyimSXvgBsaFHJuG95YozoAZp/3PySrEO51IzW31qfL/we',
            'isadmin' => true,
            'isActive' => true

        ]);
        DB::table('users')->insert([
            'name' => 'test2',
            'email' => 'test2@test.com',
            'password' => '$2y$10$n/Fve1z3x/qclkW5/Lt/Jem5.J/AMDDicJrI/heOxQ0rSmYFyNkC6',
            'isadmin' => true,
            'isActive' => true

        ]);
        
    }
}
