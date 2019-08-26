<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use \App\SchoolType;

class School extends Model
{
    //
        protected $guarded = ["id"];
        /*public function schoolType(){
            return $this->belongsTo(SchoolType::class,'school_type_id');
        }
*/
        public function school_type()
    {
        return $this->belongsTo(SchoolType::class);
    }
}
