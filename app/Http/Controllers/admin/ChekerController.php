<?php
namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
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


class ChekerController extends Controller
{
    public function indexMain()
    {
        $chittis = Chitti::with(['geographyMappings.region', 'geographyMappings.city', 'geographyMappings.country'])
        ->whereNotNull('Title')
        ->where('Title', '!=', '')
        ->where('checkerStatus', '!=', '')        
        ->whereIn('checkerStatus',['maker_to_checker'])
        ->where('makerStatus', 'sent_to_checker')
        ->orderByDesc('dateOfCreation')
        ->whereNotIn('finalStatus',['approved','deleted'])
        ->select('chittiId', 'Title', 'dateOfCreation', 'finalStatus', 'checkerStatus')
        ->get();
        $geographyOptions = Makerlebal::whereIn('id', [5, 6, 7])->get();
        return view('admin.checker.checker-listing', compact('chittis', 'geographyOptions'));
    }

    #this method is use for show the listing of maker
    public function index($id)
    {
        $chittis = Chitti::with(['geographyMappings.region', 'geographyMappings.city', 'geographyMappings.country'])
        ->where('chittiId', $id)
        ->whereNotNull('Title')
        ->where('Title', '!=', '')
        ->where('checkerStatus', '!=', '')
        ->where('makerStatus', 'sent_to_checker')
        ->select('chittiId', 'Title', 'dateOfCreation', 'finalStatus', 'checkerStatus')
        ->get();
        $geographyOptions = Makerlebal::whereIn('id', [5, 6, 7])->get();
        return view('admin.checker.checker-listing', compact('chittis', 'geographyOptions'));
    }

    #this method is use for maker make new post
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

    #this method is use for store maker data
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


    public function checkerEdit($id)
    {
        $chitti = Chitti::with('chittiimagemappings', 'geographyMappings', 'facity')
        ->whereNotIn('finalStatus',['approved','deleted'])
        ->whereNot('checkerStatus','sent_to_uploader')->findOrFail($id);

        $image = $chitti->chittiimagemappings()->first();
        // $chittiTagMapping = Chittitagmapping::where('chittiId', $id)->first();
        $chittiTagMapping = Chittitagmapping::with('tag.tagcategory')->where('chittiId', $id)->first();
        // dd($chittiTagMapping);
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

        return view('admin.checker.checker-edit', compact('chitti', 'image', 'geographyOptions', 'regions', 'cities', 'countries', 'geographyMapping', 'facityValue', 'chittiTagMapping', 'timelines', 'manSenses', 'manInventions', 'geographys', 'faunas', 'floras'));
    }

    public function checkerUpdate(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'content'   => 'required|string',
            'makerImage' => 'nullable|image|max:2048',
            'geography' => 'required',
            'c2rselect' => 'required',
            'title'     => 'required|string|max:255',
            'subtitle' => 'required|string|max:255',
            'forTheCity' => 'required|boolean',
            'isCultureNature' => 'required|boolean',
        ]);

        if ($validator->passes()) {

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

            // Update Chitti record
            $chitti = Chitti::findOrFail($id);

            if ($request->action === 'send_to_uploader')
            {
                $chitti->update([
                    'uploaderStatus'   => 'sent_to_uploader',
                    'updated_at'    => $currentDateTime,
                    'updated_by'    => Auth::guard('admin')->user()->userId,
                ]);

                // Redirect to the checker listing
                return redirect()->route('admin.checker-listing', $chitti->chittiId)
                    ->with('success', 'Sent to Uploader successfully.');
            }
            // elseif($request->action === 'send_to_maker')
            // {
            //     $currentDate = date("d-M-y H:i:s");
            //     $chitti->update([
            //         'dateOfReturnToMaker'       => $currentDate,
            //         'returnDateMaker'           => $currentDate ,
            //         'makerStatus'               => 'return_chitti_post_from_checker',
            //         'checkerId'                 => Auth::guard('admin')->user()->userId,
            //         'checkerReason'             => $request->returnChittiToMakerWithRegion,
            //         'return_chitti_post_from_checker'    => $request->returnChittiToMakerWithRegion,
            //         'postStatusMakerChecker'             => 'return_chitti_post_from_checker',
            //         'return_chitti_post_from_checker_id' => 1,
            //         'checkerStatus'         => 'Null',
            //         'uploaderStatus'        => 'Null',
            //         'finalStatus'           => 'Null',
            //     ]);
            //     return redirect()->route('admin.checker-listing', ['id' => $chitti->chittiId])->with('success', 'Checker updated successfully.');
            // }
            else
            {
                $currentDate = date("d-M-y H:i:s");
                $chitti->update([
                    'dateOfReturnToMaker'       => $currentDate,
                    'returnDateMaker'           => $currentDate ,
                    'makerStatus'               => 'return_chitti_post_from_checker',
                    'checkerId'                 => Auth::guard('admin')->user()->userId,
                    'postStatusMakerChecker'    => 'return_chitti_post_from_checker',
                    'return_chitti_post_from_checker_id' => 1,
                    'description'   => $request->content,
                    'Title'         => $request->title,
                    'SubTitle'      => $request->subtitle,
                    'checkerStatus'   => 'sent_to_uploader',
                    'uploaderStatus'        => 'Null',
                    'finalStatus'           => 'Null',
                    'updated_at'    => $currentDateTime,
                    'updated_by'    => Auth::guard('admin')->user()->userId,
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
                    'tagId'         => $request->isCultureNature,
                    'updated_at'    => $currentDateTime,
                    'updated_by'    => Auth::guard('admin')->user()->userId,
                ]);

                return redirect()->route('admin.checker-listing', ['id' => $chitti->chittiId])->with('success', 'Chitti Post have been return to maker from checker successfully.');
            }
        } else {
            return redirect()->back()
                ->withInput()
                ->withErrors($validator);
        }
    }

    #this method is use for return from checker to maker with region
    public function checkerChittiReturnMakerRegion(Request $request, $id)
    {
        $cityCode   = $request->query('City');
        $checkerId  = $request->query('checkerId');

        $chitti = Chitti::where('areaId', $cityCode)
            ->where('chittiId', $id)
            ->first();
        return view('admin.checker.chitti-checker-return-to-maker-with-region', compact('chitti'));
    }

    #this method is use for update eturn from checker to maker with region
    public function checkerChittiSendToMaker(Request $request, $id)
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
            'checkerStatus'         => 'Null',
            'uploaderStatus'        => 'Null',
            'finalStatus'           => 'Null',
        ]);
        return redirect('admin/checker/checker-listing')->with('success', 'Chitti Post have been return to maker from checker successfully');
    }
}
