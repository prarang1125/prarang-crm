<?php

namespace App\Http\Controllers\WhatsappApi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class WebHook extends Controller
{
    public function index(Request $request){
        $verify_token = "G4XMetDgfygsA4j1sJYcIWnuFGOg21Iq";
        if ($request->query('hub_verify_token') === $verify_token) {
            return response($request->query('hub_challenge'));
        }
        return response('Unauthorized', 403);

    }
    public function handalWebhook(Request $request){
        $data = $request->all();
        // Log::info('Webhook Data: ', $data);
        // Process the incoming data as needed
        return response()->json(['status' => 'success']);
    }
}
