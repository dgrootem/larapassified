<?php

namespace App\Http\Controllers\Api\V1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Employee;
use App\EduFunctionData;
use App\School;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Support\Facades\Auth;
use Exception;

use Log;

class TaddCalculationController extends Controller
{
    public function authorizeRO()
    {
        if (Auth::user()->readonly) throw new Exception('not authorized');
    }

    public function recalculateForSchool($id)
    {
        //testpersoon 28311250560
        $this->authorizeRO();
        $empids = Employee::join('edu_function_data', 'edu_function_data.employee_id', '=', 'employees.id')
            ->join('employments', 'employments.edu_function_data_id', '=', 'edu_function_data.id')
            ->where('edu_function_data.istadd', 0)
            ->where('edu_function_data.isbenoemd', 0)
            ->where('employments.school_id', $id)
            ->distinct('employees.id')
            ->select('employees.id')
            ->get();
        foreach ($empids as $empid) {
            //Log::debug($empid->id);
            $employee = Employee::with('educationalFunctionData.employments.school')->where('id', $empid->id)->get()[0];
            $this->updateAllSeniorityDaysInternal($employee);
            //Log::debug($empid);
        }
        $school = School::find($id);
        // Log::debug('useForCalculations='.$school->useForCalculations);
        if ($school->useForCalculations == 0){
            Log::info('archiving employees that only work in schools that don\'t count...');
            $query3 = "update employees set isActive = 0 where isactive = 1 and id not in (select distinct edf.employee_id from edu_function_data edf inner join employments eo on edf.id = eo.edu_function_data_id and eo.school_id <> ".$school->id.")";
            \DB::statement($query3);
        }
        else
        {
            Log::info('heractiveer alle personeelsleden die in deze school werkten');
            $query3 = "update employees set isActive = 1 where isactive = 0 and id not in (select distinct edf.employee_id from edu_function_data edf inner join employments eo on edf.id = eo.edu_function_data_id and eo.school_id <> ".$school->id.")";
            \DB::statement($query3);
        }

    }

    function updateSeniorityDays($functionData_id)
    {
        $this->authorizeRO();
        $functionData = EduFunctionData::findOrFail($functionData_id);
        //Log::debug('updateSeniorityDays for '.$functionData_id);
        // Log::debug($functionData);
        

        $employee = Employee::find($functionData->employee_id);
        // Log::debug("EMPLOYEE=" . $employee->id);
        // Log::debug("Calculating total days");
        $result =  $this->calculateSenDaysFD($employee, $functionData);
        // Log::debug($result);
        $functionData->total_seniority_days = $result['aantalDagen'];
        $functionData->seniority_days = $result['effectieveAantalDagen'];
        $functionData->save();
        // Log::debug($functionData);
        return $functionData;
    }

    public function updateAllSeniorityDays(Request $request,$id){
        $this->authorizeRO();
        // Log::debug($request);
        $employee = Employee::find($id);
        $this->updateAllSeniorityDaysInternal($employee);
    }

    function updateAllSeniorityDaysInternal(Employee $employee)
    {
        
        // Log::debug('updateAllSeniorityDays');
        // Log::debug($employee);
        // Log::debug($employee->educationalFunctionData);

        foreach ($employee->educationalFunctionData as $functionData) {
            // Log::debug('x');
            // Log::debug('updateSeniorityDays for '.$functionData->id);
            $this->updateSeniorityDays($functionData->id);
        }
        return $employee;
    }

    function calculateSenDaysEmp(Employee $employee)
    {
        $allFD = array();
        foreach ($employee->educationalFunctionData as $functiondata)
            $allFD[$functiondata->id] = $this->calculateSenDaysFD($employee, $functiondata);
        return compact('allFD');
    }

    function addToTriggerDates($triggerDates, $date, $periode)
    {
        $key = $date;
        $counter = 0;
        while (array_key_exists($key, $triggerDates)) $key = $key . $counter++;
        $triggerDates[$key] = $periode;
        return $triggerDates;
    }

    function calculateSenDaysFD(Employee $employee, EduFunctionData $functiondata)
    {
        // Log::debug('calculateSenDaysFD');
        //steek all data in een lijst: key = datum, value verwijst naar de overeenkomstige periode
        $triggerDates = array();

        foreach ($functiondata->employments as $e) {
            if ($e->school->useForCalculations) {
                $e->ptype = 1; //aanstelling
                $triggerDates = $this->addToTriggerDates($triggerDates, $e->beginDate->format('Ymd') . 'b', $e);
                $triggerDates = $this->addToTriggerDates($triggerDates, $e->endDate->format('Ymd') . 'e', $e);
            }
        }
        foreach ($employee->employmentInterruptions as $i) {
            $i->ptype = 2; //onderbreking
            $triggerDates = $this->addToTriggerDates($triggerDates, $i->beginDate->format('Ymd') . 'i', $i);
            $triggerDates = $this->addToTriggerDates($triggerDates, $i->endDate->format('Ymd') . 'j', $i);
        }
        // Log::debug(compact('triggerDates'));

        //sorteer de lijst op datums
        ksort($triggerDates);

        
        $huidigeAantalUren = 0;
        $aantalDagen = $functiondata->startwaarde_tot;
        $actievePeriodes = array();
        $onderbreking = null;
        $aantalDagenOnderbreking = 0;
        $aantalDagenOnderbrekingDatTelt = 0;
        $factor = 1.0;
        if (count($triggerDates) > 0) //enkel als we triggerdates hebben, anders gaan we ook niet door de loop
            $laatsteDatum = Carbon::createFromFormat('Ymd', substr(array_keys($triggerDates)[0], 0, 8)); //eerste element uit de lijst nemen als startpunt
        $teller = 0;
        $usedExtraDay = 0;
        foreach (array_keys($triggerDates) as $date) {
            if (count($actievePeriodes) == 0) $usedExtraDay = 0;
            $currentDate = Carbon::createFromFormat('Ymd', substr($date, 0, 8));
            // Log::debug('teller='.$teller);
            // Log::debug('laatsteDatum='.$laatsteDatum);
            // Log::debug('currentDate='.$currentDate);
            if ($teller > 0) {
                $kenletter = substr($date, 8, 1);
                if (($kenletter == 'j') || ($kenletter == 'e')) {
                    //toe te voegen aan aantal dagen = aantal dagen sinds vorige trigger datum * totaal aantal uren voor de huidige actieve periodes
                    $periodeLengte = abs($laatsteDatum->diffInDays($currentDate)) + 1 - $usedExtraDay;
                    $usedExtraDay = 1;
                } else {
                    $periodeLengte = abs($laatsteDatum->diffInDays($currentDate)) - $usedExtraDay;
                    $usedExtraDay = 0;
                }

                $dagenDezePeriode = $periodeLengte * $factor;
                $aantalDagen += $dagenDezePeriode;

                if ($onderbreking !== null) {
                    $aantalDagenOnderbreking += $dagenDezePeriode;
                    if ($onderbreking->interruption_type->telt_mee) $aantalDagenOnderbrekingDatTelt += $dagenDezePeriode;
                }
                // Log::debug(compact(['periodeLengte','dagenDezePeriode','factor']));
            }
            // werk de lijst van actieve periodes bij: elke periode kan slechts 2 keer getriggerd worden: 1e keer => wordt actief, 2e keer => stopt
            if ($triggerDates[$date]->ptype == 1) {

                if (isset($actievePeriodes) && (!array_key_exists($triggerDates[$date]->id, $actievePeriodes))) {
                    // Log::debug("nieuwe actieve periode");
                    $actievePeriodes[$triggerDates[$date]->id] = $triggerDates[$date];
                } else {
                    // Log::debug("stop actieve periode");
                    unset($actievePeriodes[$triggerDates[$date]->id]);
                }
            } else //het is een onderbreking
            {
                //nieuwe onderbreking of einde bestaande onderbreking?
                if ($onderbreking === null) {
                    //Log::debug('start onderbreking op '.$date);
                    $onderbreking = $triggerDates[$date];
                } else {
                    //Log::debug('stop onderbreking op '.$date);
                    $onderbreking = null;
                }
            }
            //herbereken het huidige aantal uren
            $huidigeAantalUren = 0;

            if (isset($actievePeriodes))
                foreach ($actievePeriodes as $id => $p) $huidigeAantalUren += $p->hours;
            //  Log::debug('uren='.$huidigeAantalUren);
            //  Log::debug('=======================================================');
            if ($huidigeAantalUren == 0) $factor = 0;
            else if ($huidigeAantalUren >= $functiondata->educationalFunction->denominator / 2)
                $factor = 1.0;
            else
                $factor = 0.5;


            $laatsteDatum = $currentDate;
            $teller++;
        }
        // Log::debug(compact(['aantalDagen','aantalDagenOnderbreking','aantalDagenOnderbrekingDatTelt']));
        //beperk het aantal dagen dat kan meetellen
        $aantalDagenOnderbrekingDatTelt = min($aantalDagenOnderbrekingDatTelt, 220);
        //bereken het aantal dagen dat niet mag meetellen: totaal aantal dagen onderbreking MINUS het aantal dat mag meetellen
        $aantalDagenDatNietTelt = $aantalDagenOnderbreking - $aantalDagenOnderbrekingDatTelt + $employee->startwaardeINT;
        // het aantal dagen dat telt als effectief gepresteerd = totaal aantal dagen MINUS het aantal dat niet mag meetellen

        $effectieveAantalDagen = max($aantalDagen - $aantalDagenDatNietTelt,0);

        return compact(['aantalDagen', 'effectieveAantalDagen']);
    }

    function calculateForThisSchoolYear()
    {
    }
}
