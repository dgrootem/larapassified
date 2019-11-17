<?php

namespace App\Http\Controllers\Api\V1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Employee;
use App\EduFunctionData;
use Carbon\Carbon;
use Carbon\CarbonPeriod;

use Log;

class TaddCalculationController extends Controller
{
    

    function updateSeniorityDays($functionData_id){
        $functionData = EduFunctionData::findOrFail($functionData_id);
        Log::debug($functionData);
        Log::debug("EMPLOYEE=".$functionData->employee);
        
        $employee = $functionData->employee;
        Log::debug("Calculating total days");
        $result =  $this->calculateSenDaysFD($functionData->employee,$functionData);
        Log::debug($result);
        $functionData->total_seniority_days = $result['aantalDagen'];
        $functionData->seniority_days = $result['effectieveAantalDagen'];
        $functionData->save();
        Log::debug($functionData);
        return $functionData;
    }

    function updateAllSeniorityDays(Employee $employee){
        foreach($employee->educationalFunctionData as $functionData){
            $this->updateSeniorityDays($employee,$functionData);
        }
        return $employee;
    }

    //
    // function getTotalSeniorityDays(/*Employee $employee,*/ EduFunctionData $functionData){
    //     $employments = $functionData->employments;
    //     $firstDate = Carbon::maxValue();
    //     $lastDate = Carbon::minValue();
    //     foreach ($employments as $employment) {
    //         if($employment->beginDate->isBefore($firstDate)) {
    //             $firstDate = $employment->beginDate;
    //         }
    //         if($employment->endDate->isAfter($lastDate)) {
    //             $lastDate = $employment->endDate;
    //         }
    //     }
    //     //echo 'Mindate=' . $firstDate;
    //     //echo 'Maxdate=' . $lastDate;
    //     return $this->calculateTotalSeniorityDays(/*$employee,*/ $functionData,$firstDate, $lastDate);
    // }

    // function hoursOnDate($functionData,$date){
    //     $hours = 0;
    //     foreach ($functionData->employments as $employment) {
    //         Log::debug("date=".$date);
    //         Log::debug("begindate=".$employment->beginDate);
    //         Log::debug("enddate=".$employment->endDate);        
    //         if ($date->isAfter($employment->beginDate->subDay()) && $date->isBefore($employment->endDate->addDay())) {
    //             $hours += $employment->hours;
    //         }
    //     }
    //     Log::debug("=====================");
    //     return $hours;
    // }



    // function calculateTotalSeniorityDays(/*Employee $employee, */EduFunctionData $functionData, Carbon $beginDate, Carbon $endDate) {
    //     $days = 0;
    //     $period = CarbonPeriod::create($beginDate, $endDate);
    //     foreach($period as $date) {
    //         $hours = $this->hoursOnDate($functionData,$date);
    //         if ($hours >= $functionData->educationalFunction->denominator / 2)
    //             $days++;
    //         else if ($hours > 0)
    //             $days += 0.5;
    //     }

    //     return $days;
    // }

    // function getSeniorityDays(Employee $employee, EduFunctionData $functionData){
    //     $employments = $functionData->employments;
    //     $firstDate = Carbon::maxValue();
    //     $lastDate = Carbon::minValue();
    //     foreach ($employments as $employment) {
    //         if($employment->beginDate->isBefore($firstDate)) {
    //             $firstDate = $employment->beginDate;
    //         }
    //         if($employment->endDate->isAfter($lastDate)) {
    //             $lastDate = $employment->endDate;
    //         }
    //     }
    //     //echo 'Mindate=' . $firstDate;
    //     //echo 'Maxdate=' . $lastDate;
    //     return $this->calculateSeniorityDays($employee, $functionData,$firstDate, $lastDate);
    // }

    // function calculateSeniorityDays(Employee $employee, EduFunctionData $functionData, Carbon $beginDate, Carbon $endDate) {
    //     $days = 0;
    //     $interruptionDays = 0;
    //     $interrupted;
    //     $period = CarbonPeriod::create($beginDate, $endDate);
    //     foreach($period as $date)
    //     {
    //         $interrupted = false;
    //         $hours = $this->hoursOnDate($functionData,$date);
    //         foreach ($employee->employmentInterruptions as $interruption) {
    //             if ($date->isAfter($interruption->beginDate->subDay()) && $date->isBefore($interruption->endDate->addDay())){
    //                 if(!$interruption->interruption_type->telt_mee) $hours = 0;
    //                 else{
    //                     $interrupted = true;
    //                     if($interruptionDays>=210){
    //                         $hours = 0;
    //                     }
    //                 }
    //             }
    //             /*if(((date.isAfter(e.getBeginDate().minusDays(1)) && date.isBefore(e.getEndDate().plusDays(1)))) && e.isCounts()){
    //                 interrupted = true;
    //                 if(interruptionDays>=210){
    //                     hours = 0;
    //                 }
    //             }*/
    //         }
    //         if($interrupted){
    //             if($hours >= $functionData->educationalFunction->denominator /2){
    //                 $interruptionDays += 1;
    //             }
    //             else{
    //                 $interruptionDays += 0.5;
    //             }
    //         }
    //         if ($hours >= $functionData->educationalFunction->denominator/2)
    //             $days++;
    //         else if ($hours > 0)
    //             $days += 0.5;
    //     }

    //     return $days;
    // }

    function calculateSenDaysEmp(Employee $employee){
        $allFD = array();
        foreach ($employee->educationalFunctionData as $functiondata)
            $allFD[$functiondata->id] = $this->calculateSenDaysFD($employee,$functiondata);
        return compact('allFD');
    }

    function addToTriggerDates($triggerDates,$date,$periode){
        $key = $date;
        $counter = 0;
        while (array_key_exists($key,$triggerDates)) $key = $key.$counter++;
        $triggerDates[$key] = $periode;
        return $triggerDates;
    }

    function calculateSenDaysFD(Employee $employee,EduFunctionData $functiondata){
        //steek all data in een lijst: key = datum, value verwijst naar de overeenkomstige periode
        $triggerDates = array();
        
        foreach ($functiondata->employments as $e) {
            $e->ptype = 1; //aanstelling
            $triggerDates = $this->addToTriggerDates($triggerDates,$e->beginDate->format('Ymd').'b',$e);
            $triggerDates = $this->addToTriggerDates($triggerDates,$e->endDate->format('Ymd').'e',$e);
        }
        foreach ($employee->employmentInterruptions as $i) {
            $i->ptype = 2; //onderbreking
            $triggerDates = $this->addToTriggerDates($triggerDates,$i->beginDate->format('Ymd').'i',$i);
            $triggerDates = $this->addToTriggerDates($triggerDates,$i->endDate->format('Ymd').'j',$i);
        }
        //Log::debug(compact('triggerDates'));
        
        //sorteer de lijst op datums
        ksort($triggerDates);
        
        
        $huidigeAantalUren = 0;
        $aantalDagen = 0;
        $actievePeriodes = array();
        $onderbreking = null;
        $aantalDagenOnderbreking = 0;
        $aantalDagenOnderbrekingDatTelt = 0;
        $factor = 1.0;
        $laatsteDatum = Carbon::createFromFormat('Ymd',substr(array_keys($triggerDates)[0],0,8)); //eerste element uit de lijst nemen als startpunt
        $teller=0;
        $usedExtraDay = 0;
        foreach (array_keys($triggerDates) as $date) {
            if (count($actievePeriodes) == 0) $usedExtraDay = 0;
            $currentDate = Carbon::createFromFormat('Ymd',substr($date,0,8));
            // Log::debug('teller='.$teller);
            // Log::debug('laatsteDatum='.$laatsteDatum);
            // Log::debug('currentDate='.$currentDate);
            if ($teller>0) {
                $kenletter = substr($date,8,1);
                if (( $kenletter == 'j') || ($kenletter == 'e')){
                //toe te voegen aan aantal dagen = aantal dagen sinds vorige trigger datum * totaal aantal uren voor de huidige actieve periodes
                    $periodeLengte = abs($laatsteDatum->diffInDays($currentDate)) +1 -$usedExtraDay;
                    $usedExtraDay = 1;
                }
                else {
                    $periodeLengte = abs($laatsteDatum->diffInDays($currentDate)) -$usedExtraDay;
                    $usedExtraDay = 0;
                }
                
                $dagenDezePeriode = $periodeLengte * $factor;
                $aantalDagen += $dagenDezePeriode;
                
                if ($onderbreking !== null){
                    $aantalDagenOnderbreking += $dagenDezePeriode;
                    if ($onderbreking->interruption_type->telt_mee) $aantalDagenOnderbrekingDatTelt += $dagenDezePeriode;
                }
                // Log::debug(compact(['periodeLengte','dagenDezePeriode','factor']));
            }
            // werk de lijst van actieve periodes bij: elke periode kan slechts 2 keer getriggerd worden: 1e keer => wordt actief, 2e keer => stopt
            if ($triggerDates[$date]->ptype == 1) {

                if (isset($actievePeriodes) && (!array_key_exists($triggerDates[$date]->id, $actievePeriodes))){
                    // Log::debug("nieuwe actieve periode");
                    $actievePeriodes[$triggerDates[$date]->id] = $triggerDates[$date];
                }
                else
                {
                    // Log::debug("stop actieve periode");
                    unset($actievePeriodes[$triggerDates[$date]->id]);
                }
            }
            else//het is een onderbreking
            {
                //nieuwe onderbreking of einde bestaande onderbreking?
                if ($onderbreking === null){
                    //Log::debug('start onderbreking op '.$date);
                    $onderbreking = $triggerDates[$date];

                }
                else{
                    //Log::debug('stop onderbreking op '.$date);
                    $onderbreking = null;
                }
            }
            //herbereken het huidige aantal uren
            $huidigeAantalUren = 0;
            
            if (isset($actievePeriodes))
                foreach ($actievePeriodes as $id => $p) $huidigeAantalUren += $p->hours;
            // Log::debug('uren='.$huidigeAantalUren);
            // Log::debug('=======================================================');
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
        $aantalDagenOnderbrekingDatTelt = min ($aantalDagenOnderbrekingDatTelt,220);
        //bereken het aantal dagen dat niet mag meetellen: totaal aantal dagen onderbreking MINUS het aantal dat mag meetellen
        $aantalDagenDatNietTelt = $aantalDagenOnderbreking - $aantalDagenOnderbrekingDatTelt;
        // het aantal dagen dat telt als effectief gepresteerd = totaal aantal dagen MINUS het aantal dat niet mag meetellen
        
        $effectieveAantalDagen = $aantalDagen - $aantalDagenDatNietTelt;

        return compact(['aantalDagen','effectieveAantalDagen']);
    }
}
