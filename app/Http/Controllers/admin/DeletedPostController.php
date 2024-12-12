<?php
namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Makerlebal;
use App\Models\Chitti;

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

        $chittis = Chitti::with(['geographyMappings.region', 'geographyMappings.city', 'geographyMappings.country'])
            ->where('finalStatus', '=', 'deleted')
            ->when($search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('Title', 'LIKE', "%{$search}%")
                    ->orWhere('SubTitle', 'LIKE', "%{$search}%"); // Assuming SubTitle might store another language
                });
            })
            ->select('chittiId', 'Title', 'dateOfCreation', 'finalStatus')
            ->paginate(10); // Adjust the number of items per page as needed

        $geographyOptions = Makerlebal::whereIn('id', [5, 6, 7])->get();

        return view('admin.deleted-post.deleted-post-listing', compact('chittis', 'geographyOptions'));
    }

}
