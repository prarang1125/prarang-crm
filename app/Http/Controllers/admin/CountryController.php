<?php
namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\ImageUploadService;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use App\Models\Mcountry;

class CountryController extends Controller
{
    #this method is use for show country listing
    public function index(Request $request)
    {
        $search = $request->input('search');
        // TODO::Sort it to Display letest record on Top
        $mcountrys = Mcountry::where('isActive', 1)
            ->when($search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('countryNameInEnglish', 'like', '%' . $search . '%')
                    ->orWhere('countryNameInUnicode', 'like', '%' . $search . '%');
                });
            })
            ->paginate(30);

        return view('admin.country.country-listing', compact('mcountrys'));
    }

    #this method is use for show country register page
    public function countryRegister()
    {
        return view('admin.country.country-register');
    }

    #this method is use for store country data
    public function countryStore(Request $request, ImageUploadService $imageUploadService)
    {
        $validator = Validator::make($request->all(), [
            'countryNameInEnglish' => 'required|string|max:255',
            'countryNameInUnicode' => 'required|string|max:255',
            'countryImage' => 'required|image|max:2048',
            'countryMap' => 'required|image|max:2048',
            'isCultureNature' => 'required|boolean',
            'content' => 'required|string',
        ]);

        if ($validator->passes()) {
            DB::beginTransaction();
            try {
                $lastId = Mcountry::max('countryId');
                $newId = $lastId ? $lastId + 1 : 1;

                // Upload Country Image
                $uploadCountryImage = $imageUploadService->uploadImage($request->file('countryImage'),  $newId, 'country');
                if (isset($uploadCountryImage['error']) && $uploadCountryImage['error'] === true) {
                    throw new \Exception('Error while uploading country image.');
                }

                // Upload Country Map
                $uploadCountryMap = $imageUploadService->uploadImage($request->file('countryMap'),  $newId, 'country_map');
                if (isset($uploadCountryMap['error']) && $uploadCountryMap['error'] === true) {
                    throw new \Exception('Error while uploading country map.');
                }

                $currentDateTime = getUserCurrentTime();

                $mcountry = new Mcountry();
                $mcountry->countryCode = 'CON' . $newId;
                $mcountry->countryNameInUnicode = $request->countryNameInUnicode;
                $mcountry->countryNameInEnglish = $request->countryNameInEnglish;
                $mcountry->Image = $uploadCountryImage['path'];
                $mcountry->Map = $uploadCountryMap['path'];
                $mcountry->Image_Name = $uploadCountryImage['full_url'];
                $mcountry->Map_Name = $uploadCountryMap['full_url'];
                $mcountry->Culture_Nature = $request->isCultureNature;
                $mcountry->text = $request->content;
                $mcountry->isActive = 1;
                $mcountry->created_at = $currentDateTime;
                $mcountry->created_by = Auth::guard('admin')->user()->userId;
                $mcountry->save();

                DB::commit();
                return redirect()->route('admin.country-listing')->with('success', 'Country created successfully.');
            } catch (\Exception $e) {
                DB::rollBack();
                return redirect()->back()->with('error', $e->getMessage());
            }
        } else {
            return redirect()->route('admin.country-register')
                ->withErrors($validator)
                ->withInput();
        }
    }


    #this method is use for delete specific data
    public function countrytDelete($id)
    {
        try {
            $currentDateTime = getUserCurrentTime();
            $mcountry = Mcountry::findOrFail($id);
            $mcountry->isActive = 0;
            $mcountry->updated_at = $currentDateTime;
            $mcountry->updated_by = Auth::guard('admin')->user()->userId;
            $mcountry->save();

            return redirect()->route('admin.country-listing')->with('success', 'User soft deleted successfully.');
        } catch (\Exception $e) {
            return redirect()->route('admin.country-listing')->with('error', 'There was an error deleting the user: ' . $e->getMessage());
        }
    }

    #this method is use for edit mcountry data
    public function countryEdit($id)
    {
        $mcountry = Mcountry::findOrFail($id);
        return view('admin.country.country-edit' , compact('mcountry'));
    }

    public function countryUpdate(Request $request, $id, ImageUploadService $imageUploadService)
    {
        $validator = Validator::make($request->all(), [
            'countryNameInUnicode' => 'required|string|max:255',
            'countryNameInEnglish' => 'required|string|max:255',
            'countryImage' => 'nullable|image|max:2048',
            'countryMap' => 'nullable|image|max:2048',
            'isCultureNature' => 'required|boolean',
            'content' => 'required|string',
        ]);

        if ($validator->passes()) {
            $mcountry = Mcountry::find($id);

            if ($mcountry) {
                DB::beginTransaction();

                try {
                    $currentDateTime = getUserCurrentTime();

                    // Handle countryImage upload if present
                    if ($request->hasFile('countryImage')) {
                        $uploadCountryImage = $imageUploadService->uploadImage($request->file('countryImage'), $id, 'country');
                        if (isset($uploadCountryImage['error']) && $uploadCountryImage['error'] === true) {
                            throw new \Exception('Error while uploading country image.');
                        }

                        $mcountry->Image = $uploadCountryImage['path'];
                        $mcountry->Image_Name = $uploadCountryImage['full_url'];
                    }

                    // Handle countryMap upload if present
                    if ($request->hasFile('countryMap')) {
                        $uploadCountryMap = $imageUploadService->uploadImage($request->file('countryMap'), $id, 'country_map');
                        if (isset($uploadCountryMap['error']) && $uploadCountryMap['error'] === true) {
                            throw new \Exception('Error while uploading country map.');
                        }

                        $mcountry->Map = $uploadCountryMap['path'];
                        $mcountry->Map_Name = $uploadCountryMap['full_url'];
                    }

                    // Update other fields only if images are provided or if required fields are updated
                    $mcountry->countryNameInUnicode = $request->countryNameInUnicode;
                    $mcountry->countryNameInEnglish = $request->countryNameInEnglish;
                    $mcountry->Culture_Nature = $request->isCultureNature;
                    $mcountry->text = $request->content;
                    $mcountry->updated_at = $currentDateTime;
                    $mcountry->updated_by = Auth::guard('admin')->user()->userId;

                    $mcountry->save();

                    DB::commit();

                    return redirect()->route('admin.country-listing')->with('success', 'Country updated successfully.');
                } catch (\Exception $e) {
                    DB::rollBack();
                    return redirect()->back()->with('error', $e->getMessage());
                }
            } else {
                return redirect()->back()->with('error', 'Country not found.');
            }
        } else {
            return redirect()->back()
                ->withInput()
                ->withErrors($validator);
        }
    }
}
?>
