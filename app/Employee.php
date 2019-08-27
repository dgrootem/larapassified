<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    protected $guarded = ["id"];

    protected $dates = ['created_at', 'updated_at', 'birthDate'];
}
