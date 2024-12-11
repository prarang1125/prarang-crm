<?php

namespace  App\Http\Controllers\accounts;

use App\Http\Controllers\Controller;
use App\Models\Chitti;
use App\Models\Makerlebal;
use App\Models\Mtag;
use App\Models\Mcity;
use App\Models\Mregion;
use App\Models\Mcountry;


class AccMakerController extends Controller
{
    #this method is use for show the listing of maker
    public function index()
    {
        $chittis = Chitti::with(['geographyMappings.region', 'geographyMappings.city', 'geographyMappings.country'])
        ->whereNotNull('Title')
        ->where('Title', '!=', '')
        ->where('makerStatus', '=', 'sent_to_checker')
        // ->where('checkerStatus', '=','maker_to_checker')
        ->select('*')
        ->get();
        $notification = Chitti::where('return_chitti_post_from_checker_id', 1)->count();
        $geographyOptions = Makerlebal::whereIn('id', [5, 6, 7])->get();
        return view('accounts.maker.acc-maker-listing', compact('chittis', 'geographyOptions', 'notification'));
    }

    #this method is use for account maker make new post
    public function accMakerRegister()
    {
        // Fetch data from the Mtag table based on tagCategoryId
        $timelines = Mtag::where('tagCategoryId', 1)->get();
        $manSenses = Mtag::where('tagCategoryId', 2)->get();
        $manInventions = Mtag::where('tagCategoryId', 3)->get();
        $geographys = Mtag::where('tagCategoryId', 4)->get();
        $faunas = Mtag::where('tagCategoryId', 5)->get();
        $floras = Mtag::where('tagCategoryId', 6)->get();
        $geographyOptions = Makerlebal::whereIn('id', [5, 6, 7])->get();
        // Fetch all regions, cities, and countries
        $regions = Mregion::all();
        $cities = Mcity::all();
        $countries = Mcountry::all();

        return view('accounts.maker.acc-maker-register', compact('timelines', 'manSenses', 'manInventions', 'geographys', 'faunas', 'floras', 'geographyOptions', 'regions', 'cities', 'countries'));
    }

    public function accChittiListReturnFromCheckerL()
    {
        // $chittis = Chitti::where('makerStatus', 'return_chitti_post_from_checker')->get();
        $chittis = Chitti::with(['geographyMappings.region', 'geographyMappings.city', 'geographyMappings.country'])
        ->whereNotNull('Title')
        ->where('Title', '!=', '')
        ->where('return_chitti_post_from_checker_id',  1)
        ->select('*')
        ->get();
        $notification = Chitti::where('return_chitti_post_from_checker_id', 1)->count();
        $geographyOptions = Makerlebal::whereIn('id', [5, 6, 7])->get();
        return view('accounts.maker.acc-chitti-rejected-from-checker-listing', compact('geographyOptions', 'notification', 'chittis'));
    }
}
