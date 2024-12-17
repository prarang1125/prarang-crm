<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use App\Services\ImageUploadService;
use App\Models\S_Cities;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class SCityController extends Controller
{
    #this method is use for show the listing of s cities
    public function index(Request $request)
    {
        $query = S_Cities::query();
        // TODO::Sort: new Record should be on top.
        // Apply search filter if provided
        if ($request->has('search') && !empty($request->input('search'))) {
            $searchTerm = $request->input('search');
            $query->where(function ($q) use ($searchTerm) {
                $q->where('citynameInEnglish', 'like', '%' . $searchTerm . '%')
                ->orWhere('citynameInUnicode', 'like', '%' . $searchTerm . '%');
            });
        }

        // Fetch active cities with pagination
        $scities = $query->where('isActive', 1)->paginate(10);

        return view('admin.scities.scities-listing', compact('scities'));
    }


    #this method is use for show the register page of scities
    public function SCityRegister()
    {
        return view('admin.scities.scities-register');
    }

    #this method is use for save the scity data
    public function SCityStore(Request $request, ImageUploadService $imageUploadService)
    {
        $validator = Validator::make($request->all(), [
            'citynameInEnglish' => 'required|string|max:255',
            'citynameInUnicode' => 'required|string|max:255',
            'sCityImage' => 'required|image|max:2048',
            'content' => 'nullable|string',
        ]);

        if ($validator->passes()) {
            DB::beginTransaction();

            try {
                $lastId = S_Cities::max('cityId');
                $newId = $lastId ? $lastId + 1 : 1;

                $currentDateTime = getUserCurrentTime();

                // Upload City Image
                if ($request->hasFile('sCityImage')) {
                    $uploadCityImage = $imageUploadService->uploadImage($request->file('sCityImage'), $newId, 's_city_images');
                    if (isset($uploadCityImage['error']) && $uploadCityImage['error'] === true) {
                        throw new \Exception('Error while uploading city image.');
                    }

                    $sCityImageName = $uploadCityImage['path'];
                    $sCityImageFullUrl = $uploadCityImage['full_url'];
                } else {
                    // Default image values if no file is uploaded
                    $sCityImageName = '';
                    $sCityImageFullUrl = '';
                }

                // Only update content if provided
                $content = $request->filled('content') ? $request->content : null;

                // Save the city record
                $scities = new S_Cities();
                $scities->cityCode = 'c' . $newId;
                $scities->citynameInUnicode = $request->citynameInUnicode;
                $scities->citynameInEnglish = $request->citynameInEnglish;
                $scities->image = $sCityImageName;
                $scities->Image_Name = $sCityImageFullUrl;
                $scities->State = 0;
                $scities->text = $content; // Only set content if provided
                $scities->isActive = 1;
                $scities->created_at = $currentDateTime;
                $scities->created_by = Auth::guard('admin')->user()->userId;
                $scities->save();

                DB::commit();

                return redirect()->route('admin.scities-listing')->with('success', 'City created successfully.');
            } catch (\Exception $e) {
                DB::rollBack();
                return redirect()->back()->with('error', $e->getMessage());
            }
        } else {
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
    public function SCityUpdate(Request $request, $id, ImageUploadService $imageUploadService)
    {
        $validator = Validator::make($request->all(), [
            'citynameInUnicode' => 'required|string|max:255',
            'citynameInEnglish' => 'required|string|max:255',
            'image' => 'nullable|image|max:2048', // Image is optional
            'content' => 'nullable|string', // Content is optional
        ]);

        if ($validator->passes()) {
            $s_city = S_Cities::find($id);

            if ($s_city) {
                DB::beginTransaction();

                try {
                    $currentDateTime = getUserCurrentTime();

                    // Handle image upload if present
                    if ($request->hasFile('image')) {
                        $uploadCityImage = $imageUploadService->uploadImage($request->file('image'), $id, 's_city_images');
                        if (isset($uploadCityImage['error']) && $uploadCityImage['error'] === true) {
                            throw new \Exception('Error while uploading city image.');
                        }

                        $s_city->image = $uploadCityImage['path'];
                        $s_city->Image_Name = $uploadCityImage['full_url'];
                    }

                    // Update only the fields provided in the request
                    $s_city->citynameInUnicode = $request->citynameInUnicode;
                    $s_city->citynameInEnglish = $request->citynameInEnglish;

                    // Only update content if provided
                    if ($request->filled('content')) {
                        $s_city->text = $request->content;
                    }

                    $s_city->updated_at = $currentDateTime;
                    $s_city->updated_by = Auth::guard('admin')->user()->userId;

                    $s_city->save();

                    DB::commit();

                    return redirect()->route('admin.scities-listing')->with('success', 'City updated successfully.');
                } catch (\Exception $e) {
                    DB::rollBack();
                    return redirect()->back()->with('error', $e->getMessage());
                }
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
