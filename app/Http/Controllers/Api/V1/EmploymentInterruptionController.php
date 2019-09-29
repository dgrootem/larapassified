<?php

namespace App\Http\Controllers\Api\V1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\EmploymentInterruption;

class EmploymentInterruptionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return EmploymentInterruption::all();
    }

    public function show($id)
    {
        return EmploymentInterruption::findOrFail($id);
    }

    public function update(Request $request, $id)
    {
        $employmentInterruption = EmploymentInterruption::findOrFail($id);
        $employmentInterruption->update($request->all());

        return $employmentInterruption;
    }

    public function store(Request $request)
    {
        $employmentInterruption = EmploymentInterruption::create($request->all());
        return $employmentInterruption;
    }

    public function destroy($id)
    {
        $employmentInterruption = EmploymentInterruption::findOrFail($id);
        $employmentInterruption->delete();
        return '';
    }
}
