<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;

class AccountsController extends Controller
{

    /*public function index()
    {
        $user = Auth::user();
        if ($user->roleId == "2") {
            return redirect()->route('accounts.maker-dashboard');
        } elseif ($user->roleId == "3") {
            return redirect()->route('accounts.checker-dashboard');
        } elseif ($user->roleId == "4") {
            return redirect()->route('accounts.uploader-dashboard');
        } elseif ($user->roleId == "6") {
            return redirect()->route('accounts.analyticsmaker-dashboard');
        } elseif ($user->roleId == "7") {
            return redirect()->route('accounts.analyticschecker-dashboard');
        } else {
            return redirect()->route('accounts.login');
        }
    }*/

    public function index()
    {
        $user = Auth::user();
        switch ($user->roleId) {
            case "2":
                return redirect()->route('accounts.maker-dashboard');
            case "3":
                return redirect()->route('accounts.checker-dashboard');
            case "4":
                return redirect()->route('accounts.uploader-dashboard');
            case "6":
                return redirect()->route('accounts.analyticsmaker-dashboard');
            case "7":
                return redirect()->route('accounts.analyticschecker-dashboard');
            default:
                return redirect()->route('accounts.login')->with('error', 'Unauthorized access');
        }
    }


}
