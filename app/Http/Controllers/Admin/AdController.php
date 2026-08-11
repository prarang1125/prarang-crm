<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Ad;

class AdController extends Controller
{
    public function index()
    {
        $data = Ad::all();
        return view('admin.ads.index', compact('data'));
    }
    public function create()
    {
        return view('admin.ads.create');
    }
    public function store(Request $request)
    {
        $data = $request->validate([
            'partner_id' => 'required|integer',
            'city_id' => 'required|integer',
            'creative_file' => 'required|image|mimes:jpg,jpeg,png,webp|max:2048',
            'ad_link' => 'required|url',
            'ad_title' => 'required|string|max:255',
            'cta_title' => 'required|string|max:255',
            'cta_text' => 'required|string',
            'status' => 'required|boolean',
            'ad_type' => 'required|string|in:image,video',
        ]);
        $path = $request->file('creative_file')
            ->store('ads', 'public');
        $data['creative_link'] = $path;
        unset($data['creative_file']);
        Ad::create($data);
        return redirect()
            ->route('admin.ads.index')
            ->with('success', 'Ad created successfully.');
    }
    public function edit($id)
    {
        $ad = Ad::findOrFail($id);

        return view('admin.ads.edit', compact('ad'));
    }
    public function update(Request $request, $id)
    {
        $data = $request->validate([
            'partner_id' => 'required|integer',
            'city_id' => 'required|integer',
            'creative_file' => 'nullable|image|max:2048',
            'ad_link' => 'required|url',
            'ad_title' => 'required|string|max:255',
            'cta_title' => 'required|string|max:255',
            'cta_text' => 'required|string',
            'status' => 'required|boolean',
            'ad_type' => 'required|string|in:image,video',
        ]);
        $ad = Ad::findOrFail($id);
        /*
     * If a new image was uploaded,
     * store it and replace the old creative.
     */
        if ($request->hasFile('creative_file')) {
            $path = $request->file('creative_file')
                ->store('ads', 'public');
            $data['creative_link'] = $path;
        }
        /*
     * creative_file is only the uploaded file.
     * It should not be inserted into the database.
     */
        unset($data['creative_file']);
        $ad->update($data);
        return redirect()
            ->route('admin.ads.index')
            ->with('success', 'Ad updated successfully.');
    }
    public function destroy($id)
    {
        $ad = Ad::findOrFail($id);
        $ad->delete();
        return redirect()
            ->route('admin.ads.index')
            ->with('success', 'Ad deleted successfully.');
    }
}
