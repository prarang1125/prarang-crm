<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB; // Import the DB facade
use Carbon\Carbon;
use App\Services\WhatsAppService;

class VisitorController extends Controller
{
    protected $whatsAppService;

    public function __construct(WhatsAppService $whatsAppService)
    {
        $this->whatsAppService = $whatsAppService;
    }

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

    public function sendWhatsAppMessage(Request $request)
    {
        $to = $request->input('to');
        $message = $request->input('message');
        // return $to;
        return $this->whatsAppService->sendMessage($to, $message);

        return response()->json(['status' => 'Message sent successfully']);
    }
}
