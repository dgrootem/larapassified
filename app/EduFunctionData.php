<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
/**
 *  model voor een aanstelling van een personeellid (employee) in een ambt (educational_function)
 * */
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
    public function ratings(){
        return $this->hasMany(Rating::class);
    }

    public function archiveReasons(){
        return $this->hasMany(EfdArchiveReason::class);
    }
}
