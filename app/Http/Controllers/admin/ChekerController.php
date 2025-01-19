<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Chitti;
use App\Models\Chittigeographymapping;
use App\Models\Chittiimagemapping;
use App\Models\Chittitagmapping;
use App\Models\Facity;
use App\Models\Makerlebal;
use App\Models\Mcity;
use App\Models\Mcountry;
use App\Models\Mregion;
use App\Models\Mtag;
use App\Services\ImageUploadService;
use App\Services\Posts\ChittiListService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class ChekerController extends Controller
{
    public function indexMain(Request $request, ChittiListService $chittiListService)
    {
        $chittis = $chittiListService->getChittiListings($request, 'sent_to_checker', 'checker');

        $notification = Chitti::where('uploaderStatus', 'sent_to_checker')
            ->whereNotIn('finalStatus', ['approved', 'deleted'])
            // ->where('finalStatus', 'sent_to_checker')
            ->count();

        return view('admin.checker.checker-listing', compact('chittis', 'notification'));
    }

    public function checkerEdit($id)
    {

         $chitti = Chitti::with('chittiimagemappings', 'geographyMappings', 'facity')
            ->whereNotIn('finalStatus', ['approved', 'deleted'])
            ->whereNot('checkerStatus', 'sent_to_uploader')->findOrFail($id);

        $image = $chitti->chittiimagemappings()->first();

        $chittiTagMapping = Chittitagmapping::with('tag.tagcategory')->where('chittiId', $id)->first();
        $subTag = $chittiTagMapping->tag->tagCategoryId;
        $timelines = Mtag::where('tagCategoryId', 1)->get();
        $manSenses = Mtag::where('tagCategoryId', 2)->get();
        $manInventions = Mtag::where('tagCategoryId', 3)->get();
        $geographys = Mtag::where('tagCategoryId', 4)->get();
        $faunas = Mtag::where('tagCategoryId', 5)->get();
        $floras = Mtag::where('tagCategoryId', 6)->get();

        $geographyOptions = Makerlebal::whereIn('id', [5, 6, 7])->get();
        $regions = Mregion::all();
        $cities = Mcity::all();
        $countries = Mcountry::all();
        $geographyMapping = $chitti->geographyMappings->first();

        $facityValue = $chitti->facity ? $chitti->facity->value : null;

        $chittiTagMapping = Chittitagmapping::with('tag.tagcategory')->where('chittiId', $id)->first();

        return view('admin.checker.checker-edit', compact('chitti', 'image', 'subTag', 'geographyOptions', 'regions', 'cities', 'countries', 'geographyMapping', 'facityValue', 'chittiTagMapping', 'timelines', 'manSenses', 'manInventions', 'geographys', 'faunas', 'floras'));
    }

    public function checkerUpdate(Request $request, $id, ImageUploadService $imageUploadService)
    {
            $currentDateTime = getUserCurrentTime();
            $date = Carbon::now()->format('Y-m-d');
            $dateofcreation = Carbon::now()->format('d-M-y H:i:s');

            $chitti = Chitti::findOrFail($id);
            if ($request->action === 'send_to_uploader') {

                $chitti->update([
                    'uploaderStatus' => 'sent_to_uploader',
                    'checkerStatus' => 'sent_to_uploader',
                    'updated_at' => $currentDateTime,
                    'dateSentToUploader' => $dateofcreation,
                    'updated_by' => Auth::guard('admin')->user()->userId,

                ]);

                return redirect()->route('admin.checker-listing')
                    ->with('success', 'Sent to Uploader successfully.');
            }
        }


    //this method is use for return from checker to maker with region
    public function checkerChittiReturnMakerRegion(Request $request, $id)
    {
        // dd($id);
        $cityCode = $request->query('City');
        $checkerId = $request->query('checkerId');

        $chitti = Chitti::where('chittiId', $id)
            ->first();

        return view('admin.checker.chitti-checker-return-to-maker-with-region', compact('chitti'));
    }

    //this method is use for update eturn from checker to maker with region
    public function checkerChittiSendToMaker(Request $request, $id)
    {
        $checkerId = $request->query('checkerId');
        $City = $request->query('City');
        $currentDate = date('d-M-y H:i:s');

        $validated = $request->validate([
            'returnChittiToMakerWithRegion' => 'required|string',
        ]);
        $date = Carbon::now()->format('Y-m-d');
        $dateofcreation = Carbon::now()->format('d-M-y H:i:s');
        $chitti = Chitti::findOrFail($id);
        $chitti->update([
            'dateOfReturnToMaker' => $dateofcreation,
            'returnDateMaker' => $currentDate,
            'makerStatus' => 'return_chitti_post_from_checker',
            'checkerId' => $checkerId,
            'checkerReason' => $request->returnChittiToMakerWithRegion,
            'return_chitti_post_from_checker' => $request->returnChittiToMakerWithRegion,
            'postStatusMakerChecker' => 'return_chitti_post_from_checker',
            'return_chitti_post_from_checker_id' => 1,
            'checkerStatus' => '',
            'uploaderStatus' => '',
            'finalStatus' => '',
        ]);

        return redirect('admin/checker/checker-listing')->with('success', 'Chitti Post have been return to maker from checker successfully');
    }

    public function chittiListReturnFromUploaderL(Request $request)
    {

        $search = $request->input('search');
        $cacheKey = 'chittis_'.$request->input('search').$request->input('page');
        $cacheDuration = 180;
        $chittis = DB::table('chitti as ch')
            ->select('ch.*', 'vg.*', 'vCg.*', 'ch.chittiId as chittiId')
            ->join('vChittiGeography as vCg', 'ch.chittiId', '=', 'vCg.chittiId')
            ->join('vGeography as vg', 'vg.geographycode', '=', 'vCg.Geography')->whereNotNull('Title')
            ->where('uploaderStatus', '=', 'sent_to_checker')
            ->whereNotIn('finalStatus', ['approved', 'deleted'])

            ->when($search, function ($query, $search) {
                $query->where(function ($query) use ($search) {
                    $query->where('Title', 'like', "%{$search}%")
                        ->orWhere('SubTitle', 'like', "%{$search}%")
                        ->orWhere('createDate', 'LIKE', '%'.$search.'%');
                });
            })
            // ->orderByDesc('ch.chittiId')
            ->orderByDesc(DB::raw("STR_TO_DATE(ch.dateOfCreation, '%d-%b-%y %H:%i:%s')"))
            ->paginate(30); // Adjust the number per page

        $notification = Chitti::where('uploaderStatus', 'sent_to_checker')
            ->whereNotIn('finalStatus', ['approved', 'deleted'])
            ->count();
        // $geographyOptions = Makerlebal::whereIn('id', [5, 6, 7])->get();

        return view('admin.checker.chitti-rejected-from-uploader-listing', compact('notification', 'chittis'));
    }
}
