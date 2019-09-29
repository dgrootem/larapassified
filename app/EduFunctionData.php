<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EduFunctionData extends Model
{
    protected $guarded = ["id"];

    public function employee(){
        return $this->belongsTo(Employee::class);
    }
    public function educationalFunction(){
        return $this->belongsTo(EducationalFunction::class);
    }
    public function employments(){
        return $this->hasMany(Employment::class);
    }
}
