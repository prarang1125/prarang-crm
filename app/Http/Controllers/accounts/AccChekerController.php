<?php

namespace App\Http\Controllers\accounts;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\ImageUploadService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use App\Models\Chitti;
use App\Models\Makerlebal;
use App\Models\Chittitagmapping;
use App\Models\Mtag;
use App\Models\Mregion;
use App\Models\Mcity;
use App\Models\Mcountry;
use App\Models\Facity;
use App\Models\Chittiimagemapping;
use App\Models\Chittigeographymapping;


class AccChekerController extends Controller
{
    public function accIndexMain(Request $request)
    {
        // Search query from request
        $search = $request->input('search');

        // Query builder with search and filter conditions
        $chittis = DB::table('chitti as ch')
        ->select('ch.*','vg.*', 'vCg.*', 'ch.chittiId as chittiId')
           ->join('vChittiGeography as vCg', 'ch.chittiId', '=', 'vCg.chittiId')
           ->join('vGeography as vg', 'vg.geographycode', '=', 'vCg.Geography')
            ->whereNotNull('Title')
            ->where('Title', '!=', '')
            ->where('checkerStatus', '!=', '')
            ->whereIn('checkerStatus', ['maker_to_checker'])
            ->where('makerStatus', 'sent_to_checker')
            ->whereNotIn('finalStatus', ['approved', 'deleted'])
            ->when($search, function ($query) use ($search) {
                $query->where('Title', 'LIKE', "%{$search}%") // Search in English
                    ->orWhere('createDate', 'LIKE', "%".mb_strtolower($search, 'UTF-8')."%"); // Handle Unicode (Hindi, etc.)
            })
            ->orderByDesc('dateOfCreation')
            ->paginate(10); // Adjust the number of items per page

        $geographyOptions = Makerlebal::whereIn('id', [5, 6, 7])->get();
        $notification = Chitti::whereNotNull('uploaderReason')
        ->where('uploaderReason', '!=', '')
        ->where('uploaderStatus', 'sent_to_checker')
        ->where('finalStatus', 'sent_to_checker')
        ->count();
        return view('accounts.checker.acc-checker-listing', compact('chittis', 'geographyOptions', 'notification'));
    }

    public function accIndex(Request $request , $id)
    {
        $search = $request->input('search');

        // Fetch geography options
        $geographyOptions = Makerlebal::whereIn('id', [5, 6, 7])->get();

        // Query the Chitti model
        $chittis = Chitti::with(['geographyMappings.region', 'geographyMappings.city', 'geographyMappings.country'])
            ->when($search, function ($query, $search) {
                // Search in both English and Hindi titles
                $query->where('Title', 'LIKE', "%{$search}%")
                    ->orWhere('createDate', 'LIKE', "%{$search}%");
            })
            ->whereNotNull('Title')
            ->where('Title', '!=', '')
            ->where('checkerStatus', '!=', '')
            ->whereIn('checkerStatus', ['maker_to_checker'])
            ->where('makerStatus', 'sent_to_checker')
            ->select('chittiId', 'Title', 'TitleHindi', 'dateOfCreation', 'finalStatus', 'checkerStatus')
            ->paginate(30); // Pagination with 10 items per page

        $notification = Chitti::whereNotNull('uploaderReason')
        ->where('uploaderReason', '!=', '')
        ->where('uploaderStatus', 'sent_to_checker')
        ->where('finalStatus', 'sent_to_checker')
        ->count();
        return view('accounts.checker.acc-checker-listing', compact('chittis', 'geographyOptions', 'notification'));
    }

    #this method is use for accounts checker edit
    public function accCheckerEdit($id)
    {

        $chitti = Chitti::with('chittiimagemappings', 'geographyMappings', 'facity')
        ->whereNotIn('finalStatus', ['approved', 'deleted'])
        ->whereNot('checkerStatus','sent_to_uploader')->findOrFail($id);

        $image = $chitti->chittiimagemappings()->first();
        // $chittiTagMapping = Chittitagmapping::where('chittiId', $id)->first();
        $chittiTagMapping = Chittitagmapping::with('tag.tagcategory')->where('chittiId', $id)->first();
        $subTag=$chittiTagMapping->tag->tagCategoryId;
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

        return view('accounts.checker.acc-checker-edit', compact('chitti', 'image','subTag','geographyOptions', 'regions', 'cities', 'countries', 'geographyMapping', 'facityValue', 'chittiTagMapping', 'timelines', 'manSenses', 'manInventions', 'geographys', 'faunas', 'floras'));
    }

    public function accCheckerUpdate(Request $request, $id, ImageUploadService $imageUploadService)
    {
        $validator = Validator::make($request->all(), [
            'content'   => 'required|string',
            'makerImage' => 'nullable|image|max:2048',
            'geography' => 'required',
            'c2rselect' => [
            'required',
            function ($attribute, $value, $fail) {
                if ($value === 'Select Select') {
                    $fail('The ' . str_replace('_', ' ', $attribute) . ' field must be properly selected.');
                }
            }],
            'title'     => ['required', 'string', 'max:255', 'regex:/^[a-zA-Z\s]+$/'],
            'subtitle' => ['required', 'string', 'max:255', 'regex:/^[a-zA-Z\s]+$/'],
            'forTheCity' => 'required|boolean',
            'tagId'     => 'required'
        ]);

        if ($validator->passes()) {

            $currentDateTime = getUserCurrentTime();
            $chitti = Chitti::findOrFail($id);

            // Update Chitti record
            if ($request->action === 'send_to_uploader') {

                $chitti->update([
                    'uploaderStatus'   => 'sent_to_uploader',
                    'checkerStatus' => 'sent_to_uploader',
                    'dateSentToUploader' => $currentDateTime,
                    'updated_at'    => $currentDateTime,
                    'updated_by'    => Auth::user()->userId,
                ]);

                // Redirect to the checker listing
                return redirect()->route('accounts.checker-dashboard')
                    ->with('success', 'Sent to Uploader successfully.');
            }

            else {

                $area_id = $request->c2rselect;
                $areaIdCode = '';
                if ($request->geography == 6) { //6 is use for city
                    $areaIdCode = 'c'.$area_id;
                } elseif ($request->geography == 5) { //5 is use for region
                    $areaIdCode = 'r'.$area_id;
                } elseif ($request->geography == 7) { // 7 is use for country
                    $areaIdCode = 'con'.$area_id;
                }
                $currentDate = date("d-M-y H:i:s");
                $chitti->update([
                    'dateOfReturnToMaker'       => $currentDate,
                    'returnDateMaker'           => $currentDate,
                    'makerStatus'               => 'sent_to_checker',
                    'checkerId'                 => Auth::user()->userId,
                    'description'   => $request->content,
                    'Title'         => $request->title,
                    'SubTitle'      => $request->subtitle,
                    'checkerStatus'   => 'maker_to_checker',
                    'updated_at'    => $currentDateTime,
                    'updated_by'    => Auth::user()->userId,
                    'cityId' => $area_id,
                    'areaId' => $area_id,
                    'geographyId' => $request->geography,
                ]);

                // Update Facity record
                Facity::where('from_chittiId', $id)->update([
                    'value'         => $request->forTheCity,
                    'updated_at'    => $currentDateTime,
                    'updated_by'    => Auth::user()->userId,
                ]);

                // Update image if provided
                if ($request->hasFile('makerImage')) {
                    $uploadImage = $imageUploadService->uploadImage($request->file('makerImage'), $chitti->chittiId);
                    if (isset($uploadImage['error']) && $uploadImage['error'] === true) {
                        DB::rollBack();
                        return redirect()->back()->with('error', 'Error while image uploading, please try again.');
                    }

                    // Update Chitti Image Mapping
                    Chittiimagemapping::where('chittiId', $id)->update([
                        'imageName'     => $uploadImage['path'],
                        'imageUrl'      => $uploadImage['full_url'],
                        'accessUrl'     => $uploadImage['path'],
                        'updated_at'    => $currentDateTime,
                        'updated_by'    => Auth::user()->userId,
                    ]);
                }

                // Update Geography Mapping
                Chittigeographymapping::where('chittiId', $id)->update([
                    'areaId'        => $request->c2rselect,
                    'geographyId'   => $request->geography,
                    'updated_at'    => $currentDateTime,
                    'updated_by'    => Auth::user()->userId,
                ]);

                // Update Tag Mapping
                Chittitagmapping::where('chittiId', $id)->update([
                    'tagId'         => $request->tagId,
                    'updated_at'    => $currentDateTime,
                    'updated_by'    => Auth::user()->userId,
                ]);
                return redirect()->route('accounts.checker-dashboard')->with('success', 'Chitti Post have been updated successfully.');
            }
        } else {
            return redirect()->back()
                ->withInput()
                ->withErrors($validator);
        }
    }

    #this method is use for return from accounts checker to maker with region
    public function accCheckerChittiReturnMakerRegion(Request $request, $id)
    {
        $cityCode   = $request->query('City');
        $checkerId  = $request->query('checkerId');

        $chitti = Chitti::where('areaId', $cityCode)
            ->where('chittiId', $id)
            ->first();
        return view('accounts.checker.acc-chitti-checker-return-to-maker-with-region', compact('chitti'));
    }

    #this method is use for update eturn from checker to maker with region
    public function accCheckerChittiSendToMaker(Request $request, $id)
    {
        $checkerId   = $request->query('checkerId');
        $City        = $request->query('City');
        $currentDate = date("d-M-y H:i:s");

        $validated = $request->validate([
            'returnChittiToMakerWithRegion'   => 'required|string',
        ]);

        $chitti = Chitti::findOrFail($id);
        $chitti->update([
            'dateOfReturnToMaker'       => $currentDate,
            'returnDateMaker'           => $currentDate ,
            'makerStatus'               => 'return_chitti_post_from_checker',
            'checkerId'                 => $checkerId,
            'checkerReason'             => $request->returnChittiToMakerWithRegion,
            'return_chitti_post_from_checker'    => $request->returnChittiToMakerWithRegion,
            'postStatusMakerChecker'             => 'return_chitti_post_from_checker',
            'return_chitti_post_from_checker_id' => 1,
            'checkerStatus'         => '',
            'uploaderStatus'        => '',
            'finalStatus'           => '',
        ]);
        return redirect()->route('accounts.checker-dashboard')->with('success', 'Chitti Post have been return to maker from checker successfully');
    }


    public function accChittiListReturnFromUploaderL(Request $request)
    {
        $search = $request->input('search');
        $cacheKey = 'chittis_'.$request->input('search').$request->input('page');
        $cacheDuration = 180;
        $chittis = DB::table('chitti as ch')
            ->select('ch.*', 'vg.*', 'vCg.*', 'ch.chittiId as chittiId')
            ->join('vChittiGeography as vCg', 'ch.chittiId', '=', 'vCg.chittiId')
            ->join('vGeography as vg', 'vg.geographycode', '=', 'vCg.Geography')->whereNotNull('Title')
            ->where('finalStatus', '!=', 'deleted')
            ->where('finalStatus', '=', 'sent_to_checker')
            ->where('uploaderReason', '!=', '')
            ->where('finalStatus', '=', 'sent_to_checker')

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

        $notification = Chitti::whereNotNull('uploaderReason')
        ->where('uploaderReason', '!=', '')
        ->where('uploaderStatus', 'sent_to_checker')
        ->where('finalStatus', 'sent_to_checker')
        ->count();
        $geographyOptions = Makerlebal::whereIn('id', [5, 6, 7])->get();

        return view('accounts.checker.acc-chitti-rejected-from-uploader-listing', compact('geographyOptions', 'notification', 'chittis'));
    }

}
