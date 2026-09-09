<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\ByletralPortal;
use App\Models\CountryPortal;
use App\Services\ImageUploadService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class BilateralPortalController extends Controller
{
    /**
     * Display a listing of the bilateral portals.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');

        $bilateralPortals = ByletralPortal::with(['primaryCountry', 'secondaryCountry'])
            ->when($search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('title', 'like', '%' . $search . '%')
                        ->orWhere('slug', 'like', '%' . $search . '%')
                        ->orWhere('content_country_code', 'like', '%' . $search . '%')
                        ->orWhereHas('primaryCountry', function ($country) use ($search) {
                            $country->where('country_name', 'like', '%' . $search . '%');
                        })
                        ->orWhereHas('secondaryCountry', function ($country) use ($search) {
                            $country->where('country_name', 'like', '%' . $search . '%');
                        });
                });
            })
            ->orderBy('created_at', 'desc')
            ->paginate(30);

        return view('admin.bilateral_portal.index', compact('bilateralPortals', 'search'));
    }

    /**
     * Show the form for creating a new bilateral portal.
     */
    public function create()
    {
        $countries = CountryPortal::orderBy('country_name', 'asc')->get();
        $livecountries = DB::table('mcountry')->where('isActive', 1)->get();

        return view('admin.bilateral_portal.create', compact('countries', 'livecountries'));
    }

    /**
     * Store a newly created bilateral portal in storage.
     */
    public function store(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'primary_country_id' => 'required|exists:country_portals,id',
            'secondary_country_id' => 'required|exists:country_portals,id|different:primary_country_id',
            'title' => 'required|string|max:255',
            'slogan' => 'nullable|string|max:255',
            'slug' => 'nullable|string|max:255|unique:byletral_portals,slug',
            'content_country_code' => 'nullable|string|max:10',
            'primary_embassy_link' => 'nullable|string|max:255',
            'secondary_embassy_link' => 'nullable|string|max:255',
            'extended_primary_link' => 'nullable|string',
            'extended_secondary_link' => 'nullable|string',
            'connections' => 'nullable|string',
            'header_scripts' => 'nullable|string',
            'footer_scripts' => 'nullable|string',
            'header_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'footer_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            DB::beginTransaction();

            $imageUploadService = new ImageUploadService();
            $data = $request->except(['header_image', 'footer_image']);

            // Auto-generate slug if not provided
            if (empty($data['slug'])) {
                $data['slug'] = Str::slug($data['title']);
            }

            // Ensure slug is unique
            $originalSlug = $data['slug'];
            $counter = 1;
            while (ByletralPortal::where('slug', $data['slug'])->exists()) {
                $data['slug'] = $originalSlug . '-' . $counter;
                $counter++;
            }

            // Handle image uploads
            if ($request->hasFile('header_image')) {
                $uploadResult = $imageUploadService->uploadImage($request->file('header_image'), 'bilateral_portal_header', 'bilateral_portals');
                if (isset($uploadResult['error'])) {
                    return redirect()->back()
                        ->with('error', 'Header image upload failed: ' . $uploadResult['message'])
                        ->withInput();
                }
                $data['header_image'] = $uploadResult['path'];
            }

            if ($request->hasFile('footer_image')) {
                $uploadResult = $imageUploadService->uploadImage($request->file('footer_image'), 'bilateral_portal_footer', 'bilateral_portals');
                if (isset($uploadResult['error'])) {
                    return redirect()->back()
                        ->with('error', 'Footer image upload failed: ' . $uploadResult['message'])
                        ->withInput();
                }
                $data['footer_image'] = $uploadResult['path'];
            }

            // Handle JSON fields
            if ($request->has('connections') && is_string($request->connections)) {
                $data['connections'] = $request->connections;
            }
            if ($request->has('header_scripts') && is_string($request->header_scripts)) {
                $data['header_scripts'] = $request->header_scripts;
            }
            if ($request->has('footer_scripts') && is_string($request->footer_scripts)) {
                $data['footer_scripts'] = $request->footer_scripts;
            }

            $bilateralPortal = ByletralPortal::create($data);

            DB::commit();

            Log::info('Bilateral Portal created successfully', [
                'portal_id' => $bilateralPortal->id,
                'title' => $bilateralPortal->title,
                'created_by' => Auth::id()
            ]);

            return redirect()->route('admin.bilateral-portals.index')
                ->with('success', 'Bilateral Portal created successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            // dd($e->getMessage());
            Log::error('Error creating bilateral portal', [
                'error' => $e->getMessage(),
                'user_id' => Auth::id()
            ]);


            return redirect()->back()
                ->with('error', 'Error creating bilateral portal: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Display the specified bilateral portal.
     */
    public function show(ByletralPortal $bilateralPortal)
    {
        $bilateralPortal->load(['primaryCountry', 'secondaryCountry']);
        return view('admin.bilateral_portal.show', compact('bilateralPortal'));
    }

    /**
     * Show the form for editing the specified bilateral portal.
     */
    public function edit(ByletralPortal $bilateralPortal)
    {
        $countries = CountryPortal::orderBy('country_name', 'asc')->get();
        $livecountries = DB::table('mcountry')->where('isActive', 1)->get();

        $bilateralPortal->load(['primaryCountry', 'secondaryCountry']);
        return view('admin.bilateral_portal.edit', compact('bilateralPortal', 'countries', 'livecountries'));
    }

    /**
     * Update the specified bilateral portal in storage.
     */
    public function update(Request $request, ByletralPortal $bilateralPortal)
    {
        $validator = Validator::make($request->all(), [
            'primary_country_id' => 'required|exists:country_portals,id',
            'secondary_country_id' => 'required|exists:country_portals,id|different:primary_country_id',
            'title' => 'required|string|max:255',
            'slogan' => 'nullable|string|max:255',
            'slug' => 'nullable|string|max:255|unique:byletral_portals,slug,' . $bilateralPortal->id,
            'content_country_code' => 'nullable|string|max:10',
            'primary_embassy_link' => 'nullable|string|max:500',
            'secondary_embassy_link' => 'nullable|string|max:500',
            'primary_country_maps' => 'nullable|string|max:500',
            'secondary_country_maps' => 'nullable|string|max:500',
            'primary_country_timezone' => 'nullable|string|max:100',
            'secondary_country_timezone' => 'nullable|string|max:100',
            'extended_primary_link' => 'nullable|string',
            'extended_secondary_link' => 'nullable|string',
            'connections' => 'nullable|string',
            'header_scripts' => 'nullable|string',
            'footer_scripts' => 'nullable|string',
            'header_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'footer_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            DB::beginTransaction();

            $imageUploadService = new ImageUploadService();
            $data = $request->except(['header_image', 'footer_image']);

            // Auto-generate slug if not provided
            if (empty($data['slug'])) {
                $data['slug'] = Str::slug($data['title']);
            }

            // Ensure slug is unique (excluding current record)
            $originalSlug = $data['slug'];
            $counter = 1;
            while (ByletralPortal::where('slug', $data['slug'])->where('id', '!=', $bilateralPortal->id)->exists()) {
                $data['slug'] = $originalSlug . '-' . $counter;
                $counter++;
            }

            // Handle image uploads
            if ($request->hasFile('header_image')) {
                $uploadResult = $imageUploadService->uploadImage($request->file('header_image'), 'bilateral_portal_header', 'bilateral_portals');
                if (isset($uploadResult['error'])) {
                    return redirect()->back()
                        ->with('error', 'Header image upload failed: ' . $uploadResult['message'])
                        ->withInput();
                }
                $data['header_image'] = $uploadResult['path'];
            }

            if ($request->hasFile('footer_image')) {
                $uploadResult = $imageUploadService->uploadImage($request->file('footer_image'), 'bilateral_portal_footer', 'bilateral_portals');
                if (isset($uploadResult['error'])) {
                    return redirect()->back()
                        ->with('error', 'Footer image upload failed: ' . $uploadResult['message'])
                        ->withInput();
                }
                $data['footer_image'] = $uploadResult['path'];
            }

            // Handle JSON fields
            if ($request->has('connections') && is_string($request->connections)) {
                $data['connections'] = $request->connections;
            }
            if ($request->has('header_scripts') && is_string($request->header_scripts)) {
                $data['header_scripts'] = $request->header_scripts;
            }
            if ($request->has('footer_scripts') && is_string($request->footer_scripts)) {
                $data['footer_scripts'] = $request->footer_scripts;
            }

            $data['extended_primary_link'] = $data['extended_primary_link'] ?? "";
            $data['extended_secondary_link'] = $data['extended_secondary_link'] ?? "";

            $bilateralPortal->update($data);

            DB::commit();

            return redirect()->route('admin.bilateral-portals.index')
                ->with('success', 'Bilateral Portal updated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->back()
                ->with('error', 'Error updating bilateral portal: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Remove the specified bilateral portal from storage.
     */
    public function destroy(ByletralPortal $bilateralPortal)
    {
        try {
            DB::beginTransaction();

            $portalTitle = $bilateralPortal->title;
            $portalId = $bilateralPortal->id;

            $bilateralPortal->delete();

            DB::commit();

            Log::info('Bilateral Portal deleted successfully', [
                'portal_id' => $portalId,
                'title' => $portalTitle,
                'deleted_by' => Auth::id()
            ]);

            return redirect()->route('admin.bilateral-portals.index')
                ->with('success', 'Bilateral Portal deleted successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error deleting bilateral portal', [
                'error' => $e->getMessage(),
                'portal_id' => $bilateralPortal->id,
                'user_id' => Auth::id()
            ]);

            return redirect()->back()
                ->with('error', 'Error deleting bilateral portal: ' . $e->getMessage());
        }
    }

    /**
     * Get bilateral portals involving a specific country
     */
    public function getByCountry(Request $request)
    {
        $countryId = $request->input('country_id');

        if (!$countryId) {
            return response()->json(['error' => 'Country ID is required'], 400);
        }

        $portals = ByletralPortal::involvingCountry($countryId)
            ->with(['primaryCountry', 'secondaryCountry'])
            ->get();

        return response()->json($portals);
    }

    /**
     * Bulk delete bilateral portals
     */
    public function bulkDelete(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'portal_ids' => 'required|array',
            'portal_ids.*' => 'exists:byletral_portals,id'
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => 'Invalid portal IDs'], 400);
        }

        try {
            DB::beginTransaction();

            $deletedCount = ByletralPortal::whereIn('id', $request->portal_ids)->delete();

            DB::commit();

            Log::info('Bulk delete bilateral portals', [
                'deleted_count' => $deletedCount,
                'portal_ids' => $request->portal_ids,
                'deleted_by' => Auth::id()
            ]);

            return response()->json([
                'success' => true,
                'message' => "Successfully deleted {$deletedCount} bilateral portals."
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error in bulk delete bilateral portals', [
                'error' => $e->getMessage(),
                'portal_ids' => $request->portal_ids,
                'user_id' => Auth::id()
            ]);

            return response()->json(['error' => 'Error deleting portals: ' . $e->getMessage()], 500);
        }
    }
}
