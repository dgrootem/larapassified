<?php

namespace App\Http\Controllers\Api\V1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use \App\EducationalFunction;
use App\EduFunctionData;
use Illuminate\Support\Facades\Auth;
use Exception;

class EducationalFunctionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        return EducationalFunction::all();
    }

    public function availableForEmployee($id)
    {
        $ids = EduFunctionData::where('employee_id',$id)->pluck('educational_function_id');
        return EducationalFunction::whereNotIn('id',$ids)->get();
    }

    public function authorizeAdmin(){
        if (!Auth::user()->isadmin) throw new Exception('not authorized'); 
    }

    public function authorizeRO(){
        if (Auth::user()->readonly) throw new Exception('not authorized'); 
    }
    

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->authorizeRO();
        $func = EducationalFunction::create($request->all());
        return $func;
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
        $this->authorizeRO();
        $func = EducationalFunction::findOrFail($id);
        $func->update($request->all());
        return $func;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $this->authorizeRO();
        $func = EducationalFunction::findOrFail($id);
        $func->delete();
        return '';
    }
}
