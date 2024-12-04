<?php

namespace  App\Http\Controllers\accounts;

use App\Http\Controllers\Controller;
use App\Models\Chitti;
use App\Models\Makerlebal;

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
        return view('accounts.maker.maker-listing', compact('chittis', 'geographyOptions', 'notification'));
    }
}
