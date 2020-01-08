<?php

namespace App\Http\Controllers\Api\V1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Employee;
use Carbon\Carbon;
use Log;
use App\EduFunctionData;
use App\Setting;
use Illuminate\Support\Facades\Auth;
use Exception;

class EmployeeController extends Controller
{

    public function authorizeRO(){
        if (Auth::user()->readonly) throw new Exception('not authorized'); 
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        Log::debug("returning all employees");
        return $this->buildIndexQuery(false);
    }

    private function buildIndexQuery($activeOnly)
    {
        $result = Employee::selectRaw('*,concat(firstname," ",lastname," [",ifnull(registrationNumber,""),"]") as fullNameExtended');
        if ($activeOnly) $result = $result->where('isActive', 1);
        return $result->orderBy('firstname', 'asc')->get();
    }

    public function indexActive()
    {
        Log::debug("returning active employees only");
        return $this->buildIndexQuery(true);
    }

    public function filterByName(String $value)
    {
        return Employee::where('lastName', 'like', '%' . $value . '%')->orWhere('firstName', 'like', '%' . $value . '%')->selectRaw('*, CONCAT(lastName," ",firstName," [",registrationNumber,"]") as fullname')->get();
    }

    public function visible()
    {
        return Employee::where('isActive', true)->get();
    }

    public function limitedTo5Years($visibleOnly = true)
    {
        return Employee::where('isActive', true); //TODO: add selection criteria for limiting to employees that have been active in the last 5 years
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
        $employee = new Employee();
        return $this->saveEmployee($employee, $request);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return Employee::findOrFail($id);
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
        $employee = Employee::findOrFail($id);
        return $this->saveEmployee($employee, $request);
    }

    private function getBirthDate(Request $request)
    {
        if (array_key_exists('registrationNumber', $request->all())) {
            if (strlen($request['registrationNumber'] > 10)) {
                $parseDate = substr($request['registrationNumber'], 1, 6);
                $year = substr($parseDate, 0, 2);
                //fix years only represented with two digits: put them in correct century
                if (intval($year) > 40) $year = "19" . $year;
                else $year = '20' . $year;
                return Carbon::createFromDate($year, substr($parseDate, 2, 2), substr($parseDate, 4, 2));
            }
        } else if (array_key_exists('birthDate', $request->all())) return $request['birthDate'];
        else return null;
    }

    private function saveEmployee(Employee $employee, Request $request)
    {
        $this->authorizeRO();
        $employee->birthDate = $this->getBirthDate($request);
        $employee->registrationNumber = $request['registrationNumber'];
        $employee->firstName = $request['firstName'];
        $employee->lastName = $request['lastName'];
        $employee->isActive = $request['isActive'];
        $employee->startwaardeDA = $request['startwaardeDA'];
        $employee->startwaardeINT = $request['startwaardeINT'];
        $employee->oudsysteem = $request['oudsysteem'];
        $employee->save();
        return $employee;
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
        $employee = Employee::findOrFail($id);
        $employee->delete();
        return '';
    }

    public function archiveOldOrTADDEmployees(/*Request $request*/)
    {
        if (!Auth::user()->isadmin && Auth::user()->isactive) throw new Exception('not authorized'); 
        Log::info("archiving old employees...");
        $query = "update employees set isActive = 0 where id in ".
            "(select r1.id from ".
                "(select e.id, max(ifNull(endDate, curdate())) as laatstedatum ".
                    "from employees e ".
                    "inner join edu_function_data edf on e.id = edf.employee_id ".
                    "inner join employments emp on emp.edu_function_data_id = edf.id ".
                    "group by e.id) r1 ".
                "where r1.laatstedatum < DATE_SUB(curdate(), INTERVAL 5 YEAR) ) ;";
        \DB::statement($query);
        Log::info('archiving employees that are already TADD...');
        $query2 = "update employees set isActive = 0 where isactive = 1 and id not in (select edf.employee_id from edu_function_data edf where edf.isTadd = 0);";
        \DB::statement($query2);
        
        
        Log::info("done!");
        //}
    }

    public function toggleEmployeesVisibility($visiblity)
    {
        if (!Auth::user()->isadmin) throw new Exception('not authorized'); 
        $v = ($visiblity == true ? "1" : "0");
        Employee::where('isActive',0)->update(["isActive" => 1]);
        //\DB::raw("update employees set isActive = " . $v . ";");
    }
}
