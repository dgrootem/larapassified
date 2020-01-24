<?php

namespace App\Http\Controllers\api\V1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Setting;
use Log;
use App\Http\Controllers\Api\V1\AccessLogTrait;

class SettingsController extends Controller
{

    use AccessLogTrait;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function indexByContext($context)
    {
        return Setting::where('context',$context)->get();
    }

    public function index()
    {
        return null;
    }

    
    

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        Log::debug($request->all());
        
        foreach($request->all() as $settingsData){
            Log::debug($settingsData);
            Log::debug($settingsData['id']);
            $s = Setting::findOrFail($settingsData['id']);
            $this->writeLog('Settings','update',$s->name,'');
            unset($settingsData['id']);
            $s->update($settingsData);
        }
        return Setting::all();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
