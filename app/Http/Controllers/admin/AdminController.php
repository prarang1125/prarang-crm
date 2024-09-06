<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Validate;
use App\Models\Mrole;
use App\Models\Mlanguagescript;
use App\Models\Muser;
use Illuminate\Validation\Rule;


class AdminController extends Controller
{
    #this method is use for show the admin dashboard pages;
    public function index()
    {
        return view('admin.dashboard');
    }

    #this method is use for show admin user profile data
    public function userProfile(){

        $user     = Auth::guard('admin')->user();
        $roleName = $user->role ? $user->role->roleName : 'No Role Assigned';
        $language = $user->languageScript ? $user->languageScript->language : 'No Language Assigned';
        return view('admin.user-profile', compact('user', 'roleName', 'language'));
    }

    #this method is use for update admin user profile
    // public function updateProfile(Request $request)
        // {
        //     $user = Auth::guard('admin')->user();

        //     $user->firstName = explode(' ', $request->input('fullName'))[0];
        //     $user->lastName = explode(' ', $request->input('fullName'))[1];
        //     $user->emailId = $request->input('emailId');
        //     $user->languageId = $request->input('languageId');
        //     $user->save();

        //     return response()->json(['success' => true]);
    // }

    #this method is use for show user listing data
    public function userListing(){
        $users = Muser::with('role')->get();
        return view('admin.user-listing', compact('users'));
    }

    #this method is use for create/register new user form page
    public function useruRegister(){
        $roles = Mrole::all();
        $languagescripts = Mlanguagescript::all();
        return view('admin.user-register', compact('roles', 'languagescripts'));
    }

    #this method is use for store/save data in db
    public function userStore(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'firstName' => 'required|string|max:255',
            'lastName' => 'required|string|max:255',
            'emailId' => 'required|email|unique:muser,emailId',
            'empPassword' => 'required|string|min:5',
            'roleId' => 'required|exists:mrole,roleID',
            'languageScriptId' => 'required|exists:mlanguagescript,id',
        ]);

        if($validator->passes()){
            Muser::create([
                'firstName' => $request->firstName,
                'lastName' => $request->lastName,
                'emailId' => $request->emailId,
                'empPassword' => bcrypt($request->empPassword),
                'roleId' => $request->roleId,
                'languageId' => $request->languageScriptId,
                'created_at' => now(),
                'created_by' => Auth::guard('admin')->user()->userId,
                'isActive' => 1
            ]);
            return redirect()->route('admin.user-listing');
        }else{
            return redirect()->route('admin.user-register')
                ->withInput()
                ->withErrors($validator);
        }
    }

    #this method is use for delete specific user id
    public function userDelete($id)
    {
        try {
            $user = Muser::findOrFail($id);
            $user->isActive = 0;
            $user->updated_at = now();
            $user->updated_by = Auth::guard('admin')->user()->userId;
            $user->save();

            return redirect()->route('admin.user-listing')->with('success', 'User soft deleted successfully.');
        } catch (\Exception $e) {
            return redirect()->route('admin.user-listing')->with('error', 'There was an error deleting the user: ' . $e->getMessage());
        }
    }

    #this method is use for show the existing data in field and also we change it.
    public function userEdit($id){
        $user = Muser::findOrFail($id);
        $languagescripts = Mlanguagescript::all();
        $roles = Mrole::all();

        return view('admin.user-edit', compact('user', 'roles', 'languagescripts'));
    }

    #this method is use for update the user data
    public function userUpdate(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'firstName' => 'required|string|max:255',
            'lastName' => 'required|string|max:255',
            'emailId' => [
                'required',
                'email',
                Rule::unique('muser', 'emailId')->ignore($id, 'userId'),
            ],
            'empPassword' => 'required|string|min:5',
            'roleId' => 'required|exists:mrole,roleID',
            'languageId' => 'required|boolean',
            'isActive' => 'required|boolean',
        ]);

        if ($validator->passes()) {
            $user = Muser::findOrFail($id);

            // Update the user with additional fields
            $user->update([
                'firstName' => $request->firstName,
                'lastName' => $request->lastName,
                'emailId' => $request->emailId,
                'empPassword' => $request->empPassword,
                'roleId' => $request->roleId,
                'languageId' => $request->languageId,
                'isActive' => $request->isActive,
                'updated_at' => now(),
                'updated_by' => Auth::guard('admin')->user()->userId,
            ]);

            return redirect()->route('admin.user-listing')->with('success', 'User updated successfully.');
        } else {
            return redirect()->back()
                ->withInput()
                ->withErrors($validator);
        }
    }
}
