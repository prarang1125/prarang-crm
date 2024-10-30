<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Mcity;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class LiveCityController extends Controller
{
    #this method is use for show the listing of city
    public function index()
    {
        $mcitys = Mcity::where('isActive', 1)->get();
        return view('admin.livecity.live-city-listing', compact('mcitys'));
    }

    #this method is use for regidter new city
    public function liveCityRegister()
    {
        return view('admin.livecity.live-city-register');
    }

    #this method is use for store city
    public function liveCityStore(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'cityNameInEnglish' => 'required|string|max:255',
            'cityNameInUnicode' => 'required|string|max:255',
            'cityImage' => 'required|image|max:2048',
            'cityMap' => 'required|image|max:2048',
            'isCultureNature' => 'required|boolean',
            'content' => 'required|string',
        ]);
        if($validator->passes()){
            $currentDateTime = getUserCurrentTime();
            if($request->hasFile('cityImage')){
                $cityImage = $request->file('cityImage');
                $cityImageName = time() . '_' . $cityImage->getClientOriginalName();
                $cityImage->move(public_path('uploads/city_images'), $cityImageName);
            }

            if($request->hasFile('cityMap')){
                $cityMap = $request->file('cityMap');
                $cityMapName = time() . '_' . $cityMap->getClientOriginalName();
                $cityMap->move(public_path('uploads/city_maps'), $cityMapName);
            }

            $lastId = Mcity::max('cityId');
            $newId = $lastId ? $lastId + 1 : 1;

            $mcity = new Mcity();
            $mcity->cityCode = 'c' . $newId;
            $mcity->cityNameInUnicode = $request->cityNameInUnicode;
            $mcity->cityNameInEnglish = $request->cityNameInEnglish;
            $mcity->Image = $cityImageName;
            $mcity->Map = $cityMapName;
            $mcity->Image_Name   = $cityImageName;
            $mcity->Map_Name     = $cityMapName;
            $mcity->Culture_Nature = $request->isCultureNature;
            $mcity->text = $request->content;
            $mcity->isActive = 1;
            $mcity->created_at = $currentDateTime;
            $mcity->created_by = Auth::guard('admin')->user()->userId;
            $mcity->save();
            return redirect()->route('admin.live-city-listing')->with('success', 'City created successfully.');
        }else{
            return redirect()->route('admin.live-city-register')
                ->withErrors($validator)
                ->withInput();
        }
    }

    #this method is use for delete live city specific data
    public function liveCitytDelete($id)
    {
        try {
            $currentDateTime = getUserCurrentTime();
            $mcountry = Mcity::findOrFail($id);
            $mcountry->isActive = 0;
            $mcountry->updated_at = $currentDateTime;
            $mcountry->updated_by = Auth::guard('admin')->user()->userId;
            $mcountry->save();

            return redirect()->route('admin.live-city-listing')->with('success', 'User soft deleted successfully.');
        } catch (\Exception $e) {
            return redirect()->route('admin.live-city-listing')->with('error', 'There was an error deleting the user: ' . $e->getMessage());
        }

    }

    #this method is use for edit live city data
    public function liveCityEdit($id)
    {
        $mcity = Mcity::findOrFail($id);
        return view('admin.livecity.live-city-edit' , compact('mcity'));
    }

    public function liveCityUpdate(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'cityNameInUnicode' => 'required|string|max:255',
            'cityNameInEnglish' => 'required|string|max:255',
            'cityImage' => 'nullable|image|max:2048',
            'cityMap' => 'nullable|image|max:2048',
            'isCultureNature' => 'required|boolean',
            'content' => 'required|string',
        ]);

        if ($validator->passes()) {
            $mcity = Mcity::find($id);

            if ($mcity) {
                $currentDateTime = getUserCurrentTime();

                // Handle cityImage upload if present
                if ($request->hasFile('cityImage')) {
                    $cityImage = $request->file('cityImage');
                    $cityImageName = time() . '_' . $cityImage->getClientOriginalName();
                    $cityImage->move(public_path('uploads/city_images'), $cityImageName);
                    // Debugging: Log the filename
                    Log::info("Generated image name: " . $cityImageName);
                    // Update the image field in the model
                    $mcity->image = $cityImageName;
                    $mcity->Image_Name = $cityImageName;
                }

                // Handle cityMap upload if present
                if ($request->hasFile('cityMap')) {
                    $cityMap = $request->file('cityMap');
                    $cityMapName = time() . '_' . $cityMap->getClientOriginalName();
                    $cityMap->move(public_path('uploads/city_maps'), $cityMapName);
                    // Debugging: Log the filename
                    Log::info("Generated map name: " . $cityMapName);
                    // Update the map field in the model
                    $mcity->map = $cityMapName;
                    $mcity->Map_Name = $cityMapName;
                }

                if (empty($request->file('cityImage')) || empty($request->file('cityMap'))) {
                    $cityImageName = $mcity->image;
                    $cityMapName = $mcity->map;
                }

                // Update other fields
                $mcity->update([
                    'cityNameInUnicode' => $request->cityNameInUnicode,
                    'cityNameInEnglish' => $request->cityNameInEnglish,
                    'isActive' => $request->isCultureNature,
                    'image' => $cityImageName,
                    'map' => $cityMapName,
                    'Image_Name' => $cityImageName,
                    'Map_Name' => $cityMapName,
                    'text' => $request->content,
                    'Culture_Nature' => $request->isCultureNature,
                    'updated_at' => $currentDateTime,
                    'updated_by' => Auth::guard('admin')->user()->userId,
                ]);

                return redirect()->route('admin.live-city-listing')->with('success', 'City updated successfully.');
            } else {
                return redirect()->back()->with('error', 'City not found.');
            }
        } else {
            return redirect()->back()
                ->withInput()
                ->withErrors($validator);
        }
    }
}

?>
