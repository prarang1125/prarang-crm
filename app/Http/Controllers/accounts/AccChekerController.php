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
use App\Models\Mcity;
use App\Models\Mcountry;
use App\Models\Facity;
use App\Models\Chittiimagemapping;
use App\Models\Chittigeographymapping;


class AccChekerController extends Controller
{
    public function accIndexMain()
    {
        $chittis = Chitti::with(['geographyMappings.region', 'geographyMappings.city', 'geographyMappings.country'])
        ->whereNotNull('Title')
        ->where('Title', '!=', '')
        ->where('checkerStatus', '!=', '')
        ->where('makerStatus', 'sent_to_checker')
        ->select('chittiId', 'Title', 'dateOfCreation', 'finalStatus', 'checkerStatus')
        ->get();
        $geographyOptions = Makerlebal::whereIn('id', [5, 6, 7])->get();
        return view('accounts.checker.acc-checker-listing', compact('chittis', 'geographyOptions'));
    }

    #this method is use for show the listing of accounts checker
    public function accIndex($id)
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
        return view('accounts.checker.acc-checker-listing', compact('chittis', 'geographyOptions'));
    }

    #this method is use for accounts checker edit
    public function accCheckerEdit($id)
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

        return view('accounts.checker.acc-checker-edit', compact('chitti', 'image', 'geographyOptions', 'regions', 'cities', 'countries', 'geographyMapping', 'facityValue', 'chittiTagMapping', 'timelines', 'manSenses', 'manInventions', 'geographys', 'faunas', 'floras'));
    }

    public function accCheckerUpdate(Request $request, $id)
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

            // Update Chitti record
            $chitti = Chitti::findOrFail($id);

            if ($request->action === 'send_to_uploader')
            {
                $chitti->update([
                    'uploaderStatus'   => 'checker_to_uploader',
                    'checkerStatus'    => 'sent_to_uploader',
                    'updated_at'    => $currentDateTime,
                    'updated_by'    => Auth::user()->userId,
                ]);

                // Redirect to the checker listing
                return redirect()->route('accounts.checker-dashboard', $chitti->chittiId)
                    ->with('success', 'Sent to Uploader successfully.');
            }
            elseif($request->action === 'update_checker')
            {
                $currentDate = date("d-M-y H:i:s");
                $chitti->update([
                    'makerStatus'               => 'sent_to_checker',
                    'checkerId'                 => Auth::user()->userId,
                    'checkerStatus'         => 'Null',
                    'uploaderStatus'        => 'Null',
                    'finalStatus'           => 'Null',
                ]);
                return redirect()->route('accounts.checker-dashboard', ['id' => $chitti->chittiId])->with('success', 'Checker updated successfully.');
            }
            else
            {
                $currentDate = date("d-M-y H:i:s");
                $chitti->update([
                    'dateOfReturnToMaker'       => $currentDate,
                    'returnDateMaker'           => $currentDate ,
                    'makerStatus'               => 'return_chitti_post_from_checker',
                    'checkerId'                 => Auth::user()->userId,
                    'postStatusMakerChecker'    => 'return_chitti_post_from_checker',
                    'return_chitti_post_from_checker_id' => 1,
                    'description'   => $request->content,
                    'Title'         => $request->title,
                    'SubTitle'      => $request->subtitle,
                    'checkerStatus'   => 'sent_to_uploader',
                    'uploaderStatus'        => 'Null',
                    'finalStatus'           => 'Null',
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
                    'tagId'         => $request->isCultureNature,
                    'updated_at'    => $currentDateTime,
                    'updated_by'    => Auth::user()->userId,
                ]);

                return redirect()->route('accounts.checker-dashboard', ['id' => $chitti->chittiId])->with('success', 'Chitti Post have been return to maker from checker successfully.');
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
            'checkerStatus'         => 'Null',
            'uploaderStatus'        => 'Null',
            'finalStatus'           => 'Null',
        ]);
        return back()->with('success', 'Chitti Post have been return to maker from checker successfully');
    }

}
