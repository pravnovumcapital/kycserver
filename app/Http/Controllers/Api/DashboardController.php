<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Nationality;
class DashboardController extends Controller
{
    public function citizenshipList()
    {
    	//dd(Nationality::get()->toArray());
    	//dd(Nationality::pluck('nationality','nation_id'));
        $nationalities = Nationality::orderBy('nationality','ASC')->pluck('nationality','nation_id')->toArray();
    	$countriesList = Nationality::orderBy('country','ASC')->pluck('country','nation_id')->toArray();

    	//dd($nationalities);
    	$citizenships = array();
    	foreach ($nationalities as $key => $nationality) {
    		$citizenshipArray['nationality_id'] = $key;
    		$citizenshipArray['nationality'] = $nationality;
    		array_push($citizenships, $citizenshipArray);
    	}
        $countries = array();
        foreach ($countriesList as $key => $country) {
            $countryArray['country_id'] = $key;
            $countryArray['country'] = $country;
            array_push($countries, $countryArray);
        }
    	//dd($citizenships);
    	return response()->json(["code"=>200,"citizenships" =>$citizenships,'countries'=>$countries],200);
    }
}
