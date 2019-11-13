<?php

namespace App\Http\Controllers\Api\V1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\EmploymentInterruption;
use Carbon\Carbon;

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

    private function saveInterruption(Request $request,EmploymentInterruption $employmentInterruption){
        $employmentInterruption->beginDate = Carbon::parse(substr($request['beginDate'],0,10));
        $employmentInterruption->endDate = Carbon::parse(substr($request['endDate'],0,10));
        $employmentInterruption->employee_id = $request['employee_id'];
        $employmentInterruption->interruption_type_id = $request['interruption_type_id'];
        
        $employmentInterruption->save();
    }

    public function update(Request $request, $id)
    {
        $employmentInterruption = EmploymentInterruption::findOrFail($id);
        $this->saveInterruption($request,$employmentInterruption);

        return $employmentInterruption;
    }

    public function store(Request $request)
    {
        $employmentInterruption = new EmploymentInterruption();
        $this->saveInterruption($request,$employmentInterruption);
        return $employmentInterruption;
    }

    public function destroy($id)
    {
        $employmentInterruption = EmploymentInterruption::findOrFail($id);
        $employmentInterruption->delete();
        return '';
    }

    public function interruptionsForEmployee($employee_id){
        return EmploymentInterruption::where('employee_id',$employee_id)->get();
    }
}
