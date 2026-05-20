<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\CountryPortal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CountryPortalController extends Controller
{
    /**
     * Display a listing of the country portals.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');

        $countryPortals = CountryPortal::when($search, function ($query, $search) {
            $query->where(function ($q) use ($search) {
                $q->where('country_name', 'like', '%' . $search . '%')
                    ->orWhere('country_code', 'like', '%' . $search . '%')
                    ->orWhere('country_name_locale', 'like', '%' . $search . '%')
                    ->orWhere('locale_lang', 'like', '%' . $search . '%');
            });
        })
            ->orderBy('country_name', 'asc')
            ->paginate(30);

        return view('admin.country_portal.index', compact('countryPortals', 'search'));
    }

    /**
     * Show the form for creating a new country portal.
     */
    public function create()
    {
        $countries = DB::table('mcountry')->where('isActive', 1)->get();
        return view('admin.country_portal.create', compact('countries'));
    }

    /**
     * Store a newly created country portal in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'country_name' => 'required|string|max:255',
            'country_code' => 'required|string|max:10|unique:country_portals,country_code',
            'anlytics_code' => 'nullable|string|max:255',
            'analytics_slug' => 'nullable|string|max:255|unique:country_portals,analytics_slug',
            'country_name_locale' => 'nullable|string|max:255',
            'slogan' => 'nullable|string|max:255',
            'locale_lang' => 'nullable|string|max:10',
            'maps' => 'nullable|string|max:1000',

            'timezone' => 'nullable|string|max:255',
            'weather' => 'nullable|string',
            'news' => 'nullable|string',
            'local_metrics' => 'nullable|string',
            'important_links' => 'nullable|string'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            DB::beginTransaction();

            $data = $request->all();

            // Auto-generate analytics slug if not provided
            if (empty($data['analytics_slug'])) {
                $data['analytics_slug'] = Str::slug($data['country_name']);
            }

            // Ensure analytics slug is unique
            $originalSlug = $data['analytics_slug'];
            $counter = 1;
            while (CountryPortal::where('analytics_slug', $data['analytics_slug'])->exists()) {
                $data['analytics_slug'] = $originalSlug . '-' . $counter;
                $counter++;
            }

            // Handle JSON fields - only process if not empty
            $jsonFields = ['local_metrics', 'important_links'];
            foreach ($jsonFields as $field) {
                if ($request->has($field) && !empty($request->$field)) {
                    if (is_array($request->$field)) {
                        $data[$field] = json_encode($request->$field);
                    } elseif (is_string($request->$field)) {
                        // Special handling for important_links which now comes as JSON
                        if ($field === 'important_links') {
                            // The important_links field now comes as pre-formatted JSON
                            $decoded = json_decode($request->$field);
                            if (json_last_error() === JSON_ERROR_NONE) {
                                $data[$field] = $request->$field;
                            } else {
                                $data[$field] = null;
                            }
                        } elseif ($field === 'local_metrics') {
                            // For news and local_metrics, the data comes as JSON string from frontend
                            $decoded = json_decode($request->$field);
                            if (json_last_error() === JSON_ERROR_NONE) {
                                $data[$field] = $request->$field;
                            } else {
                                $data[$field] = null;
                            }
                        } else {
                            // Validate JSON string for other fields
                            $decoded = json_decode($request->$field);
                            if (json_last_error() === JSON_ERROR_NONE) {
                                // Valid JSON, keep as is
                                $data[$field] = $request->$field;
                            } else {
                                // Invalid JSON, set to null
                                $data[$field] = null;
                            }
                        }
                    }
                } else {
                    // Field is empty or not present, set to null
                    $data[$field] = null;
                }
            }

            $countryPortal = CountryPortal::create($data);

            DB::commit();

            Log::info('Country Portal created successfully', [
                'country_id' => $countryPortal->id,
                'country_name' => $countryPortal->country_name,
                'created_by' => Auth::id()
            ]);

            return redirect()->route('admin.country-portals.index')
                ->with('success', 'Country Portal created successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error creating country portal', [
                'error' => $e->getMessage(),
                'user_id' => Auth::id()
            ]);

            return redirect()->back()
                ->with('error', 'Error creating country portal: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Display the specified country portal.
     */
    public function show(CountryPortal $countryPortal)
    {
        return view('admin.country_portal.show', compact('countryPortal'));
    }

    /**
     * Show the form for editing the specified country portal.
     */
    public function edit(CountryPortal $countryPortal)
    {
        $countries = DB::table('mcountry')->where('isActive', 1)->get();
        return view('admin.country_portal.edit', compact('countryPortal', 'countries'));
    }

    /**
     * Update the specified country portal in storage.
     */
    public function update(Request $request, CountryPortal $countryPortal)
    {

        $validator = Validator::make($request->all(), [
            'country_name' => 'required|string|max:255',
            'country_code' => 'required|string|max:10|unique:country_portals,country_code,' . $countryPortal->id,
            'anlytics_code' => 'nullable|string|max:255',
            'analytics_slug' => 'nullable|string|max:255|unique:country_portals,analytics_slug,' . $countryPortal->id,
            'country_name_locale' => 'nullable|string|max:255',
            'slogan' => 'nullable|string|max:255',
            'locale_lang' => 'nullable|string|max:10',
            'maps' => 'nullable|string|max:1000',

            'timezone' => 'nullable|string|max:255',
            'weather' => 'nullable|string',
            'news' => 'nullable|string',
            'local_metrics' => 'nullable|string',
            'important_links' => 'nullable|string'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            DB::beginTransaction();


            $data = $request->only([
                'country_name',
                'country_code',
                'anlytics_code',
                'analytics_slug',
                'country_name_locale',
                'slogan',
                'locale_lang',
                'maps',

                'timezone',
                'weather',
                'news',
                'local_metrics',
                'important_links'
            ]);



            // Auto-generate analytics slug if not provided
            if (empty($data['analytics_slug'])) {
                $data['analytics_slug'] = Str::slug($data['country_name']);
            }

            // Ensure analytics slug is unique (excluding current record)
            $originalSlug = $data['analytics_slug'];
            $counter = 1;
            while (CountryPortal::where('analytics_slug', $data['analytics_slug'])->where('id', '!=', $countryPortal->id)->exists()) {
                $data['analytics_slug'] = $originalSlug . '-' . $counter;
                $counter++;
            }

            // Handle JSON fields - only process if not empty
            $jsonFields = ['local_metrics', 'important_links'];
            foreach ($jsonFields as $field) {
                if ($request->has($field) && !empty($request->$field)) {
                    if (is_array($request->$field)) {
                        $data[$field] = json_encode($request->$field);
                    } elseif (is_string($request->$field)) {
                        // Special handling for important_links which now comes as JSON
                        if ($field === 'important_links') {
                            // The important_links field now comes as pre-formatted JSON
                            $decoded = json_decode($request->$field);
                            if (json_last_error() === JSON_ERROR_NONE) {
                                $data[$field] = $request->$field;
                            } else {
                                $data[$field] = null;
                            }
                        } elseif ($field === 'local_metrics') {
                            // For news and local_metrics, the data comes as JSON string from frontend
                            $decoded = json_decode($request->$field);
                            if (json_last_error() === JSON_ERROR_NONE) {
                                $data[$field] = $request->$field;
                            } else {
                                $data[$field] = null;
                            }
                        } else {
                            // Validate JSON string for other fields
                            $decoded = json_decode($request->$field);
                            if (json_last_error() === JSON_ERROR_NONE) {
                                // Valid JSON, keep as is
                                $data[$field] = $request->$field;
                            } else {
                                // Invalid JSON, set to null
                                $data[$field] = null;
                            }
                        }
                    }
                } else {
                    // Field is empty or not present, set to null
                    $data[$field] = null;
                }
            }
            $data['news'] = $request->news ?? null;
            $countryPortal->update($data);

            DB::commit();


            return redirect()->route('admin.country-portals.index')
                ->with('success', 'Country Portal updated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->back()
                ->with('error', 'Error updating country portal: ')
                ->withInput();
        }
    }

    /**
     * Remove the specified country portal from storage.
     */
    public function destroy($id)
    {
        try {
            $countryPortal = CountryPortal::findOrFail($id);

            DB::beginTransaction();

            Log::info('Attempting to delete country portal', [
                'country_id' => $countryPortal->id,
                'country_name' => $countryPortal->country_name,
                'user_id' => Auth::id()
            ]);

            // Check if country is used in bilateral portals
            $bilateralPortalsCount = $countryPortal->primaryBilateralPortals()->count() +
                $countryPortal->secondaryBilateralPortals()->count();

            if ($bilateralPortalsCount > 0) {
                DB::rollBack();
                Log::warning('Cannot delete country - in use', [
                    'country_id' => $countryPortal->id,
                    'bilateral_portals_count' => $bilateralPortalsCount
                ]);
                return redirect()->back()
                    ->with('error', 'Cannot delete country. It is being used in ' . $bilateralPortalsCount . ' bilateral portal(s).');
            }

            $countryName = $countryPortal->country_name;
            $countryId = $countryPortal->id;

            $countryPortal->delete();

            DB::commit();

            Log::info('Country Portal deleted successfully', [
                'country_id' => $countryId,
                'country_name' => $countryName,
                'deleted_by' => Auth::id()
            ]);

            return redirect()->route('admin.country-portals.index')
                ->with('success', 'Country Portal deleted successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error deleting country portal', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'user_id' => Auth::id()
            ]);

            return redirect()->back()
                ->with('error', 'Error deleting country portal: ' . $e->getMessage());
        }
    }

    /**
     * Bulk delete country portals
     */
    public function bulkDelete(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'country_ids' => 'required|array',
            'country_ids.*' => 'exists:country_portals,id'
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => 'Invalid country IDs'], 400);
        }

        try {
            DB::beginTransaction();

            // Check if any countries are used in bilateral portals
            $usedCountries = CountryPortal::whereIn('id', $request->country_ids)
                ->whereHas('primaryBilateralPortals')
                ->orWhereHas('secondaryBilateralPortals')
                ->pluck('country_name')
                ->toArray();

            if (!empty($usedCountries)) {
                return response()->json([
                    'error' => 'Cannot delete countries that are used in bilateral portals: ' . implode(', ', $usedCountries)
                ], 400);
            }

            $deletedCount = CountryPortal::whereIn('id', $request->country_ids)->delete();

            DB::commit();

            Log::info('Bulk delete country portals', [
                'deleted_count' => $deletedCount,
                'country_ids' => $request->country_ids,
                'deleted_by' => Auth::id()
            ]);

            return response()->json([
                'success' => true,
                'message' => "Successfully deleted {$deletedCount} country portals."
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error in bulk delete country portals', [
                'error' => $e->getMessage(),
                'country_ids' => $request->country_ids,
                'user_id' => Auth::id()
            ]);

            return response()->json(['error' => 'Error deleting countries: ' . $e->getMessage()], 500);
        }
    }
}
