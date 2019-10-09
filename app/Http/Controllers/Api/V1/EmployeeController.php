<?php

namespace App\Http\Controllers\Api\V1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Employee;
use Carbon\Carbon;

class EmployeeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        return Employee::selectRaw('*, CONCAT(lastName," ",firstName) as fullname')->get();
    }

    public function filterByName(String $value){
        return Employee::where('lastName','like','%'.$value.'%')->orWhere('firstName','like','%'.$value.'%')->selectRaw('*, CONCAT(lastName," ",firstName) as fullname')->get();
    }

    public function visible()
    {
        return Employee::where('isActive', true);
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
        $employee = new Employee();
        return $this->saveEmployee($employee,$request);
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
        $employee = Employee::findOrFail($id);
        return $this->saveEmployee($employee,$request);
    }

    private function getBirthDate(Request $request)
    {
        if (array_key_exists('registrationNumber', $request->all())) {
            $parseDate = substr($request['registrationNumber'], 1, 6);
            $year = substr($parseDate, 0, 2);
            //fix years only represented with two digits: put them in correct century
            if (intval($year) > 40) $year = "19" . $year;
            else $year = '20' . $year;
            return Carbon::createFromDate($year, substr($parseDate, 2, 2), substr($parseDate, 4, 2));
        } else if (array_key_exists('birthDate', $request->all())) return $request['birthDate'];
        else return null;
    }

    private function saveEmployee(Employee $employee,Request $request)
    {
        $employee->birthDate = $this->getBirthDate($request);
        $employee->registrationNumber = $request['registrationNumber'];
        $employee->firstName = $request['firstName'];
        $employee->lastName = $request['lastName'];
        $employee->isActive = $request['isActive'];
        $employee->startwaarde = $request['startwaarde'];
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
        $employee = Employee::findOrFail($id);
        $employee->delete();
        return '';
    }
}
