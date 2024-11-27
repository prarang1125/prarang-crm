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
        $allIds = $misreports->pluck('Id')->implode(',');
        return view('admin.misreport.mis-reports', compact('misreports', 'allIds'));
    }

    public function generateMisReport(Request $request)
    {
        $validated = $request->validate([
            'geography' => 'required',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ]);

        $query = Misreport::query();

        // Handle "All" option
        if ($request->filled('geography')) {
            if (str_contains($request->geography, ',')) {
                $ids = explode(',', $request->geography);
                $query->whereIn('id', $ids);
            } else {
                $query->where('id', $request->geography);
            }
        }

        $query->whereBetween('CreatedDate', [$request->start_date, $request->end_date]);

        $misreports = $query->with('userCity')->get();

        return view('admin.misreport.mis-reports', compact('misreports'));
    }
}
