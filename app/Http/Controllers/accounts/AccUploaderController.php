<?php

namespace App\Http\Controllers\accounts;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use App\Models\Chitti;
use App\Models\Makerlebal;
use App\Models\Chittitagmapping;
use App\Models\Mtag;
use App\Models\Mregion;
use App\Models\Mcountry;
use App\Models\Mcity;
use App\Models\Facity;
use App\Models\Chittiimagemapping;
use App\Models\Chittigeographymapping;
use App\Services\ImageUploadService;
use Illuminate\Support\Facades\DB;
use App\Models\ColorInfo;
use Carbon\Carbon;

class AccUploaderController extends Controller
{
    public function accIndexMain(Request $request)
    {
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
            // ->orderByDesc('ch.chittiId')
            ->orderByDesc(DB::raw("STR_TO_DATE(ch.dateSentToUploader, '%d-%b-%y %H:%i:%s')"))
            ->paginate(30); // Adjust the number per page

        $geographyOptions = Makerlebal::whereIn('id', [5, 6, 7])->get();
        return view('accounts.uploader.acc-uploader-listing', compact('chittis', 'geographyOptions', 'search'));
    }

    #this method is use for show the accounts listing of uploader
    // public function index($id)
        // {
        //     $chittis = Chitti::with(['geographyMappings.region', 'geographyMappings.city', 'geographyMappings.country'])
        //     ->where('chittiId', $id)
        //     ->whereNotNull('Title')
        //     ->where('Title', '!=', '')
        //     ->where('uploaderStatus', '!=', '')
        //     ->whereIn('uploaderStatus', ['checker_to_uploader', 'sent_to_uploader'])
        //     // ->where('finalStatus', '=', 'approved')
        //     // ->where('finalStatus', '=', 'sent_to_uploader')
        //     ->select('*')
        //     ->get();
        //     $geographyOptions = Makerlebal::whereIn('id', [5, 6, 7])->get();
        //     return view('accounts.uploader.acc-uploader-listing', compact('chittis', 'geographyOptions'));
    // }

    public function accUploaderEdit($id)
    {
        $chitti = Chitti::with('chittiimagemappings', 'geographyMappings', 'facity', 'writerColor', 'readerColor')->findOrFail($id);
        $image = $chitti->chittiimagemappings()->first();
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
        $colorOptions = ColorInfo::where('emotionType', 1)->get();
        $readerOptions = ColorInfo::where('emotionType', 0)->get();

        return view('accounts.uploader.acc-uploader-edit', compact('chitti','subTag', 'image', 'geographyOptions', 'regions', 'cities', 'countries', 'geographyMapping', 'facityValue', 'chittiTagMapping', 'timelines', 'manSenses', 'manInventions', 'geographys', 'faunas', 'floras', 'colorOptions', 'readerOptions'));
    }

    // public function accUploaderUpdate(Request $request, $id)
    // {
        //     $validator = Validator::make($request->all(), [
        //         'content'   => 'required|string|max:2000',
        //         'makerImage' => 'nullable|image|max:2048',
        //         'geography' => 'required',
        //         'c2rselect' => [
            // 'required',
            // function ($attribute, $value, $fail) {
            //     if ($value === 'Select Select') {
            //         $fail('The ' . str_replace('_', ' ', $attribute) . ' field must be properly selected.');
            //     }
            // },
        //         'title'     => 'required|string|max:255',
        //         'subtitle' => 'required|string|max:255',
        //         'forTheCity' => 'required|boolean',
        //         'isCultureNature' => 'required|boolean',
        //     ]);

        //     if ($validator->passes()) {

        //         $currentDateTime = getUserCurrentTime();

        //         $content = $request->content;
        //         $dom = new \DomDocument();
        //         @$dom->loadHtml($content, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);
        //         $images = $dom->getElementsByTagName('img');

        //         foreach ($images as $img) {
        //             $src = $img->getAttribute('src');

        //             // Check if the image source is base64 (embedded image)
        //             if (Str::startsWith($src, 'data:image')) {
        //                 // Extract the base64 image data and save it as a file
        //                 preg_match('/data:image\/(?<mime>.*?)\;base64,(?<data>.*)/', $src, $matches);
        //                 $imageData = base64_decode($matches['data']);
        //                 $imageMime = $matches['mime'];
        //                 $imageName = time() . '_' . uniqid() . '.' . $imageMime;
        //                 $path = public_path('uploads/content_images/') . $imageName;
        //                 file_put_contents($path, $imageData);

        //                 // Replace the base64 image source with the URL of the saved image
        //                 $img->setAttribute('src', asset('uploads/content_images/' . $imageName));
        //             }
        //         }

        //         // Save the updated content with proper image URLs
        //         $content = $dom->saveHTML();

        //         // Update Chitti record with approved
        //         $chitti = Chitti::findOrFail($id);
        //         if ($request->action === 'approved'){
        //             $chitti->update([
        //                 'description'   => $request->content,
        //                 'Title'         => $request->title,
        //                 'SubTitle'      => $request->subtitle,
        //                 'checkerStatus' => 'sent_to_uploader',
        //                 'finalStatus'   => 'approved',
        //                 'updated_at'    => $currentDateTime,
        //                 'updated_by'    => Auth::user()->userId,
        //             ]);

        //             return redirect()->route('accounts.uploader-dashboard', ['id' => $chitti->chittiId])->with('success', 'Uploader updated successfully.');
        //         }else{
        //             // Update Chitti record
        //             $chitti->update([
        //                 'description'   => $request->content,
        //                 'Title'         => $request->title,
        //                 'SubTitle'      => $request->subtitle,
        //                 'checkerStatus'   => 'sent_to_uploader',
        //                 'finalStatus'   => 'sent_to_uploader',
        //                 'updated_at'    => $currentDateTime,
        //                 'updated_by'    => Auth::user()->userId,
        //             ]);

        //             // Update Facity record
        //             Facity::where('from_chittiId', $id)->update([
        //                 'value'         => $request->forTheCity,
        //                 'updated_at'    => $currentDateTime,
        //                 'updated_by'    => Auth::user()->userId,
        //             ]);

        //             // Update image if provided
        //             if ($request->hasFile('makerImage')) {
        //                 $makerImage = $request->file('makerImage');
        //                 $makerImageName = time() . '_' . $makerImage->getClientOriginalName();
        //                 $makerImage->move(public_path('uploads/maker_image/'), $makerImageName);
        //                 $url = public_path('uploads/maker_image/') . $makerImageName;
        //                 $serviceAccessUrl = "admin.prarang.in/" . $url;

        //                 // Update Chitti Image Mapping
        //                 Chittiimagemapping::where('chittiId', $id)->update([
        //                     'imageName'     => $makerImageName,
        //                     'imageUrl'      => $serviceAccessUrl,
        //                     'accessUrl'     => $url,
        //                     'updated_at'    => $currentDateTime,
        //                     'updated_by'    => Auth::user()->userId,
        //                 ]);
        //             }

        //             // Update Geography Mapping
        //             Chittigeographymapping::where('chittiId', $id)->update([
        //                 'areaId'        => $request->c2rselect,
        //                 'geographyId'   => $request->geography,
        //                 'updated_at'    => $currentDateTime,
        //                 'updated_by'    => Auth::user()->userId,
        //             ]);

        //             // Update Tag Mapping
        //             Chittitagmapping::where('chittiId', $id)->update([
        //                 'tagId'         => $request->isCultureNature,
        //                 'updated_at'    => $currentDateTime,
        //                 'updated_by'    => Auth::user()->userId,
        //             ]);

        //             return redirect()->route('accounts.uploader-dashboard', ['id' => $chitti->chittiId])->with('success', 'Uploader updated successfully.');
        //         }
        //     } else {
        //         return redirect()->back()
        //             ->withInput()
        //             ->withErrors($validator);
        //     }
    // }

    public function accUploaderUpdate(Request $request, $id, ImageUploadService $imageUploadService)
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
            // 'isCultureNature' => 'required|boolean',
            'tagId' => 'required',
            'writercolor' => 'required',
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
            $chitti = Chitti::findOrFail($id);
            if (isset($data['reader']) && is_string($data['reader'])) {
                $reader = json_decode($data['reader'], true);
                $data['reader'] = $reader['id'] ?? null; // Use the `id` field from the decoded object
            }

            // Update Chitti record with approved
            if ($request->action === 'approvd'){
                $chitti->update([
                    'description'   => $request->content,
                    'Title'         => $request->title,
                    'SubTitle'      => $request->subtitle,
                    'checkerStatus'   => 'sent_to_uploader',
                    'finalStatus'   => 'approved',
                    'updated_at'    => $currentDateTime,
                    'updated_by'    => Auth::user()->userId,
                    'dateOfApprove'=> Carbon::parse($currentDateTime)->format('d-m-Y g:i A'),
                    'uploaderId' => Auth::user()->userId,
                ]);

                return redirect()->route('accounts.uploader-dashboard')->with('success', 'Uploader updated successfully.');
            }else{
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
                    'description'   => $request->content,
                    'Title'         => $request->title,
                    'SubTitle'      => $request->subtitle,
                    'updated_at'    => $currentDateTime,
                    'updated_by'    => Auth::user()->userId,
                    'cityId' => $area_id,
                    'areaId' => $area_id,
                    'geographyId' => $request->geography,
                    'writercolor'   => $request->writercolor,
                    'color_value'   => $readerValue,
                ]);

                // Update Facity record
                Facity::where('from_chittiId', $id)->update([
                    'value'         => $request->forTheCity,
                    'updated_at'    => $currentDateTime,
                    'updated_by'    => Auth::user()->userId,
                ]);

                // Update image if provided
                if (isset($request->Videourl)) {

                    $data = $this->videoPost($request->Videourl);
                    Chittiimagemapping::where('chittiId', $id)->update([
                        'imageName' => $data['video-image'],
                        'imageUrl' => $data['video-image'],
                        'VideoURL' => $data['video-url'],
                        'VideoId' => $data['video-id'],
                        'VideoExist' => 1,
                        'updated_at' => $currentDateTime,
                        'updated_by' => Auth::user()->userId,
                    ]);
                } else {
                    if ($request->hasFile('makerImage')) {
                        $uploadImage = $imageUploadService->uploadImage($request->file('makerImage'), $chitti->chittiId);
                        if (isset($uploadImage['error']) && $uploadImage['error'] === true) {
                            DB::rollBack();

                            return redirect()->back()->with('error', 'Error while image uploading, please try again.');
                        }
                        Chittiimagemapping::where('chittiId', $id)->update([
                            'imageName' => $uploadImage['path'],
                            'imageUrl' => $uploadImage['full_url'],
                            'accessUrl' => $uploadImage['path'],
                            'updated_at' => $currentDateTime,
                            'updated_by' => Auth::user()->userId,
                        ]);
                    }
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

                return redirect()->route('accounts.uploader-dashboard', ['id' => $chitti->chittiId])->with('success', 'Uploader updated successfully.');
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
        $data['video-url'] = '<iframe width="100%" height="500" src="https://www.youtube.com/embed/' . $data['video-id'] . '"
        title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
        referrerpolicy="strict-origin-when-cross-origin" allowfullscreen></iframe>';
        $data['video-image'] = 'https://img.youtube.com/vi/' . $data['video-id'] . '/0.jpg';
        return $data;
    }

    //this method is use for return from uploader to checker with region
    public function accUploaderChittiReturnCheckerRegion(Request $request, $id)
    {
        // dd($id);
        $cityCode = $request->query('City');
        $checkerId = $request->query('checkerId');

        $chitti = Chitti::where('chittiId', $id)
            ->first();

        return view('accounts.uploader.acc-chitti-uploader-return-to-checker-with-region', compact('chitti'));
    }

    //this method is use for update eturn from checker to maker with region
    public function accUploaderChittiSendToChecker(Request $request, $id)
    {
        // dd('your data is here');
        $checkerId = $request->query('checkerId');
        $City = $request->query('City');
        $currentDate = date('d-M-y H:i:s');

        $validated = $request->validate([
            'accreturnChittiToCheckerWithRegion' => 'required|string',
        ]);
        // dd($request->returnChittiToCheckerWithRegion);
        $chitti = Chitti::findOrFail($id);
        $chitti->update([
            'uploaderStatus' => 'sent_to_checker',
            'checkerStatus'  => '',
            'uploaderId'     => Auth::user()->userId,
            'uploaderReason' => $request->accreturnChittiToCheckerWithRegion,
            'dateOfReturnToChecker' => $currentDate,
            'finalStatus'     => 'sent_to_checker',
        ]);

        return redirect()->route('accounts.uploader-dashboard')->with('success', 'Chitti Post have been return to checker from Uploader successfully');
    }
}
