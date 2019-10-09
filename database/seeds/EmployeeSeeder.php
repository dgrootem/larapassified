<?php

use Illuminate\Database\Seeder;

class EmployeeSeeder extends MyCsvSeeder
{


    public function __construct()
    {
      $this->table = 'employees';
      $this->filename = base_path().'/database/seeds/csv/employees.txt';
    }

    
}
