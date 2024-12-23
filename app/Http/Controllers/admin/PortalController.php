<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Portal;
use App\Services\ImageUploadService;
use Illuminate\Http\Request;

class PortalController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $portals = Portal::paginate(20);

        return view('admin.portal.index', compact('portals'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.portal.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, ImageUploadService $imageUploadService)
    {
        $validated = $request->validate([
            'city_id' => 'required|integer|unique:portals',
            'slug' => 'required|unique:portals',
            'city_code' => 'required|string|max:10|unique:portals',
            'city_name' => 'required|string|max:255',
            'city_name_local' => 'required|string|max:255',
            'city_slogan' => 'required|string|max:255',
            'map_link' => 'required|url',
            'weather_widget_code' => 'required|string',
            'sports_widget_code' => 'required|string',
            'news_widget_code' => 'required|string',
            'local_matrics' => 'nullable',
            'header_image' => 'required|max:2048',
            'footer_image' => 'required|max:2048',
            'local_info_image' => 'required|max:2048',
            'local_lang' => 'required|string|max:50',
        ]);

        $fileFields = ['header_image', 'footer_image', 'local_info_image'];

        // Loop through file fields and handle uploads
        foreach ($fileFields as $field) {
            if ($request->hasFile($field)) {
                $validated[$field] = $imageUploadService->uploadImage($request->file($field), 'portal', 'portal')['path'];
            }
        }

        Portal::create($validated);

        return redirect()->route('portal.index')->with('success', 'Portal created successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Portal $portal)
    {
        return view('admin.portal.edit', compact('portal'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Portal $portal, ImageUploadService $imageUploadService)
    {
        $validated = $request->validate([
            'city_id' => 'required|integer|unique:portals,city_id,'.$portal->id,
            'slug' => 'required|string|max:255|unique:portals,slug,'.$portal->id,
            'city_code' => 'required|string|max:10|unique:portals,city_code,'.$portal->id,
            'city_name' => 'required|string|max:255',
            'city_name_local' => 'required|string|max:255',
            'city_slogan' => 'required|string|max:255',
            'map_link' => 'required|url',
            'weather_widget_code' => 'required|string',
            'sports_widget_code' => 'required|string',
            'news_widget_code' => 'required|string',
            'local_matrics' => 'nullable',
            'header_image' => 'nullable|max:2048',
            'footer_image' => 'nullable|max:2048',
            'local_info_image' => 'nullable|max:2048',
            'local_lang' => 'required|string|max:50',
        ]);

        // Handle file uploads (if any)
        if ($request->hasFile('header_image')) {
            $validated['header_image'] = $imageUploadService->uploadImage($request->file('header_image'), 'portal', 'portal')['path'];
        }

        if ($request->hasFile('footer_image')) {
            $validated['footer_image'] = $imageUploadService->uploadImage($request->file('footer_image'), 'portal', 'portal')['path'];
        }

        if ($request->hasFile('local_info_image')) {
            $validated['local_info_image'] = $imageUploadService->uploadImage($request->file('local_info_image'), 'portal', 'portal')['path'];
        }

        // Update the portal
        $portal->update($validated);

        return redirect()->route('portal.index')->with('success', 'Portal updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Portal $portal)
    {
        $portal->delete();

        return redirect()->route('portal.index')->with('success', 'Portal deleted successfully!');
    }
}
