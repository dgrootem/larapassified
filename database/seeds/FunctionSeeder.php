<?php

use Illuminate\Database\Seeder;

class FunctionSeeder extends MyCsvSeeder
{
    public function __construct()
    {
      $this->table = 'educational_functions';
      $this->filename = base_path().'/database/seeds/csv/functions.txt';
    }
}
