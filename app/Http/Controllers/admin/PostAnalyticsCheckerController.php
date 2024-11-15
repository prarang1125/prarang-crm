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

        $chitti = Chitti::findOrFail($id);
        $chitti->update([
            'postViewershipDate'    => $request->postViewershipFrom,
            'postViewershipDateTo'  => $request->to,
            'noofDaysfromUpload'    => $request->numberOfDays,
            'citySubscriber'        => $request->citySubscribers,
            'totalViewerCount'      => $request->total,
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
            'finalStatus'       => 'approved',
            'analyticsChecker'  => $checkerId
        ]);
        return back()->with('success', 'Post Analytics have been approved successfully');
    }
}

?>
