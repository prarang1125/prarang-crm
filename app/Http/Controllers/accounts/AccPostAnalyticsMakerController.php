<?php

namespace App\Http\Controllers\accounts;

use App\Http\Controllers\Controller;
use App\Models\Chitti;
use App\Models\Mcity;
use App\Services\Posts\ChittiListService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AccPostAnalyticsMakerController extends Controller
{
    //this method is use for show the listing of live city maker
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

        return view('accounts.postanalyticsmaker.acc-post-analytics-maker-city-listing', compact('mcitys', 'notification'));
    }

    public function accPostAnalyticsMakerListing(Request $request, ChittiListService $chittiListService)
    {
        // Get the cityCode from the request
        $cityCode = $request->query('cityCode');

        $chittis = $chittiListService->getChittiListingsForAnalytics($request, 'maker', $cityCode);

        return view('accounts.postanalyticsmaker.acc-post-analytics-maker-listing', compact('chittis'));
    }

    //this method is use for  show the post analytics maker edit data and page
    public function accPostAnalyticsMakerEdit(Request $request)
    {
        // dd('your your data is here');
        $cid = $request->query('id');
        $cityCode = $request->query('city');

        $chitti = DB::table('chitti as ch')
            ->select('ch.*', 'vg.*', 'vCg.*', 'city.*', 'ch.chittiId as chittiId')
            ->join('vChittiGeography as vCg', 'ch.chittiId', '=', 'vCg.chittiId')
            ->join('vGeography as vg', 'vg.geographycode', '=', 'vCg.Geography')
            ->join('mcity as city', 'city.cityId', '=', 'ch.areaId')
            ->where('ch.chittiId', $cid)->first();

        return view('accounts.postanalyticsmaker.acc-post-analytics-maker-create', compact('chitti'));
    }

    // this method is use for update the post analytics method
    public function accPostAnalyticsMakerUpdate(Request $request, $id)
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
            'analyticsMaker' => Auth::user()->userId,
            'monthDay' => $request->monthDay,
            'fb_link_click' => $request->facebookLinkClick,
            'postStatusMakerChecker' => 'send_to_post_checker',
            'post_anlytics_rtrn_to_mkr_id' => 1,
            // 'createDate' => $currentDateTime
        ]);

        // Redirect with success message
        return redirect()->route('accounts.analyticsmaker-dashboard')
            ->with('success', 'Data updated successfully.');

    }

    public function accPostAnalyticsListReturnFromCheckerL(Request $request)
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

        return view('accounts.postanalyticsmaker.acc-post-analytics-rejected-from-checker-listing', compact('chittis', 'search'));
    }
}
