<?php

namespace App\Http\Controllers\Api\V1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use \App\Rating;
use \App\RatingType;
use Carbon\Carbon;

class RatingController extends Controller
{
    use AccessLogTrait;


    public function authorizeRO(){
        if (Auth::user()->readonly) throw new Exception('not authorized'); 
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
        $this->writeLog('Ratings','create',$request['edu_function_data_id'],'');
        $rating = new Rating();
        $rating = $this->saveRating($rating,$request);
        return $rating;
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
        $this->writeLog('Ratings','update',$request['edu_function_data_id'],'');
        $rating = Rating::findOrFail($id);
        $rating = $this->saveRating($rating,$request);
        return $rating;
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
        $rating = Rating::findOrFail($id);
        $this->writeLog('Ratings','delete',$rating->edu_function_data_id,'');
        $rating->delete();
        return '';
    }

    private function saveRating(Rating $rating,Request $request){
        $ratingType = ratingType::findOrFail($request['rating_type_id']);

        $rating->edu_function_data_id = $request['edu_function_data_id'];
        $rating->positief = $request['positief'];
        $rating->datum = Carbon::parse(substr($request['datum'],0,10));
        $rating->ratingType()->associate($ratingType);
        $rating->save();
        $id = $rating->id;
        $rating = Rating::findOrFail($id);
        $rt = $rating->ratingtype;
        return $rating;
    }
}
