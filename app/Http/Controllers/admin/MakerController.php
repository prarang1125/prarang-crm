<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Services\ImageUploadService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use App\Models\Mtag;
use App\Models\Mregion;
use App\Models\Mcity;
use App\Models\Mcountry;
use App\Models\Makerlebal;
use App\Models\Chitti;
use App\Models\Chittigeographymapping;
use App\Models\Chittiimagemapping;
use App\Models\Facity;
use App\Models\Chittitagmapping;


class MakerController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $chittis = Chitti::with(['geographyMappings.region', 'geographyMappings.city', 'geographyMappings.country'])
            ->when($search, function ($query, $search) {
                return $query->where(function ($q) use ($search) {
                    $q->where('Title', 'LIKE', '%' . $search . '%')
                        ->orWhere('SubTitle', 'LIKE', '%' . $search . '%')
                        ->orWhere('createDate', 'LIKE', '%' . $search . '%');
                });
            })
            ->whereNotNull('Title')
            ->where('Title', '!=', '')
            ->where('makerStatus', '=', 'sent_to_checker')
            ->where('finalStatus', '!=', 'deleted')
            ->select('*')
            ->orderByDesc('dateOfCreation')
            ->paginate(10); // Change '10' to the number of items per page

        $notification = Chitti::where('return_chitti_post_from_checker_id', 1)->count();
        $geographyOptions = Makerlebal::whereIn('id', [5, 6, 7])->get();
        return view('admin.maker.maker-listing', compact('chittis', 'geographyOptions', 'notification'));
    }


    #this method is use for maker make new post
    public function makerRegister()
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
        $regions = Mregion::where('isActive', 1)->get();
        $cities = Mcity::where('isActive', 1)->get();
        $countries = Mcountry::where('isActive', 1)->get();
        return view('admin.maker.maker-register', compact('timelines', 'manSenses', 'manInventions', 'geographys', 'faunas', 'floras', 'geographyOptions', 'regions', 'cities', 'countries'));
    }

    #this method is use for store maker data
    public function makerStore(Request $request, ImageUploadService $imageUploadService)
    {

        $validator = Validator::make($request->all(), [
            'content'   => 'required|string',
            'makerImage' => 'required|image|max:2048',
            'geography' => 'required',
            'c2rselect' => 'required',
            'title'     => 'required|string|max:255',
            // 'subtitle' => 'required|string|max:255',
            'subtitle' => ['required', 'string', 'max:255', 'regex:/^[a-zA-Z\s]+$/'],
            'forTheCity' => 'required|boolean',
            // 'isCultureNature' => 'required|boolean',
            'tagId' => 'required',

        ]);

        if ($validator->passes()) {
            DB::beginTransaction();  // Use DB facade
            try {

                $currentDateTime = getUserCurrentTime();
                $chitti = new Chitti();
                $area_id = $request->c2rselect;
                $areaIdCode = '';
                if ($request->geography == 6) { //6 is use for city
                    $areaIdCode = 'c' . $area_id;
                } elseif ($request->geography == 5) { //5 is use for region
                    $areaIdCode = 'r' . $area_id;
                } elseif ($request->geography == 7) { // 7 is use for country
                    $areaIdCode = 'con' . $area_id;
                }

                $chitti->languageId = 1;
                $chitti->description = $request->content;
                $chitti->dateOfCreation =  $currentDateTime;
                $chitti->createDate =  $currentDateTime;
                $chitti->Title = $request->title;
                $chitti->SubTitle = $request->subtitle;
                $chitti->makerId = Auth::guard('admin')->user()->userId;
                $chitti->makerStatus = 'sent_to_checker';
                $chitti->finalStatus = '';
                $chitti->checkerStatus = '';
                $chitti->cityId = $area_id;
                $chitti->areaId = $areaIdCode;
                $chitti->geographyId = $request->geography;
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

                $uploadImage = $imageUploadService->uploadImage($request->file('makerImage'), $lastId);
                if (isset($uploadImage['error']) && $uploadImage['error'] === true) {
                    DB::rollBack();
                    return redirect()->back()->with('error', 'Error while image uploading, please try again.');
                }

                $chittiimagemapping = new Chittiimagemapping();
                $chittiimagemapping->imageName = $uploadImage['path'];
                $chittiimagemapping->imageUrl = $uploadImage['full_url'];
                $chittiimagemapping->accessUrl = $uploadImage['path'];
                $chittiimagemapping->isActive = '1';
                $chittiimagemapping->chittiId = $lastId;
                $chittiimagemapping->isDefult = 'true';
                $chittiimagemapping->imageTag = $uploadImage['path'];
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
                $chittitagmapping->tagId = $request->tagId;
                $chittitagmapping->created_at = $currentDateTime;
                $chittitagmapping->created_by = Auth::guard('admin')->user()->userId;
                $chittitagmapping->save();
                DB::commit();  // Commit transaction
                return redirect()->route('admin.maker-listing')->with('success', 'Post created successfully.');
            } catch (\Exception $e) {
                // dd($e->getMessage());
                DB::rollBack();  // Rollback transaction
                return redirect()->route('admin.maker-register')->with('error', 'An error occurred, please try again.');
            }
        } else {
            return redirect()->route('admin.maker-register')
                ->withErrors($validator)
                ->withInput();
        }
    }

    public function makerEdit($id)
    {
        $chitti = Chitti::with('chittiimagemappings', 'geographyMappings', 'facity')->findOrFail($id);
        if ($chitti->checkerStatus == 'maker_to_checker' || $chitti->checkerStatus == 'sent_to_uploader') {
            return redirect()->back()->with('error', 'not allow to edit');
        }
        $image = $chitti->chittiimagemappings()->first();
        // $chittiTagMapping = Chittitagmapping::where('chittiId', $id)->first();
        $chittiTagMapping = Chittitagmapping::with('tag.tagcategory')->where('chittiId', $id)->first();
        $subTag = $chittiTagMapping->tag->tagCategoryId;
        $timelines = Mtag::where('tagCategoryId', 1)->get();
        $manSenses = Mtag::where('tagCategoryId', 2)->get();
        $manInventions = Mtag::where('tagCategoryId', 3)->get();
        $geographys = Mtag::where('tagCategoryId', 4)->get();
        $faunas = Mtag::where('tagCategoryId', 5)->get();
        $floras = Mtag::where('tagCategoryId', 6)->get();

        $geographyOptions = Makerlebal::whereIn('id', [5, 6, 7])->get();
        $regions = Mregion::where('isActive', 1)->get();
        $cities = Mcity::where('isActive', 1)->get();
        $countries = Mcountry::where('isActive', 1)->get();
        $geographyMapping = $chitti->geographyMappings->first();
        $facityValue = $chitti->facity ? $chitti->facity->value : null;
        // dd($geographyMapping);
        // dd($facityValue);

        $chittiTagMapping = Chittitagmapping::with('tag.tagcategory')->where('chittiId', $id)->first();

        return view('admin.maker.maker-edit', compact('chitti', 'subTag', 'image', 'geographyOptions', 'regions', 'cities', 'countries', 'geographyMapping', 'facityValue', 'chittiTagMapping', 'timelines', 'manSenses', 'manInventions', 'geographys', 'faunas', 'floras'));
    }

    public function makerUpdate(Request $request, $id, ImageUploadService $imageUploadService)
    {
        $validator = Validator::make($request->all(), [
            'content'   => 'required|string',
            'makerImage' => 'nullable|image|max:2048',
            'geography' => 'required',
            'c2rselect' => 'required',
            'title'     => 'required|string|max:255',
            'subtitle' => ['required', 'string', 'max:255', 'regex:/^[a-zA-Z\s]+$/'],
            // 'subtitle' => 'required|string|max:255',
            'forTheCity' => 'required|boolean',
            // 'isCultureNature' => 'required|boolean',
            'tagId' => 'required',
        ]);

        if ($validator->passes()) {
            DB::beginTransaction();
            try {
                $currentDateTime = getUserCurrentTime();
                // Update Chitti record
                $chitti = Chitti::findOrFail($id);
                // dd($request->action);
                if ($request->action === 'send_to_checker') {
                    $chitti->update([
                        'makerStatus'   => 'sent_to_checker',
                        'checkerStatus' => 'maker_to_checker',
                        'updated_at'    => $currentDateTime,
                        'updated_by'    => Auth::guard('admin')->user()->userId,
                        'return_chitti_post_from_checker_id' => 0,
                        'returnDateToChecker' => $currentDateTime,
                        'makerId'       => Auth::guard('admin')->user()->userId,
                        // 'finalStatus'   => 'Null',
                    ]);
                    DB::commit();
                    // Redirect to the checker listing
                    return redirect()->route('admin.maker-listing', $chitti->chittiId)
                        ->with('success', 'Sent to Checker successfully.');
                } else {
                    $chitti->update([
                        'description'   => $request->content,
                        'Title'         => $request->title,
                        'SubTitle'      => $request->subtitle,
                        // 'checkerStatus' => 'maker_to_checker',
                        'makerStatus'   => 'sent_to_checker',
                        'makerId'       => Auth::guard('admin')->user()->userId,
                        // 'finalStatus'   => 'Null',
                        // 'checkerStatus' => 'Null',
                        'updated_at'    => $currentDateTime,
                        'updated_by'    => Auth::guard('admin')->user()->userId,
                        'return_chitti_post_from_checker_id' => 0,
                        'returnDateToChecker' => $currentDateTime,
                    ]);

                    // Update Facity record
                    Facity::where('from_chittiId', $id)->update([
                        'value'         => $request->forTheCity,
                        'updated_at'    => $currentDateTime,
                        'updated_by'    => Auth::guard('admin')->user()->userId,
                    ]);

                    // Update image if provided
                    if ($request->hasFile('makerImage')) {
                        $uploadImage = $imageUploadService->uploadImage($request->file('makerImage'), $chitti->chittiId);
                        if (isset($uploadImage['error']) && $uploadImage['error'] === true) {
                            DB::rollBack();
                            return redirect()->back()->with('error', 'Error while image uploading, please try again.');
                        }
                        Chittiimagemapping::where('chittiId', $id)->update([
                            'imageName'     => $uploadImage['path'],
                            'imageUrl'      => $uploadImage['full_url'],
                            'accessUrl'     => $uploadImage['path'],
                            'updated_at'    => $currentDateTime,
                            'updated_by'    => Auth::guard('admin')->user()->userId,
                        ]);

                    }
                    // Update Geography Mapping
                    Chittigeographymapping::where('chittiId', $id)->update([
                        'areaId'        => $request->c2rselect,
                        'geographyId'   => $request->geography,
                        'updated_at'    => $currentDateTime,
                        'updated_by'    => Auth::guard('admin')->user()->userId,
                    ]);

                    // Update Tag Mapping
                    Chittitagmapping::where('chittiId', $id)->update([
                        'tagId'         => $request->tagId,
                        'updated_at'    => $currentDateTime,
                        'updated_by'    => Auth::guard('admin')->user()->userId,
                    ]);
                    DB::commit();
                    return redirect()->route('admin.maker-listing')->with('success', 'Maker updated successfully.');
                }
            } catch (\Exception $e) {

                DB::rollBack();
                Log::error('Maker Update Error: ' . $e->getMessage(), ['exception' => $e]);
                return redirect()->back()->with('error', 'An error occurred while updating the maker.')->withInput();
            }
        } else {
            return redirect()->back()
                ->withInput()
                ->withErrors($validator);
        }
    }
    public function chittiListReturnFromCheckerL(Request $request)
    {
        $query = Chitti::with(['geographyMappings.region', 'geographyMappings.city', 'geographyMappings.country'])
            ->whereNotNull('Title')
            ->where('Title', '!=', '')
            ->where('finalStatus', '!=', 'deleted')
            ->where('return_chitti_post_from_checker_id', 1);


        if ($request->has('search') && $request->input('search') != '') {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('Title', 'LIKE', "%$search%")
                    ->orWhere('description', 'LIKE', "%$search%");
            });
        }


        // Paginate results
        $chittis = $query->paginate(30); // Adjust the number of items per page as needed

        $notification = Chitti::where('return_chitti_post_from_checker_id', 1)->count();
        $geographyOptions = Makerlebal::whereIn('id', [5, 6, 7])->get();

        return view('admin.maker.chitti-rejected-from-checker-listing', compact('geographyOptions', 'notification', 'chittis'));
    }


    public function makerDelete($id)
    {
        try {
            $chittis = Chitti::findOrFail($id);
            $chittis->finalStatus = 'deleted';
            $chittis->makerStatus = "sent_to_checker";
            $chittis->return_chitti_post_from_checker_id = 0;
            $chittis->save();

            return redirect()->route('admin.maker-listing')->with('success', 'Listing soft deleted successfully.');
        } catch (\Exception $e) {
            return redirect()->route('admin.maker-listing')->withErrors(['error' => 'An error occurred while trying to soft delete the listing.']);
        }
    }

    public function updateTitle(Request $request) #Code: Vivek Yadav
    {

        $validatedData = $request->validate([
            'Title' => 'required|string|max:255',
            'subTitle' => [
                'required',
                'regex:/^[a-zA-Z0-9 -]+$/',
            ],
            'chittiId' => 'required|integer|exists:chitti,chittiId',
        ], [
            'Title.required' => 'The title field is required.',
            'subTitle.required' => 'The subtitle field is required.',
            'subTitle.regex' => 'The subtitle must contain only letters and numbers.',
            'chittiId.required' => 'Chitti ID is required.',
            'chittiId.exists' => 'The provided Chitti ID does not exist.',
        ]);
        $chitti = Chitti::where('chittiId', $validatedData['chittiId'])->firstOrFail();
        $chitti->Title = $validatedData['Title'];
        $chitti->subTitle = $validatedData['subTitle'];
        $chitti->save();
        return redirect()->back()->with('success', 'Post Title Updated Successfully.');
    }
}
