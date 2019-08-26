<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SchoolType extends Model
{
    //
    public function schools()
    {
        return $this->hasMany(School::class);
    }
}
