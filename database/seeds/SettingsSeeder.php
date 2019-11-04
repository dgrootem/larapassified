<?php

use Illuminate\Database\Seeder;

class SettingsSeeder extends MyCsvSeeder
{
    public function __construct()
    {
      DB::connection()->enableQueryLog();
      $this->table = 'settings';
      $this->filename = base_path().'/database/seeds/csv/settings.csv';
    }
    
}