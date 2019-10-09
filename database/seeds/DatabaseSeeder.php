<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(SchoolTypeSeeder::class);
        $this->call(SchoolSeeder::class);
        $this->call(EmployeeSeeder::class);
        $this->call(FunctionSeeder::class);
        $this->call(FunctionDataSeeder::class);
        $this->call(EmploymentsSeeder::class);
        
        $this->call(InterruptionSeeder::class);

        $this->call(BenoemingSeedre::class);

    }
}
