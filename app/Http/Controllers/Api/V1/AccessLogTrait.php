<?php

namespace App\Http\Controllers\Api\V1;

use App\AccessLog;
use Illuminate\Support\Facades\Auth;

trait AccessLogTrait {
    function writeLog(
        $component,$action,$target,$info
    ) { 
        $l = new AccessLog();
        $l->user_id = Auth::user()->id;
        $l->component = $component;
        $l->target = $target;
        $l->action = $action;
        $l->info = $info;
        $l->save();
     }
    
}