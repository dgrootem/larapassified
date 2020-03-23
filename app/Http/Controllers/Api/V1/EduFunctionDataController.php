<?php

namespace App\Http\Controllers\Api\V1;

ini_set('max_execution_time', 180);

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\EduFunctionData;
use App\Employment;
use App\School;
use App\Setting;
use Log;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Exception;
use PDF;

class EduFunctionDataController extends Controller
{
    use AccessLogTrait;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->writeLog('Educational Function Data','index','all','full list');
        return EduFunctionData::with(['educationalFunction', 'employee'])->get();
    }

    public function authorizeRO()
    {
        if (Auth::user()->readonly) throw new Exception('not authorized');
    }

    //todo: pagination voorzien
    // zie https://laravel.com/docs/5.5/pagination
    public function fullIndex()
    {
        $this->writeLog('Educational Function Data','fullIndex','all','full list');
        //Log::debug("trying to find some data");
        //return EduFunctionData::with(['educationalFunction','employee'])->get();
        return $this->baseQuery(1,1,1,1,'html')
            ->orderBy('employees.lastName', 'asc')
            ->orderBy('educational_functions.name', 'asc')
            ->get();
    }

    private function getCurrentSetting($name, $index)
    {
        //Log::debug(compact('index'));
        $now = new Carbon();
        //neem de eerste die stopt
        $results = Setting::where('name', $name)->where('van', '<', $now)->where('tot', '>', $now)->orderBy('tot', 'asc')->pluck('value');
        if (count($results) < $index + 1) return null;
        //Log::debug($results);
        return $results[$index];
    }

    private function generatePDF($listToShow,$listName,$schoolId,$listType){
        Log::debug('Generating PDF, listToShow='.$listToShow);
        Log::debug('Generating PDF, listName='.$listName);
        Log::debug('Generating PDF, schoolId='.$schoolId);
        Log::debug('Generating PDF, listType='.$listType);
        $school = School::find($schoolId);
        $gendate = Carbon::now();
        $usebootstrap = 0;
        $pdf = PDF::loadView('pdf.dashboard',compact(['listToShow','listType','gendate','usebootstrap','school']));
        Log::debug('PDF created');
        return $pdf->download($listName.'.pdf');
    }

/*
    public function dashboardPDF(Request $request,$fullList,$schoolId){
        $this->writeLog('Educational Function Data','PDF','dashboard','');
        $nextyear = $this->nextYearTADD($request,$fullList,$schoolId);
        $thisyear = $this->thisYearTADD($request,$fullList,$schoolId);
        $tadd = $this->alreadyTADD($request,$fullList,$schoolId);
        $school = School::find($schoolId);
        $gendate = Carbon::now();
        $usebootstrap = 0;
        $pdf = PDF::loadView('pdf.dashboard',compact(['nextyear','thisyear','tadd','gendate','usebootstrap','school']));
        return $pdf->download('volgendjaar.pdf');
        //return view('pdf.dashboard',compact(['nextyear','thisyear','tadd','gendate']));
        
    }
*/
    public function baseQuery($neededTotal, $neededEffective1,$oudsysteem,$fullList,$schoolId)
    {
        if (is_numeric($schoolId))
            $myschoolid = $schoolId;
        else return null; //lets cause an error :)

        $result= EduFunctionData::join('employees', 'employees.id', '=', 'employee_id')
            ->join('educational_functions', 'educational_function_id', '=', 'educational_functions.id')
            
            ->select(
                'employees.firstname',
                'employees.lastname',
                \DB::raw("CONCAT(employees.lastname,' ',employees.firstname) AS fullname"),
                'edu_function_data.id',
                'educational_functions.name as ambt',
                'edu_function_data.seniority_days',
                'edu_function_data.total_seniority_days',
                \DB::raw('edu_function_data.seniority_days / ' . $neededEffective1 . ' * 100.0 as seniority_days_perc'),
                \DB::raw('edu_function_data.total_seniority_days / ' . $neededTotal . ' * 100.0 as total_seniority_days_perc'),
                'edu_function_data.datum_verbetering_nodig_gezet as werkpunt',
                'edu_function_data.istadd',
                \DB::raw($oudsysteem . ' as oudsysteem')
            )->where('edu_function_data.isbenoemd', '=', 0);
        if ($fullList == 0) $result = $result->where('employees.isActive',1);
        if ($schoolId != -1) /*$result = $result->whereExists(function($query) use ($schoolId){
                $query->select('x')->from('employments')
                        ->where('school_id',$schoolId)
                        ->where('edu_function_data_id','=','edu_function_data.id');
            });*/
            $result = $result->whereRaw("exists (select 'x' from `employments` where `employments`.`school_id` = ".$myschoolid." and `employments`.`edu_function_data_id` = `edu_function_data`.`id`)");
            //$result->dump();
        return $result;
    }

    public function nextYearTADD(Request $request,$output,$fullList,$schoolId)
    {
        Log::debug('==================== nextYearTADD  ====================');
        Log::debug('output='.$output);
        Log::debug('fullList='.$fullList);
        Log::debug('schoolId='.$schoolId);
        $this->writeLog('dashboard','nextYearTADD','full list='.$fullList,'');
        $neededTotal = $this->getCurrentSetting('taddNeededTotal', 1);
        $neededEffective1 = $this->getCurrentSetting('taddNeededEffective', 1);
        $neededEffective2 = $this->getCurrentSetting('taddNeededEffective2', 1);
        // Log::debug('==================== nextYearTADD  ====================');
        //Log::debug(compact(['neededTotal', 'neededEffective1', 'neededEffective2']));
        $results = $this->baseQuery($neededTotal, $neededEffective1,'false',$fullList,$schoolId)
            ->where('edu_function_data.istadd', '=', 0)
            ->where(function($query1){
                $query1->where(function($query){
                    //TODO: helft van $neededTotal nemen? afchecken inhoudelijk!!
                    $neededTotal = $this->getCurrentSetting('taddNeededTotal', 1);
                    $query->whereBetween('edu_function_data.total_seniority_days', array(277, $neededTotal))
                    ->where('edu_function_data.seniority_days', '>=', 200);
                })
                ->orWhere(function($query){
                    $neededEffective1 = $this->getCurrentSetting('taddNeededEffective', 1);
                    $query->whereBetween('edu_function_data.seniority_days', array(200, $neededEffective1))
                    ->where('edu_function_data.total_seniority_days','>=',277);
                });
            })
            
             //TODO: helft van $neededEffective1 nemen? afchecken inhoudelijk!!
            ->orderBy('employees.lastName', 'asc')
            ->orderBy('educational_functions.name', 'asc')
            ;
            Log::debug(compact(['neededTotal','neededEffective1','neededEffective2']));
            Log::debug('nextYearTADD='.$results->toSql());
            //if ($schoolId != -1) $results = $results->distinct('edu_function_data.id');
        $listToShow = $results->get();
        if ($output == 'html')
            return $listToShow;
        else if ($output == 'pdf'){
            return $this->generatePDF($listToShow,'Volgend jaar TADD',$schoolId,1);
        }
        else return null;
    }

    public function thisYearTADD(Request $request,$output,$fullList,$schoolId)
    {
        $this->writeLog('dashboard','thisYearTADD','full list='.$fullList,'');
        $neededTotal = $this->getCurrentSetting('taddNeededTotal', 0);
        $neededEffective1 = $this->getCurrentSetting('taddNeededEffective', 0);
        $neededEffective2 = $this->getCurrentSetting('taddNeededEffective2', 0);
        // Log::debug('==================== thisYearTADD oud ====================');
        // Log::debug(compact(['neededTotal', 'neededEffective1', 'neededEffective2']));
        $volgensoudsysteem = $this->baseQuery($neededTotal, $neededEffective1,'true',$fullList,$schoolId)
            ->where('edu_function_data.istadd', '=', 0)
            ->where(function ($query) {
                $neededTotal = $this->getCurrentSetting('taddNeededTotal', 0);
                $neededEffective1 = $this->getCurrentSetting('taddNeededEffective', 0);
                $query->where('edu_function_data.total_seniority_days', '>=', $neededTotal)
                    ->where('edu_function_data.seniority_days', '>=', $neededEffective1);
            })
            // niet nodig van dit te doen voor mensen die onder oud systeem vallen
            /*->orWhere(function ($query) {
                $neededEffective2 = $this->getCurrentSetting('taddNeededEffective2', 0);
                $query->whereNotNull('edu_function_data.datum_verbetering_nodig_gezet')
                    ->where('edu_function_data.seniority_days_currentyear', '>=', $neededEffective2);
            })*/
            ;

        //if ($schoolId != -1) $volgensoudsysteem = $volgensoudsysteem->distinct('edu_function_data.id');   
            // Log::debug('volgensoudsysteem='.$volgensoudsysteem->toSql());
        // Log::debug('==================== thisYearTADD nieuw ====================');
        
        $volgensnieuwsysteem = $this->baseQuery($neededTotal, $neededEffective1,'false',$fullList,$schoolId)
            ->where('edu_function_data.istadd', '=', 0)
            ->where(function ($query) {
                $neededTotal_new = $this->getCurrentSetting('taddNeededTotal', 1);
                $neededEffective1_new = $this->getCurrentSetting('taddNeededEffective', 1);
                // Log::debug(compact(['neededTotal_new','neededEffective1_new']));
                $query->where('edu_function_data.total_seniority_days', '>=', $neededTotal_new)
                    ->where('edu_function_data.seniority_days', '>=', $neededEffective1_new)
                    ->where(function($query){
                        // we moeten hier een 'OR' gebruiken: 
                        // van zodra ze voldoen aan 1 van onderstaande 
                        // zitten ze niet meer in het oud systeem, 
                        // waar ze al uit gevallen zijn in vorige query en we ze hier dus moeten oppikken
                        
                        $neededTotal_old = $this->getCurrentSetting('taddNeededTotal', 0);
                        $neededEffective1_old = $this->getCurrentSetting('taddNeededEffective', 0);
                        // Log::debug(compact(['neededTotal_old','neededEffective1_old']));
                        $query  ->where('edu_function_data.seniority_days', '<',  $neededEffective1_old)
                                ->orWhere('edu_function_data.total_seniority_days', '<', $neededTotal_old);
                    });
            })
            // DIT STUK VAN DE QUERY IS NOG HELEMAAL NIET JUIST!!!!! deze or moet ook ergens beter genest worden!!
            ->orWhere(function ($query) {
                $neededEffective2 = $this->getCurrentSetting('taddNeededEffective2', 0);
                $query->whereNotNull('edu_function_data.datum_verbetering_nodig_gezet')
                    ->where('edu_function_data.seniority_days_currentyear', '>=', $neededEffective2);
            })
            ;

            //if ($schoolId != -1) $volgensnieuwsysteem = $volgensnieuwsysteem->distinct();
            // Log::debug(compact(['neededTotal','neededEffective1','neededEffective2']));
            // Log::debug('volgensnieuwsysteem='.$volgensnieuwsysteem->toSql());
        $listToShow = $volgensnieuwsysteem
            ->union($volgensoudsysteem)
            ->orderBy('fullname', 'asc')
            ->orderBy('ambt', 'asc')
            ->get();
        if ($output == 'html')
            return $listToShow;
        else if ($output == 'pdf'){
            return $this->generatePDF($listToShow,'Dit jaar TADD',$schoolId,2);
        }
        else return null;
    }

    public function alreadyTADD(Request $request,$output,$fullList,$schoolId)
    {
        $this->writeLog('dashboard','alreadyTADD','full list='.$fullList,'');
        $results = $this->baseQuery(1, 1,true,$fullList,$schoolId)->where('edu_function_data.istadd', '=', 1)
            ->orderBy('employees.lastName', 'asc')
            ->orderBy('educational_functions.name', 'asc');
        //if ($schoolId != -1)  $results = $results->distinct('edu_function_data.id');
        Log::debug($results->toSql());
        $listToShow = $results->get();
        if ($output == 'html')
            return $listToShow;
        else if ($output == 'pdf'){
            return $this->generatePDF($listToShow,'Reeds TADD',$schoolId,3);
        }
        else return null;
    }

    public function show($id)
    {
        $this->writeLog('Educational Function Data','show','id='.$id,'');
        return EduFunctionData::findOrFail($id);
    }

    private function saveAndReturn($eduFunctionData)
    {
        $this->authorizeRO();
        $eduFunctionData->save();
        $eduFunctionData->educationalFunction;
        $eduFunctionData->employments;

        return $eduFunctionData;
    }

    //set ambt
    public function update(Request $request, $id)
    {
        $this->authorizeRO();
        $eduFunctionData = EduFunctionData::findOrFail($id);
        $this->writeLog('Educational Function Data','update','id='.$id,'');
        //Log::debug($request->all());
        $eduFunctionData->educational_function_id = $request['educational_function_id'];
        return $this->saveAndReturn($eduFunctionData);
    }

    //add employment
    public function addEmployment(Request $request, $id)
    {
        $this->authorizeRO();
        $eduFunctionData = EduFunctionData::findOrFail($id);
        $employment = Employment::findOrFail($request['employment_id']);
        $this->writeLog('Educational Function Data','add employment','id='.$id,$employment->beginDateAsDate.'->'.$employment->endDateAsDate);
        $eduFunctionData->employments()->associate($employment);
        return $this->saveAndReturn($eduFunctionData);
    }

    //remove employment
    public function removeEmployment(Request $request, $id, $employment_id)
    {
        $this->authorizeRO();
        $eduFunctionData = EduFunctionData::findOrFail($id);
        $this->writeLog('Educational Function Data','add employment','id='.$id,'removed id='.$employment_id);
        $eduFunctionData->employments()->detach($employment_id);
        return $this->saveAndReturn($eduFunctionData);
    }

    //create a new instance
    public function store(Request $request)
    {
        $this->authorizeRO();
        //Log::debug($request->all());
        
        //link with employee and educational function 
        $eduFunctionData = new EduFunctionData();
        //employment linking will come later
        //$eduFunctionData->employment_id = $employment->id;
        $eduFunctionData->educational_function_id = $request['educational_function_id'];
        $eduFunctionData->employee_id = $request['employee_id'];

        $eduFunctionData->save();
        
        $id = $eduFunctionData->id;
        $eduFunctionData = EduFunctionData::findOrFail($id);

        Log::debug("newly created edufunctiondata id=" . $eduFunctionData->id);
        Log::debug($eduFunctionData);

        $eduFunctionData->employments;
        $eduFunctionData->educationalFunction;
        $this->writeLog('Educational Function Data','create','id='.$id,'function='.$eduFunctionData->educationalFunction->name);

        return $eduFunctionData;
    }

    public function destroy($id)
    {
        $this->authorizeRO();
        $eduFunctionData = EduFunctionData::findOrFail($id);
        $this->writeLog('Educational Function Data','delete','id='.$id,'');
        $eduFunctionData->delete();
        return '';
    }

    public function functionDataForEmployee($id)
    {
        $neededTotal_old = $this->getCurrentSetting('taddNeededTotal', 0);
        $neededEffective1_old = $this->getCurrentSetting('taddNeededEffective', 0);
        $neededTotal_new = $this->getCurrentSetting('taddNeededTotal', 1);
        $neededEffective1_new = $this->getCurrentSetting('taddNeededEffective', 1);
        $hasnew = (($neededTotal_new !== null) && ($neededEffective1_new != null));
        $results =  EduFunctionData::where('employee_id', $id)
            ->with('educationalFunction', 'employments.school')
            ->get();
        foreach ($results as $result) {
            if (($result->seniority_days < $neededEffective1_old) || ($result->total_seniority_days < $neededTotal_old) && $hasnew) {
                Log::debug('oud systeem');
                $result->oudsysteem = true;
                $result->total_seniority_days_perc = $result->total_seniority_days / $neededTotal_new * 100.0;
                $result->seniority_days_perc = $result->seniority_days / $neededEffective1_new * 100.0;
            } else {
                Log::debug('nieuw systeem');
                $result->oudsysteem = false;
                $result->total_seniority_days_perc = $result->total_seniority_days / $neededTotal_old * 100.0;
                $result->seniority_days_perc = $result->seniority_days / $neededEffective1_old * 100.0;
            }
        }
        $this->writeLog('Educational Function Data','functionDataForEmployee','employee id='.$id,'nb results='.$results->count());

        return $results;
    }

    public function addWerkpunt($id)
    {
        $this->authorizeRO();
        $efd = EduFunctionData::findOrFail($id);
        $efd->datum_verbetering_nodig_gezet = Carbon::today();
        $this->writeLog('Educational Function Data','add werkpunt','id='.$id,'');
        $efd->save();
        return $efd;
    }

    public function verwijderWerkpunt($id)
    {
        $this->authorizeRO();
        $efd = EduFunctionData::findOrFail($id);
        $efd->datum_verbetering_nodig_gezet = null;
        $this->writeLog('Educational Function Data','removed werkpunt','id='.$id,'');
        $efd->save();
        return $efd;
    }

    public function addTADD($id)
    {
        $this->authorizeRO();
        $efd = EduFunctionData::findOrFail($id);
        $efd->isTadd = true;
        $this->writeLog('Educational Function Data','add tadd','id='.$id,'');
        $efd->save();
        return $efd;
    }

    public function verwijderTADD($id)
    {
        $this->authorizeRO();
        $efd = EduFunctionData::findOrFail($id);
        $efd->isTadd = false;
        $this->writeLog('Educational Function Data','removed tadd','id='.$id,'');
        $efd->save();
        return $efd;
    }

    public function addBenoemd($id)
    {
        $this->authorizeRO();
        $efd = EduFunctionData::findOrFail($id);
        $efd->isBenoemd = true;
        $this->writeLog('Educational Function Data','add benoemd','id='.$id,'');
        $efd->save();
        return $efd;
    }

    public function verwijderBenoemd($id)
    {
        $this->authorizeRO();
        $efd = EduFunctionData::findOrFail($id);
        $efd->isBenoemd = false;
        $this->writeLog('Educational Function Data','removed benoemd','id='.$id,'');
        $efd->save();
        return $efd;
    }

    public function setProperties(Request $request,$id){
        $this->authorizeRO();
        $efd = EduFunctionData::find($id);
        Log::debug($efd);
        
        $start = $request['startwaarde_tot'];
        $start2 = $request['startwaarde_int'];
        $isTadd = $request['isTadd'];
        $isBenoemd = $request['isBenoemd'];
        $efd->startwaarde_tot = $start;
        $efd->startwaarde_int = $start2;
        $efd->isTadd = $isTadd;
        $efd->isBenoemd = $isBenoemd;
        Log::debug($isTadd);
        Log::debug($isBenoemd);
        $this->writeLog('Educational Function Data','set startwaardes','id='.$id,'tot='.$start.' ,eff='.$start2.' ,tadd='.$isTadd.' ,isBenoemd='.$isBenoemd);
        $efd->save();
        return $efd;
    }
}
