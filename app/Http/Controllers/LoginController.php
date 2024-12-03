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
        // $user = Auth::Muser();
        $validator = Validator::make($request->all(),[
            'email' => 'required|email',
            'password' => 'required',
            'language' => 'required|in:english,hindi',
        ]);

        // $user = Muser::find(2);

        // echo "<pre>";
        // print_r($user);
        // echo "<pre>";
        // // die();
        // $user->empPassword = bcrypt('usertesting');  // Make sure to use the correct column name
        // $user->save();
        // dd($request->email);
        // dd($request->password);
        if($validator->passes()){

            $credentials = [
                'emailId' => $request->email,
                'password' => $request->password, // Use 'password' key here
            ];
            // dd(Auth::attempt($credentials));
            // if(Auth::attempt($credentials)){
            //     return redirect()->route('accounts.dashboard');
            #strat new code for specific page access maker checker and uploader
            if (Auth::attempt($credentials)) {
                $user = Auth::user(); // Authenticated user
                // dd($user);
                // Role-based redirection
                // dd($user->roleId);
                switch ($user->roleId) {
                    case 2:
                        return redirect()->route('maker.dashboard');
                    case 3:
                        return redirect()->route('checker.dashboard');
                    case 4:
                        return redirect()->route('uploader.dashboard');
                    default:
                        return redirect()->route('accounts.dashboard');
                }
                #end new code for specific page access maker checker and uploader
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
