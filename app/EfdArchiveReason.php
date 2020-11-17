<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EfdArchiveReason extends Model
{

    public function eduFunctionDatas(){
        return $this->hasMany(EduFunctionData::class,'edu_function_data_id','id');
    }
}
