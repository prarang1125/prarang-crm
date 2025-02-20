<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB; // Import the DB facade
use Carbon\Carbon;

class VisitorController extends Controller
{


    public function index()
    {
        $cities = DB::table('visitors')->select('post_city')->distinct('post_city')->pluck('post_city');
        return view('visitors.index', compact('cities'));
    }

    public function showVisitor(Request $request)
    {
        // validate the request
        $request->validate([
            'city' => 'required',
            's' => 'required|date_format:d-m-Y h:i A',
            'e' => 'required|date_format:d-m-Y h:i A',
        ]);

        $startDate = $request->s;
        $endDate = $request->e;
        $city = $request->city;

        $cities = DB::table('visitors')->select('post_city')->distinct('post_city')->pluck('post_city');
        return view('visitors.show', compact('cities', 'city', 'startDate', 'endDate'));
    }

}
