<?php
namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use App\Services\ImageUploadService;
use App\Models\Mregion;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class RegionController extends Controller
{
    #this method is use for region listing
    public function index(Request $request)
    {
        $search = $request->input('search');
        $regions = Mregion::where('isActive', 1)
                    ->when($search, function ($query, $search) {
                        $query->where(function ($q) use ($search) {
                            $q->where('regionnameInEnglish', 'like', "%{$search}%")
                            ->orWhere('regionnameInUnicode', 'like', "%{$search}%");
                        });
                    })
                    ->paginate(30);

        return view("admin.region.region-listing", compact('regions'));
    }

    #this method is use for show regiter new region page
    public function regionRegister()
    {
        return view('admin.region.regio-register');
    }

    #this method is use for store new region data
    public function regionStore(Request $request, ImageUploadService $imageUploadService)
    {
        $validator = Validator::make($request->all(), [
            'regionnameInEnglish' => 'required|string|max:255',
            'regionnameInUnicode' => 'required|string|max:255',
            'regionImage' => 'required|image|max:2048',
            'regionMap' => 'required|image|max:2048',
            'isCultureNature' => 'required|boolean',
            'content' => 'nullable|string', // Content is optional
        ]);

        if ($validator->passes()) {
            DB::beginTransaction();

            try {
                // Generate a new region ID
                $lastId = Mregion::max('regionId');
                $newId = $lastId ? $lastId + 1 : 1;

                // Upload Region Image
                $uploadRegionImage = $imageUploadService->uploadImage($request->file('regionImage'), $newId, 'region_images');
                if (isset($uploadRegionImage['error']) && $uploadRegionImage['error'] === true) {
                    throw new \Exception('Error while uploading region image.');
                }

                // Upload Region Map
                $uploadRegionMap = $imageUploadService->uploadImage($request->file('regionMap'), $newId, 'region_maps');
                if (isset($uploadRegionMap['error']) && $uploadRegionMap['error'] === true) {
                    throw new \Exception('Error while uploading region map.');
                }

                // Create new Region record
                $currentDateTime = getUserCurrentTime();

                $mregion = new Mregion();
                $mregion->regionCode = 'r' . $newId;
                $mregion->regionnameInUnicode = $request->regionnameInUnicode;
                $mregion->regionnameInEnglish = $request->regionnameInEnglish;
                $mregion->isActive = 1;
                $mregion->image = $uploadRegionImage['path'];
                $mregion->map = $uploadRegionMap['path'];
                $mregion->Image_Name = $uploadRegionImage['full_url'];
                $mregion->Map_Name = $uploadRegionMap['full_url'];
                $mregion->Culture_Nature = $request->isCultureNature;

                // Only update content if provided
                if ($request->filled('content')) {
                    $mregion->text = $request->content;
                }

                $mregion->created_at = $currentDateTime;
                $mregion->created_by = Auth::guard('admin')->user()->userId;
                $mregion->save();

                DB::commit();
                return redirect()->route('admin.region-listing')->with('success', 'Region created successfully.');
            } catch (\Exception $e) {
                DB::rollBack();
                return redirect()->back()->with('error', $e->getMessage());
            }
        } else {
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

    public function regionUpdate(Request $request, $id, ImageUploadService $imageUploadService)
    {
        $validator = Validator::make($request->all(), [
            'regionnameInUnicode' => 'required|string|max:255',
            'regionnameInEnglish' => 'required|string|max:255',
            'regionImage' => 'nullable|image|max:2048',
            'regionMap' => 'nullable|image|max:2048',
            'isCultureNature' => 'required|boolean',
            'content' => 'nullable|string',
        ]);

        if ($validator->passes()) {
            $mregion = Mregion::find($id);

            if ($mregion) {
                DB::beginTransaction();

                try {
                    $currentDateTime = getUserCurrentTime();

                    // Handle regionImage upload if present
                    if ($request->hasFile('regionImage')) {
                        $uploadRegionImage = $imageUploadService->uploadImage($request->file('regionImage'), $id, 'region_images');
                        if (isset($uploadRegionImage['error']) && $uploadRegionImage['error'] === true) {
                            throw new \Exception('Error while uploading region image.');
                        }

                        $mregion->image = $uploadRegionImage['path'];
                        $mregion->Image_Name = $uploadRegionImage['full_url'];
                    }

                    // Handle regionMap upload if present
                    if ($request->hasFile('regionMap')) {
                        $uploadRegionMap = $imageUploadService->uploadImage($request->file('regionMap'), $id, 'region_maps');
                        if (isset($uploadRegionMap['error']) && $uploadRegionMap['error'] === true) {
                            throw new \Exception('Error while uploading region map.');
                        }

                        $mregion->map = $uploadRegionMap['path'];
                        $mregion->Map_Name = $uploadRegionMap['full_url'];
                    }

                    // Update other fields only if necessary
                    $mregion->regionnameInUnicode = $request->regionnameInUnicode;
                    $mregion->regionnameInEnglish = $request->regionnameInEnglish;
                    $mregion->Culture_Nature = $request->isCultureNature;

                    // Preserve content unless provided
                    if ($request->filled('content')) {
                        $mregion->text = $request->content;
                    }

                    $mregion->updated_at = $currentDateTime;
                    $mregion->updated_by = Auth::guard('admin')->user()->userId;

                    $mregion->save();

                    DB::commit();

                    return redirect()->route('admin.region-listing')->with('success', 'Region updated successfully.');
                } catch (\Exception $e) {
                    DB::rollBack();
                    return redirect()->back()->with('error', $e->getMessage());
                }
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
