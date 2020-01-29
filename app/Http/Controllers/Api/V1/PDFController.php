<?php



namespace App\Http\Controllers\Api\V1;

ini_set('max_execution_time', 180);

use App\EduFunctionData;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Employee;
use App\EmploymentInterruption;
use PDF;
use Carbon\Carbon;
use App\Setting;

class PDFController extends Controller
{

    use AccessLogTrait;
    //
    public function createPDF(Request $request,$id){
        $employee = Employee::findOrFail($id);
        $ambten = Employee::join('edu_function_data','employees.id','=','edu_function_data.employee_id')
                        ->join('educational_functions','educational_function_id','=','educational_functions.id')
                        ->where('employees.id',$employee->id)
                        ->distinct()
                        ->get(['educational_functions.id','educational_functions.name']);
        $aanstellingen = array();
        foreach($ambten as $ambt)
            $aanstellingen[$ambt->id] = EduFunctionData::with('employments.school')
                                                    ->where('employee_id',$employee->id)
                                                    ->where('educational_function_id',$ambt->id)
                                                    ->get();
        $interruptions = EmploymentInterruption::with('interruption_type')->where('employee_id',$employee->id)->get();
        $gendate = Carbon::now()->format('d-m-Y H:i:s');

        $mainlogo = Setting::where('name','mainlogo')->where('van','<=',Carbon::today())->where('tot','>=',Carbon::today())->get();
        if ($mainlogo->count() >0) $mainlogo = $mainlogo[0];
        else $mainlogo = '';

        $this->writeLog('PDF','persoonelijke pdf','employee id='.$id,'');

        //return compact(['ambten','aanstellingen','interruptions','employee','gendate']);
        $usebootstrap = 1;
        //$pdf = PDF::loadView('pdf.persoonlijk',compact(['ambten','aanstellingen','interruptions','employee','gendate','usebootstrap','mainlogo']));
        //return $pdf->download($id.'.pdf');

        return view('pdf.persoonlijk',compact(['ambten','aanstellingen','interruptions','employee','gendate','usebootstrap','mainlogo']));
    }

    
}
