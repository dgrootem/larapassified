<?php

namespace App\Http\Controllers\Api\V1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\EmploymentInterruption;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Exception;

class EmploymentInterruptionController extends Controller
{

    use AccessLogTrait;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->writeLog('Interruptions','index','all','');
        return EmploymentInterruption::all();
    }

    public function authorizeRO(){
        if (Auth::user()->readonly) throw new Exception('not authorized'); 
    }

    public function show($id)
    {
        $this->writeLog('Interruptions','show','id='.$id,'');
        return EmploymentInterruption::findOrFail($id);
    }

    private function saveInterruption(Request $request,EmploymentInterruption $employmentInterruption){
        $employmentInterruption->beginDate = Carbon::parse(substr($request['beginDate'],0,10));
        $employmentInterruption->endDate = Carbon::parse(substr($request['endDate'],0,10));
        $employmentInterruption->employee_id = $request['employee_id'];
        $employmentInterruption->interruption_type_id = $request['interruption_type_id'];
        
        $employmentInterruption->save();
        $it = $employmentInterruption->interruption_type;
    }

    public function update(Request $request, $id)
    {
        $this->authorizeRO();
        $employmentInterruption = EmploymentInterruption::findOrFail($id);
        $this->writeLog('Interruptions','update','id='.$id,'');
        $this->saveInterruption($request,$employmentInterruption);
        

        return $employmentInterruption;
    }

    public function store(Request $request)
    {
        $this->authorizeRO();
        $employmentInterruption = new EmploymentInterruption();
        $this->writeLog('Interruptions','create','id='.$employmentInterruption->id,'');
        $this->saveInterruption($request,$employmentInterruption);
        return $employmentInterruption;
    }

    public function destroy($id)
    {
        $this->authorizeRO();
        $employmentInterruption = EmploymentInterruption::findOrFail($id);
        $this->writeLog('Interruptions','create','id='.$employmentInterruption->id,'');
        $employmentInterruption->delete();
        return '';
    }

    public function interruptionsForEmployee($employee_id){
        $this->writeLog('Interruptions','interruptionsForEmployee','employee_id='.$employee_id,'');
        return EmploymentInterruption::with('interruption_type')->where('employee_id',$employee_id)->get();
    }
}
