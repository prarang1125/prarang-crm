<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB; // Import the DB facade
use Carbon\Carbon;
class VisitorController extends Controller
{
    public function showVisitor(Request $request)
    {
        // Fetch all visitors from the 'visitors' table
      $cities = DB::table('visitors')->select('city')->distinct('city')->pluck('city');
      $startDate = $request->s;
      $endDate = $request->e;
      $city = $request->city;

      // Default to the current month's start and end dates if s and e are null
      if (!$startDate || !$endDate) {
        $startDate = Carbon::now()->startOfMonth();
        $endDate = Carbon::now()->endOfMonth();
      } else {
          // Convert the provided dates to Y-m-d format
          $startDate = Carbon::createFromFormat('d/m/Y', $startDate)->startOfDay();
          $endDate = Carbon::createFromFormat('d/m/Y', $endDate)->endOfDay();
      }

      // Build the query
      $query = DB::table('visitors')->whereBetween('created_at', [$startDate, $endDate]);

      // Add city condition if provided
      if ($city) {
          $query->where('city', $city);
      }

     $totalHits=$query->count();
     $totalVisit=$query->sum('visit_count');
      // Execute the query
       $visitors = $query->orderByDesc('visit_count')->get();

      // Return the fetched data as a response
      return view('visitors.show',compact('cities','visitors','totalHits','totalVisit'));
    }
}
