<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
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
    #this method is use for show the listing of maker
    // public function index()
    // {
    //     $chittis = Chitti::with(['geographyMappings.region', 'geographyMappings.city', 'geographyMappings.country'])
    //     ->whereNotNull('Title')
    //     ->where('Title', '!=', '')
    //     ->where('makerStatus', '=', 'sent_to_checker')
    //     // ->where('is_active', 1)
    //     // ->where('checkerStatus', '=','maker_to_checker')
    //     ->select('*')
    //     ->orderByDesc('dateOfCreation')
    //     ->get();

    //     $notification = Chitti::where('return_chitti_post_from_checker_id', 1)->count();
    //     $geographyOptions = Makerlebal::whereIn('id', [5, 6, 7])->get();
    //     return view('admin.maker.maker-listing', compact('chittis', 'geographyOptions', 'notification'));
    // }

    public function index(Request $request)
    {
        $search = $request->input('search'); // Get the search query from the request

        // Fetch Chitti data with pagination and optional search filtering
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
            ->paginate(3); // Change '10' to the number of items per page

        $notification = Chitti::where('return_chitti_post_from_checker_id', 1)->count();
        $geographyOptions = Makerlebal::whereIn('id', [5, 6, 7])->get();

        return view('admin.maker.maker-listing', compact('chittis', 'geographyOptions', 'notification'));
    }


    #this method is use for maker make new post
    public function makerRegister()
    {
        // Fetch data from the Mtag table based on tagCategoryId
        $timelines = Mtag::where('tagCategoryId', 1)->get();
        // dd($timelines);
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
    public function makerStore(Request $request)
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
            'isCultureNature' => 'required|boolean',
            'tagId' => 'required',
        ]);
        if ($validator->passes()) {

            DB::beginTransaction();  // Use DB facade
            try {
            $currentDateTime = getUserCurrentTime();

            $content = $request->content;
            $dom = new \DomDocument();
            @$dom->loadHtml($content, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);
            $images = $dom->getElementsByTagName('img');

            foreach ($images as $img) {
                $src = $img->getAttribute('src');

                // Check if the image source is base64 (embedded image)
                if (Str::startsWith($src, 'data:image')) {
                    // Extract the base64 image data and save it as a file
                    preg_match('/data:image\/(?<mime>.*?)\;base64,(?<data>.*)/', $src, $matches);
                    $imageData = base64_decode($matches['data']);
                    $imageMime = $matches['mime'];
                    $imageName = time() . '_' . uniqid() . '.' . $imageMime;
                    $path = public_path('uploads/content_images/') . $imageName;
                    file_put_contents($path, $imageData);

                    // Replace the base64 image source with the URL of the saved image
                    $img->setAttribute('src', asset('uploads/content_images/' . $imageName));
                }
            }

            // Save the updated content with proper image URLs
            $content = $dom->saveHTML();
            // dd($content);

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
            $chitti->cityId = $area_id;
            $chitti->areaId = $areaIdCode;
            $chitti->geographyId = $request->geography;
            $chitti->created_at = $currentDateTime;
            $chitti->is_active = 1;
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

            if ($request->hasFile('makerImage')) {
                $makerImage = $request->file('makerImage');
                $makerImageName = time() . '_' . $makerImage->getClientOriginalName();
                $makerImage->move(public_path('uploads/maker_image/'), $makerImageName);
                $url = public_path('uploads/maker_image/') . "" . $makerImageName;
                // $serviceAccessUrl = "admin.prarang.in".$url;
                $serviceAccessUrl = $url;
            }

            // echo "url";
            // dd($url);
            // echo "service Access";
            // dd($serviceAccessUrl);
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
            $chittitagmapping->tagId = $request->tagId;
            $chittitagmapping->created_at = $currentDateTime;
            $chittitagmapping->created_by = Auth::guard('admin')->user()->userId;
            $chittitagmapping->save();

            DB::commit();  // Commit transaction
            return redirect()->route('admin.maker-listing')->with('success', 'Post created successfully.');
        } catch (\Exception $e) {
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
        if ($chitti->checkerStatus == 'maker_to_checker'  || $chitti->checkerStatus == 'sent_to_uploader' && $chitti->makerStatus != 'return_chitti_post_from_checker' ) {
            return redirect()->back()->with('error', 'not allow to edit');
        }
        $image = $chitti->chittiimagemappings()->first();
        // $chittiTagMapping = Chittitagmapping::where('chittiId', $id)->first();
        $chittiTagMapping = Chittitagmapping::with('tag.tagcategory')->where('chittiId', $id)->first();
        $subTag=$chittiTagMapping->tag->tagCategoryId;
        $timelines = Mtag::where('tagCategoryId', 1)->get();
        $manSenses = Mtag::where('tagCategoryId', 2)->get();
        $manInventions = Mtag::where( 'tagCategoryId', 3)->get();
        $geographys = Mtag::where('tagCategoryId', 4)->get();
        $faunas = Mtag::where('tagCategoryId', 5)->get();
        $floras = Mtag::where('tagCategoryId', 6)->get();

        $geographyOptions = Makerlebal::whereIn('id', [5, 6, 7])->get();
        $regions = Mregion::where('isActive', 1)->get();
        $cities = Mcity::where('isActive', 1)->get();
        $countries = Mcountry::where('isActive', 1)->get();        
        $geographyMapping = $chitti->geographyMappings->first();
        $facityValue = $chitti->facity ? $chitti->facity->value : null;

         $chittiTagMapping = Chittitagmapping::with('tag.tagcategory')->where('chittiId', $id)->first();

        // Check if chittiTagMapping, tag, and tagcategory are set before accessing tagCategoryInUnicode
        // $tagCategoryInUnicode = $chittiTagMapping && $chittiTagMapping->tag && $chittiTagMapping->tag->tagcategory
        //     ? $chittiTagMapping->tag->tagcategory->tagCategoryInUnicode
        //     : null;

        // dd($tagCategoryInUnicode);
        // return view('admin.maker.maker-edit', compact('chitti', 'image', 'geographyOptions', 'regions', 'cities', 'countries', 'geographyMapping', 'facityValue', 'chittiTagMapping'));

        return view('admin.maker.maker-edit', compact('chitti','subTag', 'image', 'geographyOptions', 'regions', 'cities', 'countries', 'geographyMapping', 'facityValue', 'chittiTagMapping', 'timelines', 'manSenses', 'manInventions', 'geographys', 'faunas', 'floras'));
    }

    public function makerUpdate(Request $request, $id)
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

            // Handle content images (base64 to file conversion and updating the content HTML)
            $content = $request->content;
            $dom = new \DomDocument();
            @$dom->loadHtml($content, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);
            $images = $dom->getElementsByTagName('img');

            foreach ($images as $img) {
                $src = $img->getAttribute('src');

                if (Str::startsWith($src, 'data:image')) {
                    preg_match('/data:image\/(?<mime>.*?)\;base64,(?<data>.*)/', $src, $matches);
                    $imageData = base64_decode($matches['data']);
                    $imageMime = $matches['mime'];
                    $imageName = time() . '_' . uniqid() . '.' . $imageMime;
                    $path = public_path('uploads/content_images/') . $imageName;
                    file_put_contents($path, $imageData);

                    // Replace base64 with file URL
                    $img->setAttribute('src', asset('uploads/content_images/' . $imageName));
                }
            }
            // Save updated content with image URLs
            $content = $dom->saveHTML();

            // Update Chitti record
            $chitti = Chitti::findOrFail($id);

            if ($request->action === 'send_to_checker') {
                // dd($request);
                $chitti->update([
                    'makerStatus'   => 'sent_to_checker',
                    'checkerStatus' => 'maker_to_checker',
                    'updated_at'    => $currentDateTime,
                    'updated_by'    => Auth::guard('admin')->user()->userId,
                    'return_chitti_post_from_checker_id' => 0,
                    'returnDateToChecker' => $currentDateTime,
                    'makerId'       => Auth::guard('admin')->user()->userId,
                    'finalStatus'   => 'Null',
                ]);

                // Redirect to the checker listing
                return redirect()->route('admin.maker-listing', $chitti->chittiId)
                    ->with('success', 'Sent to Checker successfully.');
            } else {
                $chitti->update([
                    'description'   => $request->content,
                    'Title'         => $request->title,
                    'SubTitle'      => $request->subtitle,
                    'makerStatus'   => 'sent_to_checker',
                    'makerId'       => Auth::guard('admin')->user()->userId,
                    'finalStatus'   => 'Null',
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
                    $makerImage = $request->file('makerImage');
                    $makerImageName = time() . '_' . $makerImage->getClientOriginalName();
                    $makerImage->move(public_path('uploads/maker_image/'), $makerImageName);
                    $url = public_path('uploads/maker_image/') . $makerImageName;
                    $serviceAccessUrl = "admin.prarang.in/" . $url;

                    // Update Chitti Image Mapping
                    Chittiimagemapping::where('chittiId', $id)->update([
                        'imageName'     => $makerImageName,
                        'imageUrl'      => $serviceAccessUrl,
                        'accessUrl'     => $url,
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

    #this method is use for show the listing of return post via checker
    // public function chittiListReturnFromCheckerL()
    // {
    //     // $chittis = Chitti::where('makerStatus', 'return_chitti_post_from_checker')->get();
    //     $chittis = Chitti::with(['geographyMappings.region', 'geographyMappings.city', 'geographyMappings.country'])
    //     ->whereNotNull('Title')
    //     ->where('Title', '!=', '')
    //     ->where('return_chitti_post_from_checker_id',  1)
    //     ->select('*')
    //     ->get();
    //     $notification = Chitti::where('return_chitti_post_from_checker_id', 1)->count();
    //     $geographyOptions = Makerlebal::whereIn('id', [5, 6, 7])->get();
    //     return view('admin.maker.chitti-rejected-from-checker-listing', compact('geographyOptions', 'notification', 'chittis'));
    // }

    public function chittiListReturnFromCheckerL(Request $request)
    {
        $query = Chitti::with(['geographyMappings.region', 'geographyMappings.city', 'geographyMappings.country'])
            ->whereNotNull('Title')
            ->where('Title', '!=', '')
            ->where('finalStatus', '!=', 'deleted')
            ->where('return_chitti_post_from_checker_id', 1);

        // Handle search
        if ($request->has('search') && $request->input('search') != '') {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('Title', 'LIKE', "%$search%")
                ->orWhere('description', 'LIKE', "%$search%");
            });
        }

        // Paginate results
        $chittis = $query->paginate(2); // Adjust the number of items per page as needed

        $notification = Chitti::where('return_chitti_post_from_checker_id', 1)->count();
        $geographyOptions = Makerlebal::whereIn('id', [5, 6, 7])->get();

        return view('admin.maker.chitti-rejected-from-checker-listing', compact('geographyOptions', 'notification', 'chittis'));
    }


    public function makerDelete($id)
    {
        try {
            $chittis = Chitti::findOrFail($id);
            $chittis->finalStatus ='deleted';
            $chittis->makerStatus="sent_to_checker";
            $chittis->return_chitti_post_from_checker_id=0;
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
        $chitti = Chitti::where('chittiId',$validatedData['chittiId'])->firstOrFail();
        $chitti->Title = $validatedData['Title'];
        $chitti->subTitle = $validatedData['subTitle'];
        $chitti->save();
        return redirect()->route('admin.maker-listing')->with('success', 'Post Title Updated Successfully.');
    }
}
