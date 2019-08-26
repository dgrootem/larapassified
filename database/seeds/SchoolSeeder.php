<?php

use Illuminate\Database\Seeder;

class SchoolSeeder extends MyCsvSeeder
{

    public function __construct()
    {
      $this->table = 'schools';
      $this->filename = base_path().'/database/seeds/csv/schools.csv';
    }
    
}