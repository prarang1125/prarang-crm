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
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    

    public function indexMain(Request $request)
    {
        
        $search = $request->input('search');

        
        $chittis = Chitti::with(['geographyMappings.region', 'geographyMappings.city', 'geographyMappings.country'])
            ->whereNotNull('Title')
            ->where('Title', '!=', '')
            ->where('makerStatus', 'sent_to_checker')
            ->whereIn('checkerStatus', ['maker_to_checker'])
            ->whereNotIn('finalStatus', ['approved', 'deleted'])
            
            ->when($search, function ($query) use ($search) {
                $query->where('Title', 'LIKE', "%{$search}%") 
                    ->orWhereRaw('LOWER(createDate) LIKE ?', ['%' . mb_strtolower($search, 'UTF-8') . '%']); 
            })
            ->orderByDesc('dateOfCreation')
            ->paginate(10);
        

        $geographyOptions = Makerlebal::whereIn('id', [5, 6, 7])->get();

        return view('admin.checker.checker-listing', compact('chittis', 'geographyOptions'));
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
            
            'tagId'     => 'required'
        ]);

        if ($validator->passes()) {

            $currentDateTime = getUserCurrentTime();

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

                    
                    $img->setAttribute('src', asset('uploads/content_images/' . $imageName));
                }
            }

            
            $content = $dom->saveHTML();
            

            

            $chitti = Chitti::findOrFail($id);
            if ($request->action === 'send_to_uploader') {

                $chitti->update([
                    'uploaderStatus'   => 'sent_to_uploader',
                    'checkerStatus' => 'sent_to_uploader',
                    'updated_at'    => $currentDateTime,
                    'updated_by'    => Auth::guard('admin')->user()->userId,
                ]);

                
                return redirect()->route('admin.checker-listing')
                    ->with('success', 'Sent to Uploader successfully.');
            }

            
            
            
            
            
            
            
            
            
            
            
            
            
            
            
            
            
            
            else {
                
                $currentDate = date("d-M-y H:i:s");
                $chitti->update([
                    'dateOfReturnToMaker'       => $currentDate,
                    'returnDateMaker'           => $currentDate,

                    'makerStatus'               => 'sent_to_checker',
                    'checkerId'                 => Auth::guard('admin')->user()->userId,
                    'description'   => $request->content,
                    'Title'         => $request->title,
                    'SubTitle'      => $request->subtitle,
                    'checkerStatus'   => 'maker_to_checker',
                    
                    
                    'updated_at'    => $currentDateTime,
                    'updated_by'    => Auth::guard('admin')->user()->userId,
                ]);

                
                Facity::where('from_chittiId', $id)->update([
                    'value'         => $request->forTheCity,
                    'updated_at'    => $currentDateTime,
                    'updated_by'    => Auth::guard('admin')->user()->userId,
                ]);

                
                if ($request->hasFile('makerImage')) {
                    $makerImage = $request->file('makerImage');
                    $makerImageName = time() . '_' . $makerImage->getClientOriginalName();
                    $makerImage->move(public_path('uploads/maker_image/'), $makerImageName);
                    $url = public_path('uploads/maker_image/') . $makerImageName;
                    $serviceAccessUrl = "admin.prarang.in/" . $url;

                    
                    Chittiimagemapping::where('chittiId', $id)->update([
                        'imageName'     => $makerImageName,
                        'imageUrl'      => $serviceAccessUrl,
                        'accessUrl'     => $url,
                        'updated_at'    => $currentDateTime,
                        'updated_by'    => Auth::guard('admin')->user()->userId,
                    ]);
                }

                
                Chittigeographymapping::where('chittiId', $id)->update([
                    'areaId'        => $request->c2rselect,
                    'geographyId'   => $request->geography,
                    'updated_at'    => $currentDateTime,
                    'updated_by'    => Auth::guard('admin')->user()->userId,
                ]);

                
                Chittitagmapping::where('chittiId', $id)->update([
                    'tagId'         => $request->tagId,
                    'updated_at'    => $currentDateTime,
                    'updated_by'    => Auth::guard('admin')->user()->userId,
                ]);
                return redirect()->route('admin.checker-listing')->with('success', 'Chitti Post have been updated successfully.');
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
            'returnDateMaker'           => $currentDate,
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
        return redirect('admin/checker/checker-listing')->with('success', 'Chitti Post have been return to maker from checker successfully');
    }
}
