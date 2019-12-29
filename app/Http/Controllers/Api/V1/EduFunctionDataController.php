<?php

namespace App\Http\Controllers\Api\V1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\EduFunctionData;
use App\Employment;
use App\Setting;
use Log;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Exception;

class EduFunctionDataController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return EduFunctionData::with(['educationalFunction','employee'])->get();
    }

    public function authorizeRO(){
        if (Auth::user()->readonly) throw new Exception('not authorized'); 
    }

    //todo: pagination voorzien
    // zie https://laravel.com/docs/5.5/pagination
    public function fullIndex(){
        //Log::debug("trying to find some data");
        //return EduFunctionData::with(['educationalFunction','employee'])->get();
            return $this->baseQuery()
                ->orderBy('employees.lastName', 'asc')
                ->orderBy('educational_functions.name', 'asc')
                ->get();
    }

    private function getCurrentSetting($name){
        $now = new Carbon();
        return Setting::where('name',$name)->where('van','<',$now)->where('tot','>',$now)->pluck('value')[0];
    }
    
    public function baseQuery(){
        

        $neededTotal = $this->getCurrentSetting('taddNeededTotal');
        $neededEffective1 = $this->getCurrentSetting('taddNeededEffective');
        $neededEffective2 = $this->getCurrentSetting('taddNeededEffective2');

        return EduFunctionData::join('employees','employees.id','=','employee_id')
                ->join('educational_functions','educational_function_id','=','educational_functions.id')
                ->select('employees.firstname',
                         'employees.lastname',
                         'edu_function_data.id',
                         'educational_functions.name as ambt',
                         'edu_function_data.seniority_days',
                         'edu_function_data.total_seniority_days',
                         \DB::raw('edu_function_data.seniority_days / '.$neededEffective1 . ' * 100.0 as seniority_days_perc'),
                         \DB::raw('edu_function_data.total_seniority_days / '.$neededTotal. ' * 100.0 as total_seniority_days_perc'),
                         'edu_function_data.datum_verbetering_nodig_gezet as werkpunt',
                         'edu_function_data.istadd');
    }

    public function nextYearTADD(){
        $neededTotal = $this->getCurrentSetting('taddNeededTotal');
        $neededEffective1 = $this->getCurrentSetting('taddNeededEffective');
        $neededEffective2 = $this->getCurrentSetting('taddNeededEffective2');
        Log::debug(compact(['neededTotal','neededEffective1','neededEffective2']));
        return $this->baseQuery()
            ->where('edu_function_data.istadd','=',0)
            ->whereBetween('edu_function_data.total_seniority_days',array(277,$neededTotal))
            ->whereBetween('edu_function_data.seniority_days',array(200,$neededEffective1))
            ->orderBy('employees.lastName', 'asc')
                ->orderBy('educational_functions.name', 'asc')
                ->get();

    }

    public function thisYearTADD(){
//        Log::debug(compact(['neededTotal','neededEffective1','neededEffective2']));
        return $this->baseQuery()
            ->where('edu_function_data.istadd','=',0)
            ->where(function($query){
                $neededTotal = $this->getCurrentSetting('taddNeededTotal');
                $neededEffective1 = $this->getCurrentSetting('taddNeededEffective');
                $query->where('edu_function_data.total_seniority_days','>=',$neededTotal)
                    ->where('edu_function_data.seniority_days','>=',$neededEffective1);
            })    
            ->orWhere(function($query){
                $neededEffective2 = $this->getCurrentSetting('taddNeededEffective2');
                $query->whereNotNull('edu_function_data.datum_verbetering_nodig_gezet')
                    ->where('edu_function_data.seniority_days_currentyear','>=',$neededEffective2);
            })
            ->orderBy('employees.lastName', 'asc')
            ->orderBy('educational_functions.name', 'asc')
            ->get();

    }

    public function alreadyTADD(){
        return $this->baseQuery()->where('edu_function_data.istadd','=',1)
            ->orderBy('employees.lastName', 'asc')
            ->orderBy('educational_functions.name', 'asc')
            ->get();
    }

    public function show($id)
    {
        return EduFunctionData::findOrFail($id);
    }

    private function saveAndReturn($eduFunctionData){
        $this->authorizeRO();
        $eduFunctionData->save();
        $eduFunctionData->educationalFunction;
        $eduFunctionData->employments;

        return $eduFunctionData;
    }

    //set ambt
    public function update(Request $request, $id)
    {
        $this->authorizeRO();
        $eduFunctionData = EduFunctionData::findOrFail($id);
        Log::debug($request->all());
        $eduFunctionData->educational_function_id= $request['educational_function_id'];
        return $this->saveAndReturn($eduFunctionData);
    }

    //add employment
    public function addEmployment(Request $request,$id){
        $this->authorizeRO();
        $eduFunctionData = EduFunctionData::findOrFail($id);
        $employment = Employment::findOrFail($request['employment_id']);
        $eduFunctionData->employments()->associate($employment);
        return $this->saveAndReturn($eduFunctionData);
    }

    //remove employment
    public function removeEmployment(Request $request,$id,$employment_id){
        $this->authorizeRO();
        $eduFunctionData = EduFunctionData::findOrFail($id);
        $eduFunctionData->employments()->detach($employment_id);
        return $this->saveAndReturn($eduFunctionData);
    }

    //create a new instance
    public function store(Request $request)
    {
        $this->authorizeRO();
        Log::debug($request->all());
        //link with employee and educational function 
        $eduFunctionData = new EduFunctionData();
        //employment linking will come later
        //$eduFunctionData->employment_id = $employment->id;
        $eduFunctionData->educational_function_id = $request['educational_function_id'];
        $eduFunctionData->employee_id = $request['employee_id'];

        $eduFunctionData->save();
        $id = $eduFunctionData->id;
        $eduFunctionData = EduFunctionData::findOrFail($id);

        Log::debug("newly created edufunctiondata id=".$eduFunctionData->id);
        Log::debug($eduFunctionData);
        
        $eduFunctionData->employments;
        $eduFunctionData->educationalFunction;

        return $eduFunctionData;

    }

    public function destroy($id)
    {
        $this->authorizeRO();
        $eduFunctionData = EduFunctionData::findOrFail($id);
        $eduFunctionData->delete();
        return '';
    }

    public function functionDataForEmployee($id){
        $result =  EduFunctionData::where('employee_id',$id)->with('educationalFunction','employments.school')->get();
        return $result;
    }

    public function addWerkpunt($id){
        $this->authorizeRO();
        $efd = EduFunctionData::findOrFail($id);
        $efd->datum_verbetering_nodig_gezet = Carbon::today();
        $efd->save();
        return $efd;
    }

    public function verwijderWerkpunt($id){
        $this->authorizeRO();
        $efd = EduFunctionData::findOrFail($id);
        $efd->datum_verbetering_nodig_gezet = null;
        $efd->save();
        return $efd;
    }

    public function addTADD($id){
        $this->authorizeRO() ;
        $efd = EduFunctionData::findOrFail($id);
        $efd->isTadd = true;
        $efd->save();
        return $efd;
    }

    public function verwijderTADD($id){
        $this->authorizeRO();
        $efd = EduFunctionData::findOrFail($id);
        $efd->isTadd = false;
        $efd->save();
        return $efd;
    }
}
