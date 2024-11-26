<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;

class MisReportController extends Controller
{
    public function index()
    {
        return view('admin.misreport.mis-reports');
    }
}
