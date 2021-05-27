<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
/**
 *  model voor een aanstelling van in een school. Hierbij wordt de link gemaakt tussen de school enerzijds 
 * en een object 'edu_function_data' anderzijds waar de gegevens van het personeelslid + het specifieke ambt in zitten
 * */
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
