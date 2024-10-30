<?php
namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Makerlebal;
use App\Models\Chitti;

class DeletedPostController extends Controller
{
    #this method is use for show the listing of maker
    public function index()
    {
        $chittis = Chitti::with(['geographyMappings.region', 'geographyMappings.city', 'geographyMappings.country'])
        ->whereNotNull('Title')
        ->where('Title', '!=', '')
        ->where('finalStatus', '=', 'deleted')
        ->select('chittiId', 'Title', 'dateOfCreation', 'finalStatus', 'makerStatus', 'checkerStatus')
        ->get();
        $geographyOptions = Makerlebal::whereIn('id', [5, 6, 7])->get();
        return view('admin.deleted-post.deleted-post-listing', compact('chittis', 'geographyOptions'));
    }
}
