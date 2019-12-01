<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    protected $guarded = ["id"];

    protected $dates = ['created_at', 'updated_at', 'birthDate'];

    public function getFullnameAttribute()
    {
        return "{$this->firstName} {$this->lastName}";
    }

    public function getFullnameExtendedAttribute()
    {
        return "{$this->firstName} {$this->lastName} [{$this->registrationNumber}]";
    }

    public function educationalFunctionData()
    {
        return $this->hasMany(EduFunctionData::class);
    }

    public function employmentInterruptions()
    {
        return $this->hasMany(EmploymentInterruption::class);
    }
}
