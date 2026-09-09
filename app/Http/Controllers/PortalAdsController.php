<?php

namespace App\Http\Controllers;

use App\Services\ImageUploadService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class PortalAdsController extends Controller
{
    private $fileFields = ['ad_logo', 'interaction_ad', 'non_interaction_ad', 'square_ad', 'vertical_ad'];

    protected $imageUploadService;

    public function __construct(ImageUploadService $imageUploadService)
    {
        $this->imageUploadService = $imageUploadService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $ads = DB::table('portal_ads')
            ->join('portals', 'portals.id', '=', 'portal_ads.portal_id')
            ->select('portal_ads.*', 'portals.city_name as portal_name')
            ->whereNull('portal_ads.deleted_at')
            ->orderBy('portal_ads.id', 'desc')
            ->get();

        return view('portal.ads.index', compact('ads'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $portals = DB::table('portals')->get();
        return view('portal.ads.create', compact('portals'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'portal_id' => 'required|exists:portals,id',
            'title' => 'required|string|max:255',
            'ad_logo' => 'nullable|image|max:2048',
            'interaction_ad' => 'nullable|image|max:2048',
            'non_interaction_ad' => 'nullable|image|max:2048',
            'square_ad' => 'nullable|image|max:2048',
            'vertical_ad' => 'nullable|image|max:2048',
            'interaction_url' => 'nullable|url|max:300',
            'non_interaction_url' => 'nullable|url|max:300',
            'status' => 'required|boolean',
        ]);

        $data = $request->only([
            'portal_id',
            'title',
            'interaction_url',
            'non_interaction_url',
            'status',
        ]);

        foreach ($this->fileFields as $field) {
            if ($request->hasFile($field)) {
                $result = $this->imageUploadService->uploadImage($request->file($field), $field, 'portal-ads');

                if (!empty($result['error'])) {
                    return back()->withInput()->withErrors([$field => $result['message']]);
                }

                $data[$field] = $result['full_url'];
            }
        }

        $data['created_at'] = now();
        $data['updated_at'] = now();

        DB::table('portal_ads')->insert($data);

        return redirect('admin/portal-ads')->with('success', 'Portal ad created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $ad = DB::table('portal_ads')
            ->join('portals', 'portals.id', '=', 'portal_ads.portal_id')
            ->select('portal_ads.*', 'portals.city_name as portal_name')
            ->where('portal_ads.id', $id)
            ->first();

        if (!$ad) {
            return redirect('admin/portal-ads')->with('error', 'Portal ad not found.');
        }

        return view('portal.ads.show', compact('ad'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $ad = DB::table('portal_ads')->where('id', $id)->first();

        if (!$ad) {
            return redirect('admin/portal-ads')->with('error', 'Portal ad not found.');
        }

        $portals = DB::table('portals')->get();

        return view('portal.ads.edit', compact('ad', 'portals'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $ad = DB::table('portal_ads')->where('id', $id)->first();

        if (!$ad) {
            return redirect('admin/portal-ads')->with('error', 'Portal ad not found.');
        }

        $request->validate([
            'portal_id' => 'required|exists:portals,id',
            'title' => 'required|string|max:255',
            'ad_logo' => 'nullable|image|max:2048',
            'interaction_ad' => 'nullable|image|max:2048',
            'non_interaction_ad' => 'nullable|image|max:2048',
            'square_ad' => 'nullable|image|max:2048',
            'vertical_ad' => 'nullable|image|max:2048',
            'interaction_url' => 'nullable|url|max:300',
            'non_interaction_url' => 'nullable|url|max:300',
            'status' => 'required|boolean',
        ]);

        $data = $request->only([
            'portal_id',
            'title',
            'interaction_url',
            'non_interaction_url',
            'status',
        ]);

        foreach ($this->fileFields as $field) {
            if ($request->hasFile($field)) {
                $result = $this->imageUploadService->uploadImage($request->file($field), $field, 'portal-ads');

                if (!empty($result['error'])) {
                    return back()->withInput()->withErrors([$field => $result['message']]);
                }

                // delete old file from s3 before saving new url
                if (!empty($ad->$field)) {
                    $oldPath = str_replace(Storage::disk('s3')->url(''), '', $ad->$field);
                    Storage::disk('s3')->delete($oldPath);
                }

                $data[$field] = $result['full_url'];
            }
        }

        $data['updated_at'] = now();

        DB::table('portal_ads')->where('id', $id)->update($data);

        return redirect('admin/portal-ads')->with('success', 'Portal ad updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $ad = DB::table('portal_ads')->where('id', $id)->first();

        if (!$ad) {
            return redirect('admin/portal-ads')->with('error', 'Portal ad not found.');
        }

        foreach ($this->fileFields as $field) {
            if (!empty($ad->$field)) {
                $oldPath = str_replace(Storage::disk('s3')->url(''), '', $ad->$field);
                Storage::disk('s3')->delete($oldPath);
            }
        }

        DB::table('portal_ads')->where('id', $id)->update([
            'deleted_at' => now(),
        ]);

        return redirect('admin/portal-ads')->with('success', 'Portal ad deleted successfully.');
    }
}
