<?php
namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Mregion;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class RegionController extends Controller
{
    #this method is use for region listing
    public function index()
    {
        $regions = Mregion::where('isActive', 1)->get();
        return view("admin.region.region-listing", compact('regions'));
    }

    #this method is use for show regiter new region page
    public function regionRegister()
    {
        return view('admin.region.regio-register');
    }

    #this method is use for store new region data
    public function regionStore(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'regionnameInEnglish' => 'required|string|max:255',
            'regionnameInUnicode' => 'required|string|max:255',
            'regionImage' => 'required|image|max:2048',
            'regionMap' => 'required|image|max:2048',
            'isCultureNature' => 'required|boolean',
            'content' => 'required|string',
        ]);
        if($validator->passes()){
            $currentDateTime = getUserCurrentTime();
            if($request->hasFile('regionImage')){
                $regionImage = $request->file('regionImage');
                $regionImageName = time() . '_' . $regionImage->getClientOriginalName();
                $regionImage->move(public_path('uploads/region_images'), $regionImageName);
            }

            if($request->hasFile('regionMap')){
                $regionMap = $request->file('regionMap');
                $regionMapName = time() . '_' . $regionMap->getClientOriginalName();
                $regionMap->move(public_path('uploads/region_maps'), $regionMapName);
            }

            $lastId = Mregion::max('regionId');
            $newId = $lastId ? $lastId + 1 : 1;

            $mregion = new Mregion();
            $mregion->regionCode = 'r' . $newId;
            $mregion->regionnameInUnicode = $request->regionnameInUnicode;
            $mregion->regionnameInEnglish = $request->regionnameInEnglish;
            $mregion->isActive = 1;
            $mregion->image = $regionImageName;
            $mregion->map = $regionMapName;
            $mregion->Image_Name   = $regionImageName;
            $mregion->Map_Name     = $regionMapName;
            $mregion->text = $request->content;
            $mregion->Culture_Nature = $request->isCultureNature;
            $mregion->created_at = $currentDateTime;
            $mregion->created_by = Auth::guard('admin')->user()->userId;
            $mregion->save();
            return redirect()->route('admin.region-listing')->with('success', 'Region created successfully.');
        }else{
            return redirect()->route('admin.region-register')
                ->withErrors($validator)
                ->withInput();
        }
    }

    #this method is use for delete specific region data
    public function regionDelete($id)
    {
        try {
            $currentDateTime = getUserCurrentTime();
            $region = Mregion::findOrFail($id);
            $region->isActive = 0;
            $region->updated_at = $currentDateTime;
            $region->updated_by = Auth::guard('admin')->user()->userId;
            $region->save();

            return redirect()->route('admin.region-listing')->with('success', 'User soft deleted successfully.');
        } catch (\Exception $e) {
            return redirect()->route('admin.region-listing')->with('error', 'There was an error deleting the user: ' . $e->getMessage());
        }
    }

    #this method is use for edit region data
    public function regionEdit($id)
    {
        $region = Mregion::findOrFail($id);
        return view('admin.region.region-edit' , compact('region'));
    }

    #this method is use for region data update
    public function regionUpdate(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'regionnameInEnglish' => 'required|string|max:255',
            'regionnameInUnicode' => 'required|string|max:255',
            'regionImage' => 'nullable|image|max:2048',
            'regionMap' => 'nullable|image|max:2048',
            'isCultureNature' => 'required|boolean',
            'content' => 'required|string',
        ]);

        if ($validator->passes()) {
            $mregion = Mregion::find($id);

            if ($mregion) {
                $currentDateTime = getUserCurrentTime();

                // Handle cityImage upload if present
                if ($request->hasFile('regionImage')) {
                    $regionImage = $request->file('regionImage');
                    $regionImageName = time() . '_' . $regionImage->getClientOriginalName();
                    $regionImage->move(public_path('uploads/region_images'), $regionImageName);
                    // Debugging: Log the filename
                    Log::info("Generated image name: " . $regionImageName);
                    // Update the image field in the model
                    $mregion->image = $regionImageName;
                    $mregion->Image_Name = $regionImageName;
                }

                // Handle cityMap upload if present
                if ($request->hasFile('regionMap')) {
                    $regionMap = $request->file('regionMap');
                    $regionMapName = time() . '_' . $regionMap->getClientOriginalName();
                    $regionMap->move(public_path('uploads/region_maps'), $regionMapName);
                    // Debugging: Log the filename
                    Log::info("Generated map name: " . $regionMapName);
                    // Update the map field in the model
                    $mregion->map = $regionMapName;
                    $mregion->Map_Name = $regionMapName;
                }

                if (empty($request->file('cityImage')) || empty($request->file('cityMap'))) {
                    $regionImageName = $mregion->image;
                    $regionMapName = $mregion->map;
                }

                // Update other fields
                $mregion->update([
                    'regionnameInUnicode' => $request->regionnameInUnicode,
                    'regionnameInEnglish' => $request->regionnameInEnglish,
                    'isActive' => $request->isCultureNature,
                    'image' => $regionImageName,
                    'map' => $regionMapName,
                    'Image_Name' => $regionImageName,
                    'Map_Name' => $regionMapName,
                    'text' => $request->content,
                    'Culture_Nature' => $request->isCultureNature,
                    'updated_at' => $currentDateTime,
                    'updated_by' => Auth::guard('admin')->user()->userId,
                ]);

                return redirect()->route('admin.region-listing')->with('success', 'Region updated successfully.');
            } else {
                return redirect()->back()->with('error', 'Region not found.');
            }
        } else {
            return redirect()->back()
                ->withInput()
                ->withErrors($validator);
        }
    }
}
