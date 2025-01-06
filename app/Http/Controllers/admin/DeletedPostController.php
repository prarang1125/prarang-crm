<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Makerlebal;
use App\Models\Chitti;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DeletedPostController extends Controller
{
    #this method is use for show the listing of maker
    // public function index()
    // {
        //     $chittis = Chitti::with(['geographyMappings.region', 'geographyMappings.city', 'geographyMappings.country'])
        //     ->whereNotNull('Title')
        //     ->where('Title', '!=', '')
        //     ->where('finalStatus', '=', 'deleted')
        //     ->select('chittiId', 'Title', 'dateOfCreation', 'finalStatus', 'makerStatus', 'checkerStatus')
        //     ->get();
        //     $geographyOptions = Makerlebal::whereIn('id', [5, 6, 7])->get();
        //     return view('admin.deleted-post.deleted-post-listing', compact('chittis', 'geographyOptions'));
    // }

    public function index(Request $request)
    {
        $search = $request->input('search');

        // $chittis = Chitti::with(['geographyMappings.region', 'geographyMappings.city', 'geographyMappings.country'])
        $chittis = DB::table('chitti as ch')
            ->select('ch.*', 'vg.*', 'vCg.*', 'ch.chittiId as chittiId')
            ->join('vChittiGeography as vCg', 'ch.chittiId', '=', 'vCg.chittiId')
            ->join('vGeography as vg', 'vg.geographycode', '=', 'vCg.Geography')
            ->where('ch.finalStatus', '=', 'deleted')
            ->when($search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('ch.Title', 'LIKE', "%{$search}%")
                        ->orWhere('ch.SubTitle', 'LIKE', "%{$search}%"); // Assuming SubTitle might store another language
                });
            })
            ->orderByDesc(DB::raw("STR_TO_DATE(dateOfApprove, '%d-%b-%y %H:%i:%s')"))
            // ->select('chittiId', 'Title', 'dateOfCreation', 'finalStatus')
            ->paginate(30);

        $geographyOptions = Makerlebal::whereIn('id', [5, 6, 7])->get();
        return view('admin.deleted-post.deleted-post-listing', compact('chittis', 'geographyOptions'));
    }

    function deletedPostToChecker($chittiId)
    {
        $currentDateTime = getUserCurrentTime();
        $chitti = Chitti::findOrFail($chittiId);
        $date = Carbon::now()->format('Y-m-d');
        $dateofcreation = Carbon::now()->format('d-M-y H:i:s');
        $chitti->update([
            'makerStatus'   => 'sent_to_checker',
            'checkerStatus' => 'maker_to_checker',
            'uploaderStatus'=>'',
            'updated_at'    => $currentDateTime,
            'updated_by'    => Auth::guard('admin')->user()->userId,
            'return_chitti_post_from_checker_id' => 0,
            'returnDateToChecker' => $dateofcreation,
            'makerId'       => Auth::guard('admin')->user()->userId,
            'finalStatus'   => '',
        ]);
        return redirect()->route('admin.deleted-post-listing')->with('success','Post sent to checker.');
    }
}
