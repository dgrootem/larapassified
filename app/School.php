<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use \App\SchoolType;

class School extends Model
{
    protected $guarded = ["id"];

    public function school_type()
    {
        return $this->belongsTo(SchoolType::class);
    }

    public function employments(){
        return $this->hasMany(Employment::class);
    }
}
