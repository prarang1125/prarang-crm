<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Ad;
use App\Models\Chitti;
use App\Models\AdChittiMap;
use Illuminate\Http\Request;

class AdChittiMapController extends Controller
{
    public function edit($adId)
    {
        $ad = Ad::findOrFail($adId);
        $mappedChittis = AdChittiMap::where('ad_id', $adId)
            ->where('status', 1)
            ->pluck('chitti_id');
        $chittis = Chitti::whereIn('chittiId', $mappedChittis)
            ->orderBy('chittiId', 'desc')
            ->get();
        return view('admin.ads.chitti-maps', compact(
            'ad',
            'chittis'
        ));
    }
}
