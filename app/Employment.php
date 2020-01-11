<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Employment extends Model
{
    protected $guarded = ["id"];

    protected $dates = ['created_at', 'updated_at', 'beginDate', 'endDate'];

    public function educationalFunctionData(){
        return $this->belongsTo(EduFunctionData::class);
    }

    public function school(){
        return $this->belongsTo(School::class);
    }

    public function getBeginDateAsDateAttribute(){
        return $this->beginDate->format('d-m-Y');
    }
    public function getEndDateAsDateAttribute(){
        return $this->endDate->format('d-m-Y');
    }
}
