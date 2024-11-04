<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Mcity;
use App\Models\Chitti;
use App\Models\Chittigeographymapping;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class PostAnalyticsMakerController extends Controller
{
    #this method is use for show the listing of live city maker
    public function index()
    {
        $mcitys = Mcity::where('isActive', 1)->get();
        return view('admin.postanalyticsmaker.post-analytics-maker-city-listing', compact('mcitys'));
    }


    #this method is use for show the listing post analytics maker according to city
    public function postAnalyticsMakerListing(Request $request)
    {
        // Get the cityCode from the request
        $cityCode = $request->query('cityCode');
        // $numericPart = preg_replace('/[^0-9]/', '', $cityCode);
        // $areaId = (int) $numericPart;
        // $chittis = Chitti::with('city')->where('cityId', $areaId)->get();
        $chittis = Chitti::where('areaId', $cityCode)->get();
        return view('admin.postanalyticsmaker.post-analytics-maker-listing', compact('chittis'));
    }

    public function postAnalyticsMakerEdit($id)
    {
        // dd($id);
        $chittis = Chitti::where('chittiId', $id)->get();
        dd($chittis);
        return view('admin.postanalyticsmaker.post-analytics-maker-create', compact('chittis'));
    }

}

?>
