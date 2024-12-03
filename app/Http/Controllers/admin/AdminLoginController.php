<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use App\Models\Muser;

class AdminLoginController extends Controller
{

    public function index()
    {
        return view('admin.login');
    }

    // This method will authenticate admin
    public function authenticate(Request $request)
    {
        // $user = Auth::muser();
        $validator = Validator::make($request->all(),[
            'email' => 'required|email',
            'password' => 'required',
            'language' => 'required|in:english,hindi',
        ]);

        $user = Muser::find(1);

        // echo "<pre>";
        // print_r($user);
        // echo "<pre>";
        // // die();
        // $user->empPassword = bcrypt('test');  // Make sure to use the correct column name
        // $user->save();

        if($validator->passes()){

            $credentials = [
                'emailId' => $request->email,
                'password' => $request->password, // Use 'password' key here
            ];

            if(Auth::guard('admin')->attempt($credentials)){
                $user = Auth::guard('admin')->user();
                if($user->roleId != "1"){
                    Auth::guard('admin')->logout();
                    return redirect()->route('admin.login')->with('error', 'You are not authorized to acces this message');
                }
                return redirect()->route('admin.dashboard');
            }else{
                return redirect()->route('admin.login')->with('error', 'Either mail or password is incorrect');
            }
        }else{
            return redirect()->route('admin.login')
                ->withInput()
                ->withErrors($validator);
        }
    }

    public function logout() {
        Auth::guard('admin')->logout();
        return redirect()->route('admin.login');
    }

}
