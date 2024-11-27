<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Misreport;

class MisReportController extends Controller
{
    public function index()
    {
        $misreports = Misreport::with('userCity')->get();
        return view('admin.misreport.mis-reports', compact('misreports'));
    }

    public function generateMisReport(Request $request)
    {
        $validated = $request->validate([
            'geography' => 'required',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ]);

        dd($request->geography);
        $query = Misreport::query();

        return view('admin.misreport.mis-reports', compact('misreports'));
    }
}
