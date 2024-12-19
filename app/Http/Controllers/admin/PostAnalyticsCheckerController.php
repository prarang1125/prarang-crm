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
    public function index(Request $request)
    {
        $query = Mcity::where('isActive', 1);

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

    #this method is use for show the listing of post anlytics checker
    public function postAnalyticsCheckerListing(Request $request)
    {
        $cityCode = $request->query('cityCode');
        $chittis = Chitti::where('areaId', $cityCode)
            ->whereIn('postStatusMakerChecker', ['send_to_post_checker', 'approved'])
            ->where('post_anlytics_rtrn_to_mkr_id', 1)
            ->get();
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
        $chitti  = Chitti::where('chittiId', $cid)->first();
        return view('admin.postanalyticschecker.post-analytics-checker-edit', compact('chitti'));
    }

    #this method is use for update postanalytics data
    public function postAnalyticsCheckerUpdate(Request $request, $id)
    {
        $checkerId = $request->query('checkerId');
        $City = $request->query('City');

        $validated = $request->validate([
            'postNumber'   => 'required|string',
            'titleOfPost'  => 'required|string',
            'uploadDate'   => 'required|date',
            'numberOfDays' => 'required|integer',
            'nameOfCity'   => 'required|string',
            'advertisementInPost' => 'required|in:Yes,No',
            'postViewershipFrom'  => 'required|date',
            'to'     => 'required|date',
            'citySubscribers'    => 'required|integer',
            'total'  => 'required|integer',
            'prarangApplication' => 'nullable|string',
            'websiteGd' => 'nullable|string',
            'email'     => 'nullable|string',
            'sponsored' => 'nullable|string',
            'instagram' => 'nullable|string',
        ]);

        $total = [$request->citySubscribers, $request->prarangApplication, $request->websiteGd, $request->email, $request->instagram];
        $totalSum = array_sum(array_map('intval', $total));

        $chitti = Chitti::findOrFail($id);
        $chitti->update([
            'postViewershipDate'    => $request->postViewershipFrom,
            'postViewershipDateTo'  => $request->to,
            'noofDaysfromUpload'    => $request->numberOfDays,
            'citySubscriber'        => $request->citySubscribers,
            'totalViewerCount'      => $totalSum,
            'prarangApplication'    => $request->prarangApplication,
            'websiteCount'          => $request->websiteGd,
            'emailCount'            => $request->email,
            'sponsoredBy'           => $request->sponsored,
            'instagramCount'        => $request->instagram,
            'advertisementPost'     => $request->advertisementInPost,
            'analyticsChecker'      => $checkerId,
        ]);
        return back()->with('success', 'Data updated successfully.');
    }

    #this method is use for approve checker post analytics.
    public function postAnalyticsCheckerApprove(Request $request, $id)
    {
        $checkerId   = $request->query('checkerId');
        $City        = $request->query('City');
        $uploadDate  = date("d-m-Y H:i A");
        $currentDate = date("d-M-y H:i:s");
        $reportDate  = date("d-m-Y");
        $chitti = Chitti::findOrFail($id);
        $chitti->update([
            'dateOfApprove'     => $uploadDate,
            'uploadDataTime'    => $currentDate,
            'approveDate'       => $reportDate,
            'postStatusMakerChecker'       => 'approved',
            'analyticsChecker'  => $checkerId
        ]);
        return back()->with('success', 'Post Analytics have been approved successfully');
    }

    #this method is use for make page for write the region of return to maker
    public function postAnalyticsCheckerReturnRegion(Request $request, $id)
    {
        $cityCode   = $request->query('City');
        $checkerId  = $request->query('checkerId');
        // dd($id);
        $chitti = Chitti::where('areaId', $cityCode)
            ->where('chittiId', $id)
            ->first();
        return view('admin.postanalyticschecker.post-analytics-checker-return-region', compact('chitti'));
    }

    #this method is use for return to post analytics maker with region and soft delete from post analytic checker
    public function postAnalyticsCheckerSendToMaker(Request $request, $id)
    {
        $checkerId   = $request->query('checkerId');
        $City        = $request->query('City');
        $currentDate = date("d-M-y H:i:s");

        $validated = $request->validate([
            'returnToMakerWithRegion'   => 'required|string',
        ]);

        $chitti = Chitti::findOrFail($id);
        $chitti->update([
            'analyticsChecker'          => $checkerId,
            'checkerReason'             => $request->returnToMakerWithRegion,
            'post_anlytics_rtrn_to_mkr' => $request->returnToMakerWithRegion,
            'dateOfReturnToMaker'       => $currentDate,
            'postStatusMakerChecker'    => 'return_post_from_checker',
            'post_anlytics_rtrn_to_mkr_id' => 0,
        ]);
        return back()->with('success', 'Post Analytics have been return maker post analytics from checker successfully');
    }
}

?>
