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

class PDFController extends Controller
{
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

        //return compact(['ambten','aanstellingen','interruptions','employee','gendate']);

        $pdf = PDF::loadView('pdf.persoonlijk',compact(['ambten','aanstellingen','interruptions','employee','gendate']));
        return $pdf->download($id.'.pdf');

        return view('pdf.persoonlijk',compact(['ambten','aanstellingen','interruptions','employee','gendate']));
    }

    
}
