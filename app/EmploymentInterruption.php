<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EmploymentInterruption extends Model
{
    protected $guarded = ["id"];

    protected $dates = ['created_at', 'updated_at', 'beginDate', 'endDate'];

    public function employee(){
        return $this->belongsTo(Employee::class);
    }
}
