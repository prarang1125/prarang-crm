<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use App\Models\Muser;

use function Laravel\Prompts\error;

class LoginController extends Controller
{
    // This method will show login page
    public function index()
    {
        return view('accounts.login');
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

        $user = Muser::find(2);

        // echo "<pre>";
        // print_r($user);
        // echo "<pre>";
        // // die();
        // $user->empPassword = bcrypt('usertesting');  // Make sure to use the correct column name
        // $user->save();

        if($validator->passes()){

            $credentials = [
                'emailId' => $request->email,
                'password' => $request->password, // Use 'password' key here
            ];

            if(Auth::attempt($credentials)){
                return redirect()->route('accounts.dashboard');
            }else{
                return redirect()->route('accounts.login')->with('error', 'Either mail or password is incorrect');
            }
        }else{
            return redirect()->route('accounts.login')
                ->withInput()
                ->withErrors($validator);
        }
    }

    public function logout() {
        Auth::logout();
        return redirect()->route('accounts.login');
    }
}
