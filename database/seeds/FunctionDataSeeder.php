<?php

use Illuminate\Database\Seeder;

class FunctionDataSeeder extends MyCsvSeeder
{
    public function __construct()
    {
      $this->table = 'edu_function_data';
      $this->filename = base_path().'/database/seeds/csv/functiondata.txt';
    }
}