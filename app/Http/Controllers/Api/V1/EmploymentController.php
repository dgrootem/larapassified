<?php

namespace App\Http\Controllers\Api\V1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Employment;
use Carbon\Carbon;

class EmploymentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Employment::all();
    }

    public function show($id)
    {
        return Employment::findOrFail($id);
    }

    public function update(Request $request, $id)
    {
        $employment = Employment::findOrFail($id);
        $employment->update($request->all());

        return $employment;
    }

    public function store(Request $request)
    {
        $employment = new Employment();
        //Log::debug(substr($request['beginDate'],0,10));
        // Carbon::createFromFormat('DD-MM-YYYY',$request['beginDate']);
        $employment->beginDate = Carbon::parse(substr($request['beginDate'],0,10));
        $employment->endDate = Carbon::parse(substr($request['endDate'],0,10));;
        $employment->hours = $request['hours'];
        $employment->school_id = $request['school_id'];
        $employment->edu_function_data_id = $request['edu_function_data_id'];
        $employment->save();
        //$employment = Employment::create($request->all());
        $employment->school;
        return $employment;
    }

    public function destroy($id)
    {
        $employment = Employment::findOrFail($id);
        $employment->delete();
        return '';
    }
}
