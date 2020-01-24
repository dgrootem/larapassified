<?php

namespace App\Http\Controllers\Api\V1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Employment;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Exception;

class EmploymentController extends Controller
{
    use AccessLogTrait;

    public function authorizeRO(){
        if (Auth::user()->readonly) throw new Exception('not authorized'); 
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->writeLog('Employments','index','all','');
        return Employment::all();
    }

    public function show($id)
    {
        $this->writeLog('Employments','show','id='.$id,'');
        return Employment::findOrFail($id);
    }

    private function saveEmployment(Request $request,Employment $employment){
        $employment->beginDate = Carbon::parse(substr($request['beginDate'],0,10));
        $employment->endDate = Carbon::parse(substr($request['endDate'],0,10));;
        $employment->hours = $request['hours'];
        $employment->school_id = $request['school_id'];
        $employment->edu_function_data_id = $request['edu_function_data_id'];
        $employment->save();
    }

    public function update(Request $request, $id)
    {
        $this->authorizeRO();
        $employment = Employment::findOrFail($id);
        $this->writeLog('Employments','update','id='.$id,'');
        $this->saveEmployment($request,$employment);
        //$employment->update($request->all());
        $employment->school; //refresh link with school
        return $employment;
    }

    public function store(Request $request)
    {
        $this->authorizeRO();
        $employment = new Employment();
        $this->writeLog('Employments','create','id='.$employment->id,'');
        //Log::debug(substr($request['beginDate'],0,10));
        // Carbon::createFromFormat('DD-MM-YYYY',$request['beginDate']);
        $this->saveEmployment($request,$employment);
        
        //$employment = Employment::create($request->all());
        $employment->school;
        return $employment;
    }

    public function destroy($id)
    {
        $this->authorizeRO();
        $employment = Employment::findOrFail($id);
        $this->writeLog('Employments','delete','id='.$employment->id,'');
        $employment->delete();
        return '';
    }
}
