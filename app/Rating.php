<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Rating extends Model
{
    protected $guarded = ["id"];

    protected $dates = ['created_at', 'updated_at'];

    public function educationalFunctionData(){
        return $this->belongsTo(EduFunctionData::class);
    }

    public function ratingType(){
        return $this->belongsTo(RatingType::class);
    }

    public function getBeginDate(){
        return $this->created_at->format('d-m-Y');
    }
    public function getEndDate(){
        return '';
    }
}
