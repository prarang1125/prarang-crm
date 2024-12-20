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
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class UploaderController extends Controller
{
    // public function indexMain()
    // {
    //     $chittis = Chitti::with(['geographyMappings.region', 'geographyMappings.city', 'geographyMappings.country'])
    //     ->whereNotNull('Title')
    //     ->where('Title', '!=', '')
    //     ->where('uploaderStatus', '!=', '')
    //     ->where('uploaderStatus', '=', 'sent_to_uploader')
    //     ->orderByDesc('dateOfCreation')
    //     // ->where('finalStatus', '=', 'sent_to_uploader')
    //     ->select('chittiId', 'Title', 'dateOfCreation', 'finalStatus', 'checkerStatus', 'uploaderStatus')
    //     ->get();
    //     $geographyOptions = Makerlebal::whereIn('id', [5, 6, 7])->get();
    //     return view('admin.uploader.uploader-listing', compact('chittis', 'geographyOptions'));
    // }

    public function indexMain(Request $request)
    {
        $search = $request->input('search');

        $chittis = Chitti::with(['geographyMappings.region', 'geographyMappings.city', 'geographyMappings.country'])
            ->whereNotNull('Title')
            ->where('Title', '!=', '')
            ->where('uploaderStatus', '=', 'sent_to_uploader')
            ->when($search, function ($query, $search) {
                $query->where(function ($query) use ($search) {
                    $query->where('Title', 'like', "%{$search}%")
                        ->orWhere('SubTitle', 'like', "%{$search}%");
                });
            })
            ->whereNotIn('finalStatus', ['deleted'])
            ->orderByDesc('dateOfCreation')
            ->select('chittiId', 'Title', 'SubTitle', 'dateOfCreation', 'finalStatus', 'checkerStatus', 'uploaderStatus')
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
        $search = $request->input('search');

        // Fetch Chitti records with relationships and pagination
        $chittis = Chitti::with(['geographyMappings.region', 'geographyMappings.city', 'geographyMappings.country'])
            ->when($search, function ($query) use ($search) {
                $query->where(function ($subQuery) use ($search) {
                    $subQuery->where('Title', 'LIKE', "%$search%")
                        ->orWhere('SubTitle', 'LIKE', "%$search%")
                        ->orWhere('metaTag', 'LIKE', "%$search%");
                });
            })
            ->where('uploaderStatus', '=', 'sent_to_uploader')
            ->paginate(30); // Adjust the number of items per page

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
            'c2rselect' => 'required',
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
        $chitti = Chitti::with('chittiimagemappings', 'geographyMappings', 'facity')->findOrFail($id);
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

        return view('admin.uploader.uploader-edit', compact('chitti', 'subTag', 'image', 'geographyOptions', 'regions', 'cities', 'countries', 'geographyMapping', 'facityValue', 'chittiTagMapping', 'timelines', 'manSenses', 'manInventions', 'geographys', 'faunas', 'floras'));
    }

    public function uploaderUpdate(Request $request, $id, ImageUploadService $imageUploadService)
    {
        $validator = Validator::make($request->all(), [
            'content' => 'required|string',
            'makerImage' => 'nullable|image|max:2048',
            'geography' => 'required',
            'c2rselect' => 'required',
            'title' => 'required|string|max:255',
            'subtitle' => 'required|string|max:255',
            'forTheCity' => 'required|boolean',
            'isCultureNature' => 'required|boolean',
        ]);

        if ($validator->passes()) {

            $currentDateTime = getUserCurrentTime();

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
                    'dateOfApprove' => $currentDateTime,
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
                    'updated_at' => $currentDateTime,
                    'updated_by' => Auth::guard('admin')->user()->userId,
                    // 'date',
                    'cityId' => $area_id,
                    'areaId' => $areaIdCode,
                    'geographyId' => $request->geography,
                ]);

                // Update Facity record
                Facity::where('from_chittiId', $id)->update([
                    'value' => $request->forTheCity,
                    'updated_at' => $currentDateTime,
                    'updated_by' => Auth::guard('admin')->user()->userId,
                ]);
                if ($request->hasFile('makerImage')) {
                    $uploadImage = $imageUploadService->uploadImage($request->file('makerImage'), $chitti->chittiId);
                    if (isset($uploadImage['error']) && $uploadImage['error'] === true) {
                        DB::rollBack();

                        return redirect()->back()->with('error', 'Error while image uploading, please try again.');
                    }

                    // Update Chitti Image Mapping
                    Chittiimagemapping::where('chittiId', $id)->update([
                        'imageName' => $uploadImage['path'],
                        'imageUrl' => $uploadImage['full_url'],
                        'accessUrl' => $uploadImage['path'],
                        'updated_at' => $currentDateTime,
                        'updated_by' => Auth::guard('admin')->user()->userId,
                    ]);
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

                return redirect()->route('admin.uploader-listing', ['id' => $chitti->chittiId])->with('success', 'Uploader updated successfully.');
            }
        } else {
            return redirect()->back()
                ->withInput()
                ->withErrors($validator);
        }
    }
}
