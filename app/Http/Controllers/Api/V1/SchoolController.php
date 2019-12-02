<?php

namespace App\Http\Controllers\Api\V1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use \App\School;
use \App\SchoolType;

class SchoolController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $scholen = School::all();
        foreach($scholen as $s) $s->cbd = $s->canBeDeleted();
        $schooltypes = SchoolType::all();
        return compact(["scholen","schooltypes"]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        
        $school = new School();
        $this->saveSchool($school,$request);
        return $school;
    }

    private function saveSchool(School $school,Request $request){
        $schoolType = SchoolType::findOrFail($request['school_type_id']);
        $school->name = $request['name'];
        $school->abbreviation = $request['abbreviation'];
        $school->adres = $request['adres'];
        $school->postcode = $request['postcode'];
        $school->gemeente = $request['gemeente'];
        $school->logo_filename = $request['logo_filename'];
        $school->useForCalculations = $request['useForCalculations'];
        $school->isActive = $request['isActive'];
        $school->schoolType()->associate($schoolType);
        $school->save();
        $school->cbd = $school->canBeDeleted();
        return $school;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return School::findOrFail($id);
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
        $school = School::findOrFail($id);
        $this->saveSchool($school,$request);
        //$school->update($request->all());
        return $school;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $school = School::findOrFail($id);
        $school->delete();
        return '';
    
    }
}
