<?php

namespace App\Http\Controllers\accounts;

use App\Http\Controllers\Controller;
use App\Models\Chitti;
use App\Services\Posts\ChittiListService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AccPostAnalyticsCheckerController extends Controller
{
    //this method is use for show the listing of live city checker
    public function index(Request $request)
    {
        $query = DB::table('vGeography');
        if ($request->has('search') && $request->input('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('citynameInEnglish', 'LIKE', "%$search%")
                    ->orWhere('citynameInUnicode', 'LIKE', "%$search%");
            });
        }

        $mcitys = $query->paginate(20); // Paginate with 10 items per page

        return view('accounts.postanalyticschecker.acc-post-analytics-checker-city-listing', compact('mcitys'));
    }

    //this method is use for show the listing of post anlytics checker
    public function accPostAnalyticsCheckerListing(Request $request, ChittiListService $chittiListService)
    {

        $cityCode = $request->query('cityCode');
        $chittis = $chittiListService->getChittiListingsForAnalytics($request, 'checker', $cityCode);

        return view('accounts.postanalyticschecker.acc-post-analytics-checker-listing', compact('chittis'));
    }

    public function accPostAnalyticsChckerEdit(Request $request)
    {
        $cid = $request->query('id');
        // dd($cid);
        // $cityCode = $request->query('city');

        // $chitti = Chitti::where('chittiId', $cid)->first();

        $cityCode = $request->query('cityCode');
        $numericPart = preg_replace('/[^0-9]/', '', $cityCode);
        $areaId = (int) $numericPart;

        // Get the search term from the request
        $search = $request->input('search');
        $cacheKey = 'chittis_'.$request->input('search').$request->input('page');

        // Query the data
        $chitti = DB::table('chitti as ch')
            ->select('ch.*', 'vg.*', 'vCg.*', 'user.*', 'city.*', 'ch.chittiId as chittiId')
            ->join('vChittiGeography as vCg', 'ch.chittiId', '=', 'vCg.chittiId')
            ->join('vGeography as vg', 'vg.geographycode', '=', 'vCg.Geography')
            ->join('mcity as city', 'city.cityId', '=', 'ch.areaId')
            ->leftJoin('muser as user', 'user.userId', '=', 'ch.analyticsMaker')
            ->where('ch.chittiId', $cid)->first();

        return view('accounts.postanalyticschecker.acc-post-analytics-checker-edit', compact('chitti'));
    }

    //this method is use for update postanalytics data
    public function accPostAnalyticsCheckerUpdate(Request $request, $id)
    {
        $checkerId = $request->query('checkerId');
        $City = $request->query('City');

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
            'websiteGd' => 'nullable|string',
            'email' => 'nullable|string',
            'sponsored' => 'nullable|string',
            'instagram' => 'nullable|string',
        ]);

        $total = [$request->citySubscribers, $request->prarangApplication, $request->websiteGd, $request->email, $request->instagram];
        $totalSum = array_sum(array_map('intval', $total));

        $chitti = Chitti::findOrFail($id);
        $chitti->update([
            'postViewershipDate' => $request->postViewershipFrom,
            'postViewershipDateTo' => $request->to,
            'noofDaysfromUpload' => $request->numberOfDays,
            'citySubscriber' => $request->citySubscribers,
            'totalViewerCount' => $totalSum,
            'prarangApplication' => $request->prarangApplication,
            'websiteCount' => $request->websiteGd,
            'emailCount' => $request->email,
            'sponsoredBy' => $request->sponsored,
            'instagramCount' => $request->instagram,
            'advertisementPost' => $request->advertisementInPost,
            'analyticsChecker' => $checkerId,
        ]);

        return redirect()->route('accounts.analyticschecker-dashboard')
            ->with('success', 'Data updated successfully.');
        // return back()->with('success', 'Data updated successfully.');
    }

    //this method is use for approve checker post analytics.
    public function accPostAnalyticsCheckerApprove(Request $request, $id)
    {
        $checkerId = $request->query('checkerId');
        $City = $request->query('City');
        $uploadDate = date('d-m-Y H:i A');
        $currentDate = date('d-M-y H:i:s');
        $reportDate = date('d-m-Y');
        $chitti = Chitti::findOrFail($id);
        $chitti->update([
            'dateOfApprove' => $uploadDate,
            'uploadDataTime' => $currentDate,
            'approveDate' => $reportDate,
            'postStatusMakerChecker' => 'approved',
            'analyticsChecker' => $checkerId,
        ]);

        return redirect()->route('accounts.analyticschecker-dashboard')
            ->with('success', 'Post Analytics have been approved successfully.');
        // return back()->with('success', 'Post Analytics have been approved successfully');
    }

    //this method is use for make page for write the region of return to maker
    public function accPostAnalyticsCheckerReturnRegion(Request $request, $id)
    {

        $cityCode = $request->query('City');
        $checkerId = $request->query('checkerId');
        $chitti = Chitti::where('areaId', $cityCode)
            ->where('chittiId', $id)
            ->first();

        return view('accounts.postanalyticschecker.acc-post-analytics-checker-return-region', compact('chitti'));
    }

    //this method is use for return to post analytics maker with region and soft delete from post analytic checker
    public function accPostAnalyticsCheckerSendToMaker(Request $request, $id)
    {
        $checkerId = $request->query('checkerId');
        $City = $request->query('City');
        $currentDate = date('d-M-y H:i:s');

        $validated = $request->validate([
            'returnToMakerWithRegion' => 'required|string',
        ]);

        $chitti = Chitti::findOrFail($id);
        $chitti->update([
            'analyticsChecker' => $checkerId,
            'checkerReason' => $request->returnToMakerWithRegion,
            'post_anlytics_rtrn_to_mkr' => $request->returnToMakerWithRegion,
            'dateOfReturnToMaker' => $currentDate,
            'postStatusMakerChecker' => 'return_post_from_checker',
            'post_anlytics_rtrn_to_mkr_id' => 0,
        ]);

        return redirect()->route('accounts.analyticschecker-dashboard')
            ->with('success', 'Post Analytics have been return maker post analytics from checker successfully.');
        // return back()->with('success', 'Post Analytics have been return maker post analytics from checker successfully');
    }
}
