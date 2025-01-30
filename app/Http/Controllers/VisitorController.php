<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB; // Import the DB facade
use Carbon\Carbon;

class VisitorController extends Controller
{
    public function showVisitor(Request $request)
    {
        $cities = DB::table('visitors')->select('city')->distinct('city')->pluck('city');
        $startDate = $request->s;
        $endDate = $request->e;
        $city = $request->city;
        $groupBy = $request->group ?? false;

        if (!$startDate || !$endDate) {
            $startDate = Carbon::now()->startOfMonth();
            $endDate = Carbon::now()->endOfMonth();
        }

        $query = DB::table('visitors')
            ->join('chitti', 'chitti.chittiId', '=', 'visitors.post_id')
            ->whereBetween(
                DB::raw("STR_TO_DATE(chitti.dateOfApprove, '%d-%m-%Y %h:%i %p')"),
                [
                    DB::raw("STR_TO_DATE('$startDate', '%d-%m-%Y %h:%i %p')"),
                    DB::raw("STR_TO_DATE('$endDate', '%d-%m-%Y %h:%i %p')")
                ]
            );

        if ($city) {
            $query->where('visitors.city', $city);
        }
        $totalHits = $query->count();
        $totalVisit = $query->sum('visit_count');
        if ($groupBy) {
            $query->select(
                'visitors.post_id',
                'visitors.city',
                'chitti.dateOfApprove',
                'chitti.Title',

                DB::raw('COUNT(visitors.id) as record_count'),
                DB::raw('SUM(visitors.visit_count) as total_visits')
            )->groupBy('visitors.post_id', 'chitti.Title','chitti.dateOfApprove','visitors.city');
        } else {
            $query->select('chitti.dateOfApprove', 'chitti.Title', 'visitors.*');
        }


        $visitors = $query->orderByDesc('visit_count')->paginate(30);

        return view('visitors.show', compact('cities', 'visitors', 'totalHits', 'totalVisit', 'city', 'startDate', 'endDate'));
    }

}
