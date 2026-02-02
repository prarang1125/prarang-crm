<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Muser;
use App\Models\OurTeam;
use App\Services\ImageUploadService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class OurTeamController extends Controller
{
    public function index()
    {
        $teams = OurTeam::all();

        return view('admin.our_team.index', compact('teams'));
    }

    public function create()
    {
        $users = Muser::all();

        return view('admin.our_team.create', compact('users'));
    }

    public function store(Request $request, ImageUploadService $imageUploadService)
    {
        $request->validate([
            'profile_image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'display_name' => 'required|string|max:255',
            'role' => 'required|string|max:255',
            'linkedin_link' => 'nullable|url',
        ]);

        $data = $request->all();

        if ($request->hasFile('profile_image')) {
            $data['profile_image'] = $imageUploadService->uploadImage($request->file('profile_image'), 'team', 'out-team')['path'];
        }

        OurTeam::create($data);

        return redirect()->route('our-team.index')->with('success', 'Team member added successfully!');
    }

    public function edit($id)
    {
        $team = OurTeam::findOrFail($id);
        $users = Muser::all();

        return view('admin.our_team.edit', compact('team', 'users'));
    }

    public function update(Request $request, $id, ImageUploadService $imageUploadService)
    {
        $team = OurTeam::findOrFail($id);

        $request->validate([
            'profile_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'display_name' => 'required|string|max:255',
            'role' => 'required|string|max:255',
            'linkedin_link' => 'nullable|url',
        ]);

        $data = $request->all();

        if ($request->hasFile('profile_image')) {
            $data['profile_image'] = $imageUploadService->uploadImage($request->file('profile_image'), 'team', 'out-team')['path'];
        }

        $team->update($data);

        return redirect()->route('our-team.index')->with('success', 'Team member updated successfully!');
    }

    public function destroy($id)
    {
        $team = OurTeam::findOrFail($id);
        if ($team->profile_image && Storage::exists($team->profile_image)) {
            Storage::delete($team->profile_image);
        }
        $team->delete();

        return redirect()->route('our-team.index')->with('success', 'Team member deleted successfully!');
    }

    public function getAllTeamsJson()
    {
        $teams = OurTeam::orderBy('list_order', 'asc')->get();

        return response()->json([
            'status' => 'success',
            'data' => $teams,
        ]);
    }
}
