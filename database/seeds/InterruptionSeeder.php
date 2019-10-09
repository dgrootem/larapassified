<?php

use Illuminate\Database\Seeder;

class InterruptionSeeder extends MyCsvSeeder
{
    public function __construct()
    {
      $this->table = 'employment_interruptions';
      $this->filename = base_path().'/database/seeds/csv/interruptions.txt';
    }
}
