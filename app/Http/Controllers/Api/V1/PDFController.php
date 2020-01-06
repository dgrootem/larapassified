<?php

namespace App\Http\Controllers\Api\V1;

use App\EduFunctionData;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Employee;
use App\EmploymentInterruption;

class PDFController extends Controller
{
    //
    public function createPDF(Employee $employee){
        $ambten = Employee::join('edu_function_data','employees.id','=','edu_function_data.employee_id')
                        ->join('educational_functions','educational_function_id','=','educational_functions.id')
                        ->where('employees.id',$employee->id)
                        ->distinct()
                        ->get(['educational_functions.id','educational_functions.name']);
        $aanstellingen = array();
        foreach($ambten as $ambt)
            $aanstellingen[$ambt->id] = EduFunctionData::where('employee_id',$employee->id)
                                                    ->where('educational_function_id',$ambt->id)
                                                    ->with('employments')
                                                    ->get();
        $interruptions = EmploymentInterruption::where('employee_id',$employee->id);

        return compact(['ambten','aanstellingen','interruptions','employee']);

        return view('pdf',compact(['ambten','aanstellingen','interruptions','employee']));
    }
}
