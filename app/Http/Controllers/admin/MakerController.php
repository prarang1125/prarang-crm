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


class MakerController extends Controller
{
    #this method is use for show the listing of maker
    public function index()
    {
        $chittis = Chitti::with(['geographyMappings.region', 'geographyMappings.city', 'geographyMappings.country'])
        ->whereNotNull('Title')
        ->where('Title', '!=', '')
        ->select('*')
        ->get();
        $geographyOptions = Makerlebal::whereIn('id', [5, 6, 7])->get();
        return view('admin.maker.maker-listing', compact('chittis', 'geographyOptions'));
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
        $regions = Mregion::all();
        $cities = Mcity::all();
        $countries = Mcountry::all();

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
            'subtitle' => 'required|string|max:255',
            'forTheCity' => 'required|boolean',
            'isCultureNature' => 'required|boolean',
        ]);

        if($validator->passes())
        {
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
            if($request->geography == 6){//6 is use for city
                $areaIdCode = 'c'.$area_id;
            }elseif($request->geography == 5){//5 is use for region
                $areaIdCode = 'r'.$area_id;
            }elseif($request->geography == 7){// 7 is use for country
                $areaIdCode = 'con'.$area_id;
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
    }

    public function makerEdit($id)
    {
        $chitti = Chitti::with('chittiimagemappings', 'geographyMappings', 'facity')->findOrFail($id);
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

        // Check if chittiTagMapping, tag, and tagcategory are set before accessing tagCategoryInUnicode
        // $tagCategoryInUnicode = $chittiTagMapping && $chittiTagMapping->tag && $chittiTagMapping->tag->tagcategory
        //     ? $chittiTagMapping->tag->tagcategory->tagCategoryInUnicode
        //     : null;

        // dd($tagCategoryInUnicode);
        // return view('admin.maker.maker-edit', compact('chitti', 'image', 'geographyOptions', 'regions', 'cities', 'countries', 'geographyMapping', 'facityValue', 'chittiTagMapping'));

        return view('admin.maker.maker-edit', compact('chitti', 'image', 'geographyOptions', 'regions', 'cities', 'countries', 'geographyMapping', 'facityValue', 'chittiTagMapping', 'timelines', 'manSenses', 'manInventions', 'geographys', 'faunas', 'floras'));
    }

    public function makerUpdate(Request $request, $id)
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

            if ($request->action === 'send_to_checker')
            {
                $chitti->update([
                    'checkerStatus'   => 'sent_to_uploader',
                    'updated_at'    => $currentDateTime,
                    'updated_by'    => Auth::guard('admin')->user()->userId,
                ]);

                // Redirect to the checker listing
                return redirect()->route('admin.maker-listing', $chitti->chittiId)
                    ->with('success', 'Sent to Checker successfully.');
            }
            else
            {
                $chitti->update([
                    'description'   => $request->content,
                    'Title'         => $request->title,
                    'SubTitle'      => $request->subtitle,
                    'makerStatus'   => 'sent_to_checker',
                    'finalStatus'   => '',
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

                return redirect()->route('admin.maker-listing')->with('success', 'Maker updated successfully.');
            }
        } else {
            return redirect()->back()
                ->withInput()
                ->withErrors($validator);
        }
    }
}
