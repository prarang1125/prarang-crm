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

class PostController extends Controller
{
    #this method is use for show the listing of maker
    public function index()
    {
        $chittis = Chitti::with(['geographyMappings.region', 'geographyMappings.city', 'geographyMappings.country'])
        ->whereNotNull('Title')
        ->where('Title', '!=', '')
        ->where('finalStatus', '!=', 'deleted')
        ->orderByDesc('dateOfCreation')
        ->select('chittiId', 'Title', 'dateOfCreation', 'finalStatus', 'makerStatus', 'checkerStatus')
        ->get();
        $geographyOptions = Makerlebal::whereIn('id', [5, 6, 7])->get();
        return view('admin.post.post-listing', compact('chittis', 'geographyOptions'));
    }

    public function postEdit($id)
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

        return view('admin.post.post-edit', compact('chitti', 'image', 'geographyOptions', 'regions', 'cities', 'countries', 'geographyMapping', 'facityValue', 'chittiTagMapping', 'timelines', 'manSenses', 'manInventions', 'geographys', 'faunas', 'floras'));
    }

    public function postUpdate(Request $request, $id)
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
                    'checkerStatus'     => 'sent_to_uploader',
                    'updated_at'        => $currentDateTime,
                    'updated_by'        => Auth::guard('admin')->user()->userId,
                ]);

                // Redirect to the checker listing
                return redirect()->route('admin.post-listing')->with('success', 'Post updated successfully.');
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

                return redirect()->route('admin.post-listing')->with('success', 'Post updated successfully.');
            }
        } else {
            return redirect()->back()
                ->withInput()
                ->withErrors($validator);
        }
    }

    #this method is use for delete s_city specific data
    public function postDelete($id)
    {
        try {
            $currentDateTime = getUserCurrentTime();
            $chitti = Chitti::findOrFail($id);

            // Perform the soft delete
            $chitti->finalStatus = 'deleted';
            $chitti->updated_at = $currentDateTime;
            $chitti->updated_by = Auth::guard('admin')->user()->userId;
            $chitti->save();

            return redirect()->route('admin.post-listing')->with('success', 'Post soft deleted successfully.');
        } catch (\Exception $e) {
            return redirect()->route('admin.post-listing')->with('error', 'There was an error deleting the post: ' . $e->getMessage());
        }
    }

    public function sendToChecker(Request $request, $chittiId)
    {
        // Find the Chitti record by ID
        $chitti = Chitti::findOrFail($chittiId);

        // Update the checker_status field
        $chitti->checkerStatus = 'sent_to_uploader'; // or any other status you want to set
        $chitti->save();

        // Optionally, you can flash a success message to the session
        return redirect()->back()->with('success', 'Chitti sent to checker successfully!');
    }

}
