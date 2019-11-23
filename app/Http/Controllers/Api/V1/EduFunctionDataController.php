<?php

namespace App\Http\Controllers\Api\V1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\EduFunctionData;
use App\Employment;
use Log;
use Carbon\Carbon;

class EduFunctionDataController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return EduFunctionData::with('educationalFunction')->get();
    }

    public function show($id)
    {
        return EduFunctionData::findOrFail($id);
    }

    private function saveAndReturn($eduFunctionData){
        $eduFunctionData->save();
        $eduFunctionData->educationalFunction;
        $eduFunctionData->employments;

        return $eduFunctionData;
    }

    //set ambt
    public function update(Request $request, $id)
    {
        $eduFunctionData = EduFunctionData::findOrFail($id);
        Log::debug($request->all());
        $eduFunctionData->educational_function_id= $request['educational_function_id'];
        return $this->saveAndReturn($eduFunctionData);
    }

    //add employment
    public function addEmployment(Request $request,$id){
        $eduFunctionData = EduFunctionData::findOrFail($id);
        $employment = Employment::findOrFail($request['employment_id']);
        $eduFunctionData->employments()->associate($employment);
        return $this->saveAndReturn($eduFunctionData);
    }

    //remove employment
    public function removeEmployment(Request $request,$id,$employment_id){
        $eduFunctionData = EduFunctionData::findOrFail($id);
        $eduFunctionData->employments()->detach($employment_id);
        return $this->saveAndReturn($eduFunctionData);
    }

    //create a new instance
    public function store(Request $request)
    {
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
        $eduFunctionData = EduFunctionData::findOrFail($id);
        $eduFunctionData->delete();
        return '';
    }

    public function functionDataForEmployee($id){
        $result =  EduFunctionData::where('employee_id',$id)->with('educationalFunction','employments.school')->get();
        return $result;
    }
}
