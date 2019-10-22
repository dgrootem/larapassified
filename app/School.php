<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use \App\SchoolType;

class School extends Model
{
    protected $guarded = ["id"];

    public function schoolType()
    {
        return $this->belongsTo(SchoolType::class);
    }

    public function employments(){
        return $this->hasMany(Employment::class);
    }

    public function canBeDeleted(){
        return ($this->employments()->count() === 0);
    }

    
}
