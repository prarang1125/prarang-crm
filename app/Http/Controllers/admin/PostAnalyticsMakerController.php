<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Chitti;
use App\Models\Mcity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PostAnalyticsMakerController extends Controller
{
    //this method is use for show the listing of live city maker
    // public function index()
        // {
        //     $mcitys  = Mcity::where('isActive', 1)->get();
        //     $notification = Chitti::where('post_anlytics_rtrn_to_mkr_id', 0)->count();
        //     $chittis = Chitti::where('post_anlytics_rtrn_to_mkr_id', 0)->get();

        //     return view('admin.postanalyticsmaker.post-analytics-maker-city-listing', compact('mcitys', 'notification', 'chittis'));
    // }

    public function index(Request $request)
    {
        // $query = Mcity::where('isActive', 1);
        $query = DB::table('vGeography');
        // Search functionality
        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('geography', 'LIKE', "%{$search}%")
                    ->orWhere('geography', 'LIKE', "%{$search}%");
            });
        }

        // Paginate results
        $mcitys = $query->paginate(20);
        $notification = Chitti::where('post_anlytics_rtrn_to_mkr_id', 0)->count();

        return view('admin.postanalyticsmaker.post-analytics-maker-city-listing', compact('mcitys', 'notification'));
    }

    //this method is use for show the listing post analytics maker according to city
    /*public function postAnalyticsMakerListing(Request $request)
    {

        // Get the cityCode from the request
        $cityCode = $request->query('cityCode');
        $numericPart = preg_replace('/[^0-9]/', '', $cityCode);
        $areaId = (int) $numericPart;

        // $chittis = Chitti::with('city')->where('cityId', $areaId)->get();
        $search = $request->input('search');
        $cacheKey = 'chittis_'.$request->input('search').$request->input('page');
        $cacheDuration = 180;
        $chittis = DB::table('chitti as ch')
        ->select('ch.*', 'vg.*', 'vCg.*', 'city.*', 'ch.chittiId as chittiId')
        ->join('vChittiGeography as vCg', 'ch.chittiId', '=', 'vCg.chittiId')
        ->join('vGeography as vg', 'vg.geographycode', '=', 'vCg.Geography')
        ->join('mcity as city', 'city.cityId', '=',  'ch.areaId')
        ->where('areaId', $areaId)
        ->where('finalStatus', 'approved')
        ->when($search, function ($query, $search) {
            $query->where(function ($query) use ($search) {
                $query->where('ch.Title', 'like', "%{$search}%")
                    ->orWhere('ch.SubTitle', 'like', "%{$search}%");
            });
        })
        ->orderByDesc(DB::raw("STR_TO_DATE(ch.dateSentToUploader, '%d-%b-%y %H:%i:%s')"))
        ->paginate(20)
        ->appends([
            'cityCode' => $areaId,
            'search' => $search,
        ]);
        // dd($chittis);
        // $notification = Chitti::where('post_anlytics_rtrn_to_mkr_id', 0)->count();
        return view('admin.postanalyticsmaker.post-analytics-maker-listing', compact('chittis'));
    }*/

    public function postAnalyticsMakerListing(Request $request)
    {
        // Get the cityCode from the request
        $cityCode = $request->query('cityCode');
        $numericPart = preg_replace('/[^0-9]/', '', $cityCode);
        $areaId = (int) $numericPart;

        // Get the search term from the request
        $search = $request->input('search');
        $cacheKey = 'chittis_'.$request->input('search').$request->input('page');

        // Query the data
        $chittis = DB::table('chitti as ch')
            ->select('ch.*', 'vg.*', 'vCg.*', 'city.*', 'ch.chittiId as chittiId')
            ->join('vChittiGeography as vCg', 'ch.chittiId', '=', 'vCg.chittiId')
            ->join('vGeography as vg', 'vg.geographycode', '=', 'vCg.Geography')
            ->join('mcity as city', 'city.cityId', '=', 'ch.areaId')
            ->where('areaId', $areaId)
            ->where('finalStatus', '!=', 'deleted')
            ->where('postStatusMakerChecker', '!=', 'send_to_post_checker')
            ->when($search, function ($query, $search) {
                return $query->where(function ($q) use ($search) {
                    $q->where('ch.Title', 'like', "%{$search}%")
                        ->orWhere('ch.SubTitle', 'like', "%{$search}%");
                });
            })
            ->orderByDesc(DB::raw("STR_TO_DATE(ch.createDate, '%d-%b-%y %H:%i:%s')"))
            ->paginate(20)
            ->appends([
                'cityCode' => $cityCode,
                'search' => $search,
            ]);

            // dd($chittis);
        return view('admin.postanalyticsmaker.post-analytics-maker-listing', compact('chittis'));
    }


    //this method is use for  show the post analytics maker edit data and page
    public function postAnalyticsMakerEdit(Request $request)
    {
        $cid = $request->query('id');
        $cityCode = $request->query('city');

        $chitti =  DB::table('chitti as ch')
        ->select('ch.*', 'vg.*', 'vCg.*', 'city.*', 'ch.chittiId as chittiId')
        ->join('vChittiGeography as vCg', 'ch.chittiId', '=', 'vCg.chittiId')
        ->join('vGeography as vg', 'vg.geographycode', '=', 'vCg.Geography')
        ->join('mcity as city', 'city.cityId', '=', 'ch.areaId')
        ->where('ch.chittiId', $cid)->first();

        return view('admin.postanalyticsmaker.post-analytics-maker-create', compact('chitti'));
    }

    // this method is use for update the post analytics method
    public function postAnalyticsMakerUpdate(Request $request, $id)
    {
        $validated = $request->validate([
            'postNumber' => 'required|string',
            'titleOfPost' => 'required|string',
            'uploadDate' => 'required|date',
            'numberOfDays' => 'required|integer',
            'nameOfCity' => 'required|string',
            'advertisementInPost' => 'required|in:Yes,No',
            'postViewershipFrom' => 'required|date',
            'to' => 'required|date',
            'citySubscribers' => 'required|integer',
            'total' => 'required|integer',
            'prarangApplication' => 'nullable|string',
            'facebookLinkClick' => 'nullable|integer',
            'websiteGd' => 'nullable|string',
            'monthDay' => 'nullable|string',
            'email' => 'nullable|string',
            'sponsored' => 'nullable|string',
            'instagram' => 'nullable|string',
        ]);

        // dd($request->all());
        $currentDateTime = getUserCurrentTime();
        $chitti = Chitti::findOrFail($id);
        $chitti->update([
            'postViewershipDate' => $request->postViewershipFrom,
            'postViewershipDateTo' => $request->to,
            'noofDaysfromUpload' => $request->numberOfDays,
            'citySubscriber' => $request->citySubscribers,
            'totalViewerCount' => $request->total,
            'prarangApplication' => $request->prarangApplication,
            'websiteCount' => $request->websiteGd,
            'emailCount' => $request->email,
            'sponsoredBy' => $request->sponsored,
            'instagramCount' => $request->instagram,
            'advertisementPost' => $request->advertisementInPost,
            'analyticsMaker' => Auth::guard('admin')->user()->userId,
            'monthDay' => $request->monthDay,
            'fb_link_click' => $request->facebookLinkClick,
            'postStatusMakerChecker' => 'send_to_post_checker',
            'post_anlytics_rtrn_to_mkr_id' => 1,
            // 'createDate' => $currentDateTime
        ]);

        // Redirect with success message
        return redirect()->route('admin.post-analytics-maker-city-listing')
            ->with('success', 'Data updated successfully.');

    }

    public function postAnalyticsListReturnFromCheckerL(Request $request)
    {
        $search = $request->input('search');

        // Query to fetch data with search functionality
        $query = Chitti::query();

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('Title', 'LIKE', "%{$search}%")
                    ->orWhere('SubTitle', 'LIKE', "%{$search}%");
            });
        }

        // Paginate the results
        $chittis = $query->where('post_anlytics_rtrn_to_mkr_id', 0)
            ->paginate(20); // Adjust the number per page as needed

        return view('admin.postanalyticsmaker.post-analytics-rejected-from-checker-listing', compact('chittis', 'search'));
    }
}
