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

class AccUploaderController extends Controller
{
    public function accIndexMain(Request $request)
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
            ->whereNotIn('finalStatus',['deleted'])
            ->orderByDesc('dateOfCreation')
            ->select('chittiId', 'Title', 'SubTitle', 'dateOfCreation', 'finalStatus', 'checkerStatus', 'uploaderStatus')
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
        $chitti = Chitti::with('chittiimagemappings', 'geographyMappings', 'facity')->findOrFail($id);
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

        return view('accounts.uploader.acc-uploader-edit', compact('chitti','subTag', 'image', 'geographyOptions', 'regions', 'cities', 'countries', 'geographyMapping', 'facityValue', 'chittiTagMapping', 'timelines', 'manSenses', 'manInventions', 'geographys', 'faunas', 'floras'));
    }

    // public function accUploaderUpdate(Request $request, $id)
    // {
    //     $validator = Validator::make($request->all(), [
    //         'content'   => 'required|string|max:2000',
    //         'makerImage' => 'nullable|image|max:2048',
    //         'geography' => 'required',
    //         'c2rselect' => 'required',
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

    public function accUploaderUpdate(Request $request, $id)
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

            // Update Chitti record with approved
            $chitti = Chitti::findOrFail($id);
            if ($request->action === 'approvd'){
                $chitti->update([
                    'description'   => $request->content,
                    'Title'         => $request->title,
                    'SubTitle'      => $request->subtitle,
                    'checkerStatus'   => 'sent_to_uploader',
                    'finalStatus'   => 'approved',
                    'updated_at'    => $currentDateTime,
                    'updated_by'    => Auth::user()->userId,
                    'dateOfApprove'=>$currentDateTime
                ]);

                return redirect()->route('accounts.uploader-dashboard')->with('success', 'Uploader updated successfully.');
            }else{
                // Update Chitti record
                $chitti->update([
                    'description'   => $request->content,
                    'Title'         => $request->title,
                    'SubTitle'      => $request->subtitle,
                    'updated_at'    => $currentDateTime,
                    'updated_by'    => Auth::user()->userId,
                ]);

                // Update Facity record
                Facity::where('from_chittiId', $id)->update([
                    'value'         => $request->forTheCity,
                    'updated_at'    => $currentDateTime,
                    'updated_by'    => Auth::user()->userId,
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

                return redirect()->route('accounts.uploader-dashboard', ['id' => $chitti->chittiId])->with('success', 'Uploader updated successfully.');
            }
        } else {
            return redirect()->back()
                ->withInput()
                ->withErrors($validator);
        }
    }
}
