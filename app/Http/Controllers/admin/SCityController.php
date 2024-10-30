<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\S_Cities;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class SCityController extends Controller
{
    #this method is use for show the listing of s cities
    public function index()
    {
        $scities = S_Cities::where('isActive', 1)->get();
        return view('admin.scities.scities-listing', compact('scities'));
    }

    #this method is use for show the register page of scities
    public function SCityRegister()
    {
        return view('admin.scities.scities-register');
    }

    #this method is use for store scity data
    public function SCityStore(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'citynameInEnglish' => 'required|string|max:255',
            'citynameInUnicode' => 'required|string|max:255',
            'sCityImage' => 'required|image|max:2048',
            'content' => 'required|string',
        ]);

        if($validator->passes()){
            $currentDateTime = getUserCurrentTime();
            if($request->hasFile('sCityImage')){
                $sCityImage = $request->file('sCityImage');
                $sCityImageName = time() . '_' . $sCityImage->getClientOriginalName();
                $sCityImage->move(public_path('uploads/s_city_images'), $sCityImageName);
            }

            $lastId = S_Cities::max('cityId');
            $newId = $lastId ? $lastId + 1 : 1;

            $scities = new S_Cities();
            $scities->cityCode = 'c' . $newId;
            $scities->citynameInUnicode = $request->citynameInUnicode;
            $scities->citynameInEnglish = $request->citynameInEnglish;
            $scities->image = $sCityImageName;
            $scities->Image_Name   = $sCityImageName;
            $scities->State = 0;
            $scities->text = $request->content;
            $scities->isActive = 1;
            $scities->created_at = $currentDateTime;
            $scities->created_by = Auth::guard('admin')->user()->userId;
            $scities->save();
            return redirect()->route('admin.scities-listing')->with('success', 'City created successfully.');
        }else{
            return redirect()->route('admin.scities-register')
                ->withErrors($validator)
                ->withInput();
        }
    }

    #this method is use for delete s_city specific data
    public function SCityDelete($id)
    {
        try {
            $currentDateTime = getUserCurrentTime();
            $mcountry = S_Cities::findOrFail($id);
            $mcountry->isActive = 0;
            $mcountry->updated_at = $currentDateTime;
            $mcountry->updated_by = Auth::guard('admin')->user()->userId;
            $mcountry->save();

            return redirect()->route('admin.scities-listing')->with('success', 'User soft deleted successfully.');
        } catch (\Exception $e) {
            return redirect()->route('admin.scities-listing')->with('error', 'There was an error deleting the user: ' . $e->getMessage());
        }
    }

    #this method is use for show edit page with old data for update
    public function SCityEdit($id)
    {
        $s_city = S_Cities::findOrFail($id);
        return view('admin.scities.scities-edit' , compact('s_city'));
    }

    #this method is use for update specific data
    public function SCityUpdate(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'citynameInUnicode' => 'required|string|max:255',
            'citynameInEnglish' => 'required|string|max:255',
            'image' => 'nullable|image|max:2048',
            'content' => 'required|string',
        ]);

        if ($validator->passes()) {
            $s_city = S_Cities::find($id);

            if ($s_city) {
                $currentDateTime = getUserCurrentTime();

                // Handle cityImage upload if present
                if ($request->hasFile('image')) {
                    $cityImage = $request->file('image');
                    $cityImageName = time() . '_' . $cityImage->getClientOriginalName();
                    $cityImage->move(public_path('uploads/s_city_images'), $cityImageName);
                    // Debugging: Log the filename
                    Log::info("Generated image name: " . $cityImageName);
                    // Update the image field in the model
                    $s_city->image = $cityImageName;
                    $s_city->Image_Name = $cityImageName;
                }

                if (empty($request->file('image'))) {
                    $cityImageName = $s_city->image;
                }

                // Update other fields
                $s_city->update([
                    'citynameInUnicode' => $request->citynameInUnicode,
                    'citynameInEnglish' => $request->citynameInEnglish,
                    'image' => $cityImageName,
                    'Image_Name' => $cityImageName,
                    'State' => 0,
                    'text' => $request->content,
                    'isActive' => 1,
                    'updated_at' => $currentDateTime,
                    'updated_by' => Auth::guard('admin')->user()->userId,
                ]);

                return redirect()->route('admin.scities-listing')->with('success', 'City updated successfully.');
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
