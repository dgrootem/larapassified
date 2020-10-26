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

    use AccessLogTrait;

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
        return $this->allEmployees();
    }

    private function allEmployees(){
        Log::debug("returning all employees");
        Log::debug(0);
        $this->writeLog('Employees','index','all','full list');
        $q = $this->buildIndexQuery(false);//->orderBy('firstname', 'asc');
        Log::debug(3);
        Log::debug($q->toSql());
        return $q->get();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function indexBySchool(Request $request,$schoolId)
    {
        Log::debug($schoolId);
        if ($schoolId == -1 ) return $this->allEmployees();
        Log::debug("returning all employees for school met id =".$schoolId);
        $this->writeLog('Employees','index(filtered)','school id = '.$schoolId,'filtered list');
        $q= $this->buildIndexQuery('1a1a1',true)
            ->join('edu_function_data','employee_id','=','employees.id')
            ->join('employments','edu_function_data.id','=','employments.edu_function_data_id')
            ->where('school_id',$schoolId)
            ->orderBy('firstname', 'asc');
            Log::debug($q->toSql());
            return $q->get();
    }

    private function buildIndexQuery($flags,$distinct = false)
    {
        //Log::debug($flags);
        $result = Employee::selectRaw(($distinct?'distinct ':'').'employees.*,concat(firstname," ",lastname," [",ifnull(registrationNumber,""),"]") as fullNameExtended');
        $proc_flags = explode('a',$flags);
        Log::debug(compact('proc_flags'));
        if ($proc_flags[0] == 0 ){
            Log::debug('Return enkel niet-gearchiveerde personeelsleden (PERM)');
            $result = $result->whereExists(function($query){
                $query->select (\DB::raw(1))->from('edu_function_data')->where('employee_id',\DB::raw('employees.id'))->where('archived_final',0);
            });
        }
        if ($proc_flags[1] == 0 ){
            Log::debug('Return enkel niet-gearchiveerde personeelsleden (TEMP)');
            $result = $result->whereExists(function($query){
                $query->select (\DB::raw(1))->from('edu_function_data')->where('employee_id',\DB::raw('employees.id'))->where('archived_temporary',0);
            });
        }
        if ($proc_flags[2] == 0 ){
            Log::debug('Return enkel niet-gearchiveerde personeelsleden (AUTO)');
            $result = $result->whereExists(function($query){
                $query->select (\DB::raw(1))->from('edu_function_data')->where('employee_id',\DB::raw('employees.id'))->where('archived_auto',0);
            });
        }
        //if ($activeOnly) $result = $result->where('isActive', 1);
        //Log::debug(2);
        Log::debug($result->toSql());
        return $result;
    }

    public function indexFlags(String $flags,Request $request)
    {
        Log::debug("returning employees with specific flags only");
        $this->writeLog('Employees','index','all','filtered list');
        return $this->buildIndexQuery($flags)->orderBy('firstname', 'asc')->get();
    }

    public function filterByName(String $value)
    {
        
        $employee = Employee::where('lastName', 'like', '%' . $value . '%')->orWhere('firstName', 'like', '%' . $value . '%')->selectRaw('*, CONCAT(lastName," ",firstName," [",registrationNumber,"]") as fullname')->get();
        $this->writeLog('Employees','fitlerByName','(first/last)name like '.$value,'found: '.($employee!=null?$employee->fullName:''));
        return $employee;
    }

    public function visible()
    {
        $this->writeLog('Employees','index','visible','');
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
        $this->writeLog('Employees','create',$request['lastName'].' '.$request['firstName'],'');
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
        $employee =  Employee::findOrFail($id);
        $this->writeLog('Employees','show',$employee->fullName,'');
        return $employee;
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
        $this->writeLog('Employees','update',$employee->fullName,'');
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

    private function createError($msg){
        return response()->json([
            'status' => 'error',
            'msg'    => $msg,
            'errors' => $msg,
        ], 500);
    }

    private function saveEmployee(Employee $employee, Request $request)
    {
        $this->authorizeRO();
        if ($request['registrationNumber'] !== null) //only check for existence / uniqueness if registrationnumber is available
        {
            $testemployee = Employee::where('registrationNumber', $request['registrationNumber'])->get();
            if ($testemployee->count() > 1) return $this->createError('ERROR : meerdere personeelsleden gevonden met dit nummer!!');
            else if (($testemployee->count() == 1) && ($testemployee[0]->id != $employee->id)) {
                return $this->createError('Stamboeknummer bestaat al!');
            }
        }

        $employee->birthDate = $this->getBirthDate($request);
        $employee->registrationNumber = $request['registrationNumber'];
        $employee->firstName = $request['firstName'];
        $employee->lastName = $request['lastName'];
        $employee->isActive = $request['isActive'];
        //$employee->startwaardeDA = $request['startwaardeDA'];
        //$employee->startwaardeINT = $request['startwaardeINT'];
        //$employee->oudsysteem = false; //$request['oudsysteem'];
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
        $this->writeLog('Employees','delete',$employee->fullName,'');
        $employee->delete();
        return '';
    }

    public function archiveOldOrTADDEmployees(/*Request $request*/)
    {
        if (!Auth::user()->isadmin && Auth::user()->isactive) throw new Exception('not authorized');
        $this->writeLog('Employees','archive','OldOrTADDEmployees','');
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
    }

    public function toggleEmployeesVisibility($visiblity)
    {
        if (!Auth::user()->isadmin) throw new Exception('not authorized');
        $this->writeLog('Employees','toggle visibility','',$visiblity);
        $v = ($visiblity == true ? "1" : "0");
        Employee::where('isActive',0)->update(["isActive" => 1]);
        //\DB::raw("update employees set isActive = " . $v . ";");
    }
}
