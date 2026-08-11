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
            'creative_link' => 'required|image|mimes:jpg,jpeg,png,webp,gif,mp4,mov|max:2048',
            'ad_link' => 'required|url',
            'ad_title' => 'required|string|max:255',
            'cta_title' => 'required|string|max:255',
            'cta_text' => 'required|string',
            'status' => 'required|boolean',
            'ad_type' => 'required|string|in:image,video',
        ]);
        $path = $request->file('creative_link')
            ->store('ads', 'public');
        $data['creative_link'] = $path;
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
            'creative_link' => 'nullable|image|mimes:jpg,jpeg,png,webp,gif,mp4,mov|max:2048',
            'ad_link' => 'required|url',
            'ad_title' => 'required|string|max:255',
            'cta_title' => 'required|string|max:255',
            'cta_text' => 'required|string',
            'status' => 'required|boolean',
            'ad_type' => 'required|string|in:image,video',
        ]);
        $ad = Ad::findOrFail($id);
        if ($request->hasFile('creative_link')) {
            $path = $request->file('creative_link')
                ->store('ads', 'public');
            $data['creative_link'] = $path;
        } else {
            unset($data['creative_link']);
        }
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
