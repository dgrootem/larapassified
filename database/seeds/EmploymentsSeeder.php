<?php

use Illuminate\Database\Seeder;

class EmploymentsSeeder extends MyCsvSeeder
{
    public function __construct()
    {
      $this->table = 'employments';
      $this->filename = base_path().'/database/seeds/csv/employments.txt';
    }
}
