<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Mcity;
use App\Models\Chitti;
use App\Models\Muser;
use App\Models\Chittigeographymapping;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class PostAnalyticsCheckerController extends Controller
{
    #this method is use for show the listing of live city checker
    public function index()
    {
        $mcitys = Mcity::where('isActive', 1)->get();
        return view('admin.postanalyticschecker.post-analytics-checker-city-listing', compact('mcitys'));
    }

    public function postAnalyticsCheckerListing(Request $request)
    {
        $cityCode = $request->query('cityCode');
        $chittis = Chitti::where('areaId', $cityCode)->get();
        foreach ($chittis as $chitti) {
            $anlyticsMaker =  $chitti->analyticsMaker;
        }

        $muserMaker= '';
        if(!empty($anlyticsMaker)){
            $musers = Muser::where('userId', $anlyticsMaker)->get();
            foreach($musers as $username)
            {
                $muserMaker = $username->firstName.' '.$username->lastName;
            }
        }
        return view('admin.postanalyticschecker.post-analytics-checker-listing', compact('chittis', 'muserMaker'));
    }

    public function postAnalyticsChckerEdit(Request $request)
    {
        $cid = $request->query('id');
        $cityCode = $request->query('city');
        $chitti  = Chitti::where('chittiId', $cid)->where('areaId', $cityCode)->first();
        // dd($chitti);
        return view('admin.postanalyticschecker.post-analytics-checker-edit', compact('chitti'));
    }
}

?>
