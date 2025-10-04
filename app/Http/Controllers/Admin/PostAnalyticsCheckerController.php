<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Chitti;
use App\Services\Posts\ChittiListService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PostAnalyticsCheckerController extends Controller
{
    //this method is use for show the listing of live city checker
    public function index(Request $request)
    {
        $query = DB::table('vGeography');
        if ($request->has('search') && $request->input('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('cityNameInEnglish', 'LIKE', "%$search%")
                    ->orWhere('cityNameInUnicode', 'LIKE', "%$search%");
            });
        }

        $mcitys = $query->paginate(20); // Paginate with 10 items per page

        return view('admin.postanalyticschecker.post-analytics-checker-city-listing', compact('mcitys'));
    }

    //this method is use for show the listing of post anlytics checker
    public function postAnalyticsCheckerListing(Request $request, ChittiListService $chittiListService)
    {

        $cityCode = $request->query('cityCode');
        $chittis = $chittiListService->getChittiListingsForAnalytics($request, 'checker', $cityCode);
        $geography = DB::table('vGeography')->select('geography', 'geographycode')->get();

        return view('admin.postanalyticschecker.post-analytics-checker-listing', compact('chittis', 'geography'));
    }

    public function postAnalyticsChckerEdit(Request $request)
    {
        $cid = $request->query('id');
        $cityCode = $request->query('city');
        $numericPart = preg_replace('/[^0-9]/', '', $cityCode);
        $areaId = (int) $numericPart;
        $search = $request->input('search');
        $cacheKey = 'chittis_'.$request->input('search').$request->input('page');

        $chitti = DB::table('chitti')
            ->where('chitti.chittiId', $cid)->first();

        return view('admin.postanalyticschecker.post-analytics-checker-edit', compact('chitti'));
    }

    //this method is use for update postanalytics data
    public function postAnalyticsCheckerUpdate(Request $request, $id)
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
            'monthDay' => 'required|string',
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
            'monthDay' => $request->monthDay,
        ]);

        // return redirect()->route('admin.post-analytics-checker-city-listing')
        //     ->with('success', 'Data updated successfully.');
        return back()->with('success', 'Data updated successfully.');
    }

    //this method is use for approve checker post analytics.
    public function postAnalyticsCheckerApprove(Request $request, $id)
    {
        $checkerId = $request->query('checkerId');
        $City = $request->query('City');
        $uploadDate = date('d-m-Y H:i A');
        $currentDate = date('d-M-y H:i:s');
        $reportDate = date('d-m-Y');
        $chitti = Chitti::findOrFail($id);
        $chitti->update([
            'uploadDataTime' => $currentDate,
            'approveDate' => $reportDate,
            'postStatusMakerChecker' => 'approved',
            'analyticsChecker' => $checkerId,
        ]);

        return redirect()->route('admin.post-analytics-checker-listing', ['cityCode' => $request->cityCode])
            ->with('success', 'Post Analytics have been approved successfully.');

    }

    //this method is use for make page for write the region of return to maker
    public function postAnalyticsCheckerReturnRegion(Request $request, $id)
    {
        $cityCode = $request->query('City');
        $checkerId = $request->query('checkerId');
        $chitti = Chitti::where('areaId', $cityCode)
            ->where('chittiId', $id)
            ->first();

        return view('admin.postanalyticschecker.post-analytics-checker-return-region', compact('chitti'));
    }

    //this method is use for return to post analytics maker with region and soft delete from post analytic checker
    public function postAnalyticsCheckerSendToMaker(Request $request, $id)
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
            // 'checkerReason' => $request->returnToMakerWithRegion,
            'post_anlytics_rtrn_to_mkr' => $request->returnToMakerWithRegion,
            'dateOfReturnToMaker' => date('d-m-Y'),
            'postStatusMakerChecker' => 'return_post_from_checker',
            'post_anlytics_rtrn_to_mkr_id' => 0,
        ]);

        return redirect()->route('admin.post-analytics-checker-listing', ['cityCode' => $request->cityCode])
            ->with('success', 'Post Analytics have been return maker post analytics from checker successfully.');
    }
}
