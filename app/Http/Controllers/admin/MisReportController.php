<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Misreport;
use App\Exports\MisReportExport;
use Maatwebsite\Excel\Facades\Excel;

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

        $query = Misreport::query();

        if ($request->geography !== 'All') {
            $query->where('Id', $request->geography);
        }

        $query->whereBetween('CreatedDate', [$request->start_date, $request->end_date]);
        $misreports = $query->get();
        return view('admin.misreport.mis-reports', compact('misreports'));
    }

    public function export(Request $request)
    {
        $validated = $request->validate([
            'geography' => 'required',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ]);

        return Excel::download(
            new MisReportExport($request->start_date, $request->end_date, $request->geography),
            'mis_report.xlsx'
        );
    }
}
