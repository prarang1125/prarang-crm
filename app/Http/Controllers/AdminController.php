<?php
    
namespace App\Http\Controllers;
    
use App\Models\Muser;
    
class AdminController extends Controller
{

    public function index()
    {
        return view('admin.dashboard');
    }

}