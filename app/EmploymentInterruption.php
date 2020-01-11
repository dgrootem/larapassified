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

    public function interruption_type(){
        return $this->belongsTo(InterruptionType::class);
    }

    public function getBeginDateAsDateAttribute(){
        return $this->beginDate->format('d-m-Y');
    }
    public function getEndDateAsDateAttribute(){
        return $this->endDate->format('d-m-Y');
    }
}
