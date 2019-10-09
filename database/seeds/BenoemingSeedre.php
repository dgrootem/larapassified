<?php

use Illuminate\Database\Seeder;

class BenoemingSeedre extends MyCsvSeeder
{
    public function __construct()
    {
      $this->table = 'benoemings';
      $this->filename = base_path().'/database/seeds/csv/benoemingen.txt';
    }
}
