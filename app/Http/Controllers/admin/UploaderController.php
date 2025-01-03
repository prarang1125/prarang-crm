<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Chitti;
use App\Models\Chittigeographymapping;
use App\Models\Chittiimagemapping;
use App\Models\Chittitagmapping;
use App\Models\ColorInfo;
use App\Models\Facity;
use App\Models\Makerlebal;
use App\Models\Mcity;
use App\Models\Mcountry;
use App\Models\Mregion;
use App\Models\Mtag;
use App\Services\ImageUploadService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class UploaderController extends Controller
{
    public function indexMain(Request $request)
    {
        // dd('your data is here');
        $search = $request->input('search');
        $cacheKey = 'chittis_'.$request->input('search').$request->input('page');
        $cacheDuration = 180;
        $chittis = DB::table('chitti as ch')
            ->select('ch.*', 'vg.*', 'vCg.*', 'ch.chittiId as chittiId')
            ->join('vChittiGeography as vCg', 'ch.chittiId', '=', 'vCg.chittiId')
            ->join('vGeography as vg', 'vg.geographycode', '=', 'vCg.Geography')->whereNotNull('Title')
            ->where('Title', '!=', '')
            ->whereIn('uploaderStatus', ['sent_to_uploader', 'approved'])
            ->when($search, function ($query, $search) {
                $query->where(function ($query) use ($search) {
                    $query->where('Title', 'like', "%{$search}%")
                        ->orWhere('SubTitle', 'like', "%{$search}%")
                        ->orWhere('createDate', 'LIKE', '%'.$search.'%');
                });
            })
            ->whereNotIn('finalStatus', ['deleted'])
            ->orderByDesc('ch.chittiId')
            ->orderByDesc(DB::raw("STR_TO_DATE(ch.dateSentToUploader, '%d-%b-%y %H:%i:%s')"))
            ->paginate(30); // Adjust the number per page

        $geographyOptions = Makerlebal::whereIn('id', [5, 6, 7])->get();

        return view('admin.uploader.uploader-listing', compact('chittis', 'geographyOptions', 'search'));
    }

    //this method is use for show the listing of maker
    // public function index($id)
        // {
        //     $chittis = Chitti::with(['geographyMappings.region', 'geographyMappings.city', 'geographyMappings.country'])
        //     ->where('chittiId', $id)
        //     ->whereNotNull('Title')
        //     ->where('Title', '!=', '')
        //     ->where('uploaderStatus', '!=', '')
        //     ->where('uploaderStatus', '=', 'sent_to_uploader')
        //     // ->where('finalStatus', '=', 'approved')
        //     // ->where('finalStatus', '=', 'sent_to_uploader')
        //     ->select('chittiId', 'Title', 'dateOfCreation', 'finalStatus', 'checkerStatus','uploaderStatus')
        //     ->get();
        //     $geographyOptions = Makerlebal::whereIn('id', [5, 6, 7])->get();
        //     return view('admin.uploader.uploader-listing', compact('chittis', 'geographyOptions'));
    // }

    public function index(Request $request)
    {
        // dd('your data is here');
        $search = $request->input('search');
        $cacheKey = 'chittis_'.$request->input('search').$request->input('page');
        $cacheDuration = 180;
        $chittis = DB::table('chitti as ch')
            ->select('ch.*', 'vg.*', 'vCg.*', 'ch.chittiId as chittiId')
            ->join('vChittiGeography as vCg', 'ch.chittiId', '=', 'vCg.chittiId')
            ->join('vGeography as vg', 'vg.geographycode', '=', 'vCg.Geography')->whereNotNull('Title')
            ->where('Title', '!=', '')
            ->whereIn('uploaderStatus', ['sent_to_uploader', 'approved'])
            ->when($search, function ($query, $search) {
                $query->where(function ($query) use ($search) {
                    $query->where('Title', 'like', "%{$search}%")
                        ->orWhere('SubTitle', 'like', "%{$search}%")
                        ->orWhere('createDate', 'LIKE', '%'.$search.'%');
                });
            })
            ->whereNotIn('finalStatus', ['deleted', 'sent_to_checker'])
            ->orderByDesc('ch.chittiId')
            ->orderByDesc(DB::raw("STR_TO_DATE(ch.dateOfCreation, '%d-%b-%y %H:%i:%s')"))
            ->paginate(30); // Adjust the number per page

        $geographyOptions = Makerlebal::whereIn('id', [5, 6, 7])->get();

        // Fetch geography options
        $geographyOptions = Makerlebal::whereIn('id', [5, 6, 7])->get();

        // Return view with data
        return view('admin.uploader.uploader-listing', compact('chittis', 'geographyOptions'));
    }

    //this method is use for maker make new post
    /**public function makerRegister()
    {
        // Fetch data from the Mtag table based on tagCategoryId
        $timelines = Mtag::where('tagCategoryId', 1)->get();
        $manSenses = Mtag::where('tagCategoryId', 2)->get();
        $manInventions = Mtag::where('tagCategoryId', 3)->get();
        $geographys = Mtag::where('tagCategoryId', 4)->get();
        $faunas = Mtag::where('tagCategoryId', 5)->get();
        $floras = Mtag::where('tagCategoryId', 6)->get();
        $geographyOptions = Makerlebal::whereIn('id', [5, 6, 7])->get();
        // Fetch all regions, cities, and countries
        $regions = Mregion::all();
        $cities = Mcity::all();
        $countries = Mcountry::all();

        return view('admin.maker.maker-register', compact('timelines', 'manSenses', 'manInventions', 'geographys', 'faunas', 'floras', 'geographyOptions', 'regions', 'cities', 'countries'));
    }*/

    //this method is use for store maker data
    /**public function makerStore(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'content'   => 'required|string|max:1000',
            'makerImage' => 'required|image|max:2048',
            'geography' => 'required',
            'c2rselect' => [
            'required',
            function ($attribute, $value, $fail) {
                if ($value === 'Select Select') {
                    $fail('The ' . str_replace('_', ' ', $attribute) . ' field must be properly selected.');
                }
            },
            'title'     => 'required|string|max:255',
            'subtitle' => 'required|string|max:255',
            'forTheCity' => 'required|boolean',
            'isCultureNature' => 'required|boolean',
        ]);

        if($validator->passes())
        {
            $currentDateTime = getUserCurrentTime();
            $chitti = new Chitti();
            $chitti->languageId = 1;
            $chitti->description = $request->content;
            $chitti->dateOfCreation =  $currentDateTime;
            $chitti->createDate =  $currentDateTime;
            $chitti->Title = $request->title;
            $chitti->SubTitle = $request->subtitle;
            $chitti->makerId = Auth::guard('admin')->user()->userId;
            $chitti->makerStatus = 'sent_to_checker';
            $chitti->finalStatus = 'sent_to_checker';
            $chitti->created_at = $currentDateTime;
            $chitti->created_by = Auth::guard('admin')->user()->userId;
            $chitti->save();
            // get last inserted id
            $lastId = $chitti->chittiId;

            $facity = new Facity();
            $facity->value = $request->forTheCity;
            $facity->from_chittiId = $lastId;
            $facity->created_at = $currentDateTime;
            $facity->created_by = Auth::guard('admin')->user()->userId;
            $facity->save();

            if($request->hasFile('makerImage')){
                $makerImage = $request->file('makerImage');
                $makerImageName = time() . '_' . $makerImage->getClientOriginalName();
                $makerImage->move(public_path('uploads/maker_image/'), $makerImageName);
                $url = public_path('uploads/maker_image/')."".$makerImageName;
                $serviceAccessUrl = "admin.prarang.in/".$url;
            }

            $chittiimagemapping = new Chittiimagemapping();
            $chittiimagemapping->imageName = $makerImageName;
            $chittiimagemapping->imageUrl = $serviceAccessUrl;
            $chittiimagemapping->accessUrl = $url;
            $chittiimagemapping->isActive = '1';
            $chittiimagemapping->chittiId = $lastId;
            $chittiimagemapping->isDefult = 'true';
            $chittiimagemapping->imageTag = $makerImageName;
            $chittiimagemapping->created_at = $currentDateTime;
            $chittiimagemapping->created_by = Auth::guard('admin')->user()->userId;
            $chittiimagemapping->save();

            $chittigeographymapping = new Chittigeographymapping();
            $chittigeographymapping->areaId = $request->c2rselect;
            $chittigeographymapping->geographyId = $request->geography;
            $chittigeographymapping->chittiId = $lastId;
            $chittigeographymapping->created_at = $currentDateTime;
            $chittigeographymapping->created_by = Auth::guard('admin')->user()->userId;
            $chittigeographymapping->save();

            $chittitagmapping = new Chittitagmapping();
            $chittitagmapping->chittiId = $lastId;
            $chittitagmapping->tagId = $request->isCultureNature;
            $chittitagmapping->created_at = $currentDateTime;
            $chittitagmapping->created_by = Auth::guard('admin')->user()->userId;
            $chittitagmapping->save();
            return redirect()->route('admin.maker-listing')->with('success', 'Post created successfully.');
        }else{
            return redirect()->route('admin.maker-register')
                ->withErrors($validator)
                ->withInput();
        }
    }**/

    public function uploaderEdit($id)
    {
        $chitti = Chitti::with('chittiimagemappings', 'geographyMappings', 'facity', 'writerColor', 'readerColor')->findOrFail($id);
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
        $colorOptions = ColorInfo::where('emotionType', 1)->get();
        $readerOptions = ColorInfo::where('emotionType', 0)->get();

        // dd($chitti->readerColor);
        return view('admin.uploader.uploader-edit', compact('chitti', 'subTag', 'image', 'geographyOptions', 'regions', 'cities', 'countries', 'geographyMapping', 'facityValue', 'chittiTagMapping', 'timelines', 'manSenses', 'manInventions', 'geographys', 'faunas', 'floras', 'colorOptions', 'readerOptions'));
    }

    public function uploaderUpdate(Request $request, $id, ImageUploadService $imageUploadService)
    {

        $validator = Validator::make($request->all(), [
            'content' => 'required|string',
            'makerImage' => 'nullable|image|max:2048',
            'geography' => 'required',
            'c2rselect' => [
            'required',
            function ($attribute, $value, $fail) {
                if ($value === 'Select Select') {
                    $fail('The ' . str_replace('_', ' ', $attribute) . ' field must be properly selected.');
                }
            }],
            'title' => ['required', 'string', 'max:255', 'regex:/^[a-zA-Z\s]+$/'],
            'subtitle' => ['required', 'string', 'max:255', 'regex:/^[a-zA-Z\s]+$/'],
            'forTheCity' => 'required|boolean',
            // 'isCultureNature' => 'required|boolean',
            'tagId' => 'required',
            'writercolor' => 'required',
            // 'reader'   => 'required',
        ]);
        $readerValue = $request->input('reader');
        if (is_string($readerValue)) {
            $decoded = json_decode($readerValue, true);
            if (json_last_error() === JSON_ERROR_NONE && isset($decoded['id'])) {
                // If valid JSON, extract the `id`
                $readerValue = $decoded['id'];
            }
        }
        if ($validator->passes()) {

            $currentDateTime = getUserCurrentTime();
            $date = Carbon::now()->format('Y-m-d');
            $dateofcreation = Carbon::now()->format('d-M-y H:i:s');
            if (isset($data['reader']) && is_string($data['reader'])) {
                $reader = json_decode($data['reader'], true);
                $data['reader'] = $reader['id'] ?? null; // Use the `id` field from the decoded object
            }

            // Update Chitti record with approved
            $chitti = Chitti::findOrFail($id);
            if ($request->action === 'approvd') {
                $chitti->update([
                    'description' => $request->content,
                    'Title' => $request->title,
                    'SubTitle' => $request->subtitle,
                    'checkerStatus' => 'sent_to_uploader',
                    'finalStatus' => 'approved',
                    'updated_at' => $currentDateTime,
                    'updated_by' => Auth::guard('admin')->user()->userId,
                    'dateOfApprove' => Carbon::parse($currentDateTime)->format('d-m-Y g:i A'),
                    'uploaderId' => Auth::guard('admin')->user()->userId,
                ]);

                return redirect()->route('admin.uploader-listing', ['id' => $chitti->chittiId])->with('success', 'Uploader updated successfully.');
            } else {
                $area_id = $request->c2rselect;
                $areaIdCode = '';
                if ($request->geography == 6) { //6 is use for city
                    $areaIdCode = 'c'.$area_id;
                } elseif ($request->geography == 5) { //5 is use for region
                    $areaIdCode = 'r'.$area_id;
                } elseif ($request->geography == 7) { // 7 is use for country
                    $areaIdCode = 'con'.$area_id;
                }
                // Update Chitti record
                $chitti->update([
                    'description' => $request->content,
                    'Title' => $request->title,
                    'SubTitle' => $request->subtitle,
                    'dateSentToUploader' => $dateofcreation,
                    'updated_at' => $currentDateTime,
                    'updated_by' => Auth::guard('admin')->user()->userId,
                    'uploaderId' => Auth::guard('admin')->user()->userId,
                    'cityId' => $area_id,
                    'areaId' => $area_id,
                    'geographyId' => $request->geography,
                    'writercolor' => $request->writercolor,
                    'color_value' => $readerValue,
                ]);

                // Update Facity record
                Facity::where('from_chittiId', $id)->update([
                    'value' => $request->forTheCity,
                    'updated_at' => $currentDateTime,
                    'updated_by' => Auth::guard('admin')->user()->userId,
                ]);

                if (isset($request->Videourl)) {
                    // dd($request->Videourl);
                    $data = $this->videoPost($request->Videourl);
                    Chittiimagemapping::where('chittiId', $id)->update([
                        'imageName' => $data['video-image'],
                        'imageUrl' => $data['video-image'],
                        'VideoURL' => $data['video-url'],
                        'VideoId' => $data['video-id'],
                        'VideoExist' => 1,
                        'updated_at' => $currentDateTime,
                        'updated_by' => Auth::guard('admin')->user()->userId,
                    ]);
                } else {
                    if ($request->hasFile('makerImage')) {
                        $uploadImage = $imageUploadService->uploadImage($request->file('makerImage'), $chitti->chittiId);
                        if (isset($uploadImage['error']) && $uploadImage['error'] === true) {
                            DB::rollBack();

                            return redirect()->back()->with('error', 'Error while image uploading, please try again.');
                        }dd('data3');
                        Chittiimagemapping::where('chittiId', $id)->update([
                            'imageName' => $uploadImage['path'],
                            'imageUrl' => $uploadImage['full_url'],
                            'accessUrl' => $uploadImage['path'],
                            'updated_at' => $currentDateTime,
                            'updated_by' => Auth::guard('admin')->user()->userId,
                        ]);
                    }
                }

                // Update Geography Mapping
                Chittigeographymapping::where('chittiId', $id)->update([
                    'areaId' => $request->c2rselect,
                    'geographyId' => $request->geography,
                    'updated_at' => $currentDateTime,
                    'updated_by' => Auth::guard('admin')->user()->userId,
                ]);

                // Update Tag Mapping
                Chittitagmapping::where('chittiId', $id)->update([
                    'tagId' => $request->tagId,
                    'updated_at' => $currentDateTime,
                    'updated_by' => Auth::guard('admin')->user()->userId,
                ]);

                return redirect()->back()->with('success', 'Uploader updated successfully.');
            }
        } else {
            return redirect()->back()
                ->withInput()
                ->withErrors($validator);
        }
    }

    private function videoPost($vidUrl)
    {

        parse_str(parse_url($vidUrl, PHP_URL_QUERY), $queryParams);
        $data['video-id'] = $queryParams['v'] ?? null;
        $data['video-url'] = '<iframe width="100%" height="500" src="https://www.youtube.com/embed/'.$data['video-id'].'"
        title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
        referrerpolicy="strict-origin-when-cross-origin" allowfullscreen></iframe>';
        $data['video-image'] = 'https://img.youtube.com/vi/'.$data['video-id'].'/0.jpg';

        return $data;
    }

    //this method is use for return from uploader to checker with region
    public function uploaderChittiReturnCheckerRegion(Request $request, $id)
    {
        // dd($id);
        $cityCode = $request->query('City');
        $checkerId = $request->query('checkerId');

        $chitti = Chitti::where('chittiId', $id)
            ->first();

        return view('admin.uploader.chitti-uploader-return-to-checker-with-region', compact('chitti'));
    }

    //this method is use for update eturn from checker to maker with region
    public function uploaderChittiSendToChecker(Request $request, $id)
    {
        // dd('your data is here');
        $checkerId = $request->query('checkerId');
        $City = $request->query('City');
        $currentDate = date('d-M-y H:i:s');

        $validated = $request->validate([
            'returnChittiToCheckerWithRegion' => 'required|string',
        ]);
        // dd($request->returnChittiToCheckerWithRegion);
        $chitti = Chitti::findOrFail($id);
        $chitti->update([
            'uploaderStatus' => 'sent_to_checker',
            'checkerStatus'  => '',
            'uploaderId'     => Auth::guard('admin')->user()->userId,
            'uploaderReason' => $request->returnChittiToCheckerWithRegion,
            'dateOfReturnToChecker' => $currentDate,
            'finalStatus'     => 'sent_to_checker',
        ]);

        return redirect('admin/uploader/uploader-listing')->with('success', 'Chitti Post have been return to checker from Uploader successfully');
    }
}
