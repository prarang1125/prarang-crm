<?php

namespace App\Http\Controllers;

class AccountsController extends Controller
{

    public function index()
    {
        return view('accounts.dashboard');
    }

}
