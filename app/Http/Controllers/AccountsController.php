<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;

class AccountsController extends Controller
{

    public function index()
    {
        $user = Auth::user();
        if ($user->roleId == "2") {
            return redirect()->route('accounts.maker-dashboard');
        } elseif ($user->roleId == "3") {
            return redirect()->route('accounts.checker-dashboard');
        } elseif ($user->roleId == "4") {
            return redirect()->route('accounts.uploader-dashboard');
        } else {
            return redirect()->route('accounts.login');
        }
    }

}
