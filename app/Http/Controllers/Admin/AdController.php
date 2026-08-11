<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Ad;
use App\Models\Mcity;


class AdController extends Controller
{
    public function index()
    {
        $data = Ad::all();
        return view('admin.ads.index', compact('data'));
    }
    public function create()
    {
        $cities = Mcity::all();
        return view('admin.ads.create', compact('cities'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'partner_id' => 'required|integer',
            'city_id' => 'required|integer|exists:mcity,cityId',
            'creative_link' => 'required|url',
            'ad_link' => 'required|url',
            'ad_title' => 'required|string|max:255',
            'cta_title' => 'required|string|max:255',
            'cta_text' => 'required|string',
            'status' => 'required|boolean',
            'ad_type' => 'required|string|in:image,video',
        ]);
        Ad::create($data);
        return redirect()
            ->route('admin.ads.index')
            ->with('success', 'Ad created successfully.');
    }
    public function edit($id)
    {
        $ad = Ad::findOrFail($id);
        $cities = Mcity::all();
        return view('admin.ads.edit', compact('ad', 'cities'));
    }
    public function update(Request $request, $id)
    {
        $data = $request->validate([
            'partner_id' => 'required|integer',
            'city_id' => 'required|integer|exists:mcity,cityId',
            'creative_link' => 'required|url',
            'ad_link' => 'required|url',
            'ad_title' => 'required|string|max:255',
            'cta_title' => 'required|string|max:255',
            'cta_text' => 'required|string',
            'status' => 'required|boolean',
            'ad_type' => 'required|string|in:image,video',
        ]);

        $ad = Ad::findOrFail($id);
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
