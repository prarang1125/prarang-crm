<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use App\Services\ImageUploadService;
use App\Models\Mcity;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class LiveCityController extends Controller
{
    #this method is use for show the listing of city
    public function index(Request $request)
    {
        $search = $request->input('search');

        $mcitys = Mcity::where('isActive', 1)
            ->when($search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('cityNameInEnglish', 'like', '%' . $search . '%')
                    ->orWhere('cityNameInUnicode', 'like', '%' . $search . '%');
                });
            })
            ->paginate(30);
        return view('admin.livecity.live-city-listing', compact('mcitys'));
    }


    #this method is use for regidter new city
    public function liveCityRegister()
    {
        return view('admin.livecity.live-city-register');
    }

    public function liveCityStore(Request $request, ImageUploadService $imageUploadService)
    {
        $validator = Validator::make($request->all(), [
            'cityNameInEnglish' => 'required|string|max:255',
            'cityNameInUnicode' => 'required|string|max:255',
            'cityImage' => 'required|image|max:2048',
            'cityMap' => 'required|image|max:2048',
            'isCultureNature' => 'required|boolean',
            'content' => 'required|string',
        ]);

        if ($validator->passes()) {
            DB::beginTransaction();
            try {
                $lastId = Mcity::max('cityId');
                $newId = $lastId ? $lastId + 1 : 1;

                // Upload City Image
                $uploadCityImage = $imageUploadService->uploadImage($request->file('cityImage'), $newId, 'city');
                if (isset($uploadCityImage['error']) && $uploadCityImage['error'] === true) {
                    throw new \Exception('Error while uploading city image.');
                }

                // Upload City Map
                $uploadCityMap = $imageUploadService->uploadImage($request->file('cityMap'), $newId, 'city_map');
                if (isset($uploadCityMap['error']) && $uploadCityMap['error'] === true) {
                    throw new \Exception('Error while uploading city map.');
                }

                $currentDateTime = getUserCurrentTime();

                $mcity = new Mcity();
                $mcity->cityCode = 'c' . $newId;
                $mcity->cityNameInUnicode = $request->cityNameInUnicode;
                $mcity->cityNameInEnglish = $request->cityNameInEnglish;
                $mcity->Image = $uploadCityImage['path'];
                $mcity->Map = $uploadCityMap['path'];
                $mcity->Image_Name = $uploadCityImage['full_url'];
                $mcity->Map_Name = $uploadCityMap['full_url'];
                $mcity->Culture_Nature = $request->isCultureNature;
                $mcity->text = $request->content;
                $mcity->isActive = 1;
                $mcity->created_at = $currentDateTime;
                $mcity->created_by = Auth::guard('admin')->user()->userId;
                // dd($mcity);
                $mcity->save();

                DB::commit();
                return redirect()->route('admin.live-city-listing')->with('success', 'City created successfully.');
            } catch (\Exception $e) {
                DB::rollBack();
                return redirect()->back()->with('error', $e->getMessage());
            }
        } else {
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

    #this method is use for update live city data
    public function liveCityUpdate(Request $request, $id, ImageUploadService $imageUploadService)
    {
        $validator = Validator::make($request->all(), [
            'cityNameInUnicode' => 'required|string|max:255',
            'cityNameInEnglish' => 'required|string|max:255',
            'cityImage' => 'nullable|image|max:2048',
            'cityMap' => 'nullable|image|max:2048',
            'isCultureNature' => 'required|boolean',
            'content' => 'nullable|string',
        ]);

        if ($validator->passes()) {
            $mcity = Mcity::find($id);

            if ($mcity) {
                DB::beginTransaction();

                try {
                    $currentDateTime = getUserCurrentTime();

                    // Handle cityImage upload if present
                    if ($request->hasFile('cityImage')) {
                        $uploadCityImage = $imageUploadService->uploadImage($request->file('cityImage'), $id, 'city');
                        if (isset($uploadCityImage['error']) && $uploadCityImage['error'] === true) {
                            throw new \Exception('Error while uploading city image.');
                        }

                        $mcity->Image = $uploadCityImage['path'];
                        $mcity->Image_Name = $uploadCityImage['full_url'];
                    }

                    // Handle cityMap upload if present
                    if ($request->hasFile('cityMap')) {
                        $uploadCityMap = $imageUploadService->uploadImage($request->file('cityMap'), $id, 'city_map');
                        if (isset($uploadCityMap['error']) && $uploadCityMap['error'] === true) {
                            throw new \Exception('Error while uploading city map.');
                        }

                        $mcity->Map = $uploadCityMap['path'];
                        $mcity->Map_Name = $uploadCityMap['full_url'];
                    }

                    // Update non-image fields if required
                    $mcity->cityNameInUnicode = $request->cityNameInUnicode;
                    $mcity->cityNameInEnglish = $request->cityNameInEnglish;
                    $mcity->Culture_Nature = $request->isCultureNature;

                    // Only update content if provided in the request
                    if ($request->filled('content')) {
                        $mcity->text = $request->content;
                    }

                    $mcity->updated_at = $currentDateTime;
                    $mcity->updated_by = Auth::guard('admin')->user()->userId;

                    $mcity->save();

                    DB::commit();

                    return redirect()->route('admin.live-city-listing')->with('success', 'City updated successfully.');
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

?>
