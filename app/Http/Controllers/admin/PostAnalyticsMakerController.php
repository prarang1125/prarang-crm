<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Chitti;
use App\Services\Posts\ChittiListService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PostAnalyticsMakerController extends Controller
{
    public function index(Request $request)
    {

        $query = DB::table('vGeography');

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

    public function postAnalyticsMakerListing(Request $request, ChittiListService $chittiListService)
    {
        // Get the cityCode from the request
        $cityCode = $request->query('cityCode');

        $geography = DB::table('vGeography')->select('geography', 'geographycode')->get();

        $chittis = $chittiListService->getChittiListingsForAnalytics($request, 'maker', $cityCode);

        return view('admin.postanalyticsmaker.post-analytics-maker-listing', compact('chittis', 'geography'));
    }

    //this method is use for  show the post analytics maker edit data and page
    public function postAnalyticsMakerEdit(Request $request)
    {
        $cid = $request->query('id');
        $cityCode = $request->query('city');

        $chitti = DB::table('chitti as ch')
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
        return redirect()->route('admin.post-analytics-maker-listing', ['cityCode' => $request->cityCode])
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
