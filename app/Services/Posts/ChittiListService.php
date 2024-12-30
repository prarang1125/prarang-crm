<?php

namespace App\Services\Posts;

use Illuminate\Support\Facades\DB;

class ChittiListService
{
    /**
     * Fetch the listings based on various criteria (search, pagination, filters).
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string|null  $status
     * @param  string|null  $filterType
     * @param  \Illuminate\Http\Request  $request
     * @param  string|null  $type
     * @param  string|null  $cityCode
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     *
     * @author Vivek Yadav <dev.vivek16@email.com>#ASG Kali
     * @copyright 2024, Indoeuropeans India Pvt. Ltd. #Prarang
     */
    public function getChittiListings($request, $makerStatus = null, $listingType = null)
    {
        $search = $request->input('search');

        $query = DB::table('chitti as ch')
            ->select('ch.*', 'vg.*', 'vCg.*', DB::raw("CONCAT(user.firstName, ' ', user.lastName) as userName"), 'ch.chittiId as chittiId')
            ->join('vChittiGeography as vCg', 'ch.chittiId', '=', 'vCg.chittiId')
            ->join('vGeography as vg', 'vg.geographycode', '=', 'vCg.Geography')
            ->whereNotNull('Title')
            ->where('Title', '!=', '')
            ->where('finalStatus', '!=', 'deleted');

        if ($makerStatus) {
            $query->where('makerStatus', '=', $makerStatus);

        }
        if ($listingType == 'checker') {
            $query->whereIn('checkerStatus', ['maker_to_checker']);
            $query->whereNotIn('finalStatus', ['approved', 'deleted']);
            $query = $this->users($query, 'ch.makerId');
        } elseif ($listingType == 'uploader') {
            $query->orderBy('ch.finalStatus', 'asc');
            $query->whereIn('uploaderStatus', ['sent_to_uploader', 'approved']);

            $query = $this->users($query, 'ch.checkerId');
        } else {
            $query = $this->users($query, 'ch.makerId');
        }

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('Title', 'LIKE', "%{$search}%")
                    ->orWhere('SubTitle', 'LIKE', "%{$search}%")
                    ->orWhereRaw('LOWER(createDate) LIKE ?', ['%'.mb_strtolower($search, 'UTF-8').'%']);
            });
        }

        return $query->orderByDesc(DB::raw("STR_TO_DATE(dateOfCreation, '%d-%b-%y %H:%i:%s')"))->paginate(30);

    }

    public function getChittiListingsForAnalytics($request, $type = 'maker', $cityCode = null)
    {

        $search = $request->input('search');
        $query = DB::table('chitti as ch')
            ->select('ch.*', 'vg.*', 'vCg.*', 'city.*', 'ch.chittiId as chittiId');
        $query->join('vChittiGeography as vCg', 'ch.chittiId', '=', 'vCg.chittiId')
            ->join('vGeography as vg', 'vg.geographycode', '=', 'vCg.Geography')
            ->join('mcity as city', 'city.cityId', '=', 'ch.areaId')
            ->where('vCg.Geography', $cityCode)
            ->where('finalStatus', 'approved');
        if ($type === 'checker') {
            $query->leftJoin('muser as user', 'user.userId', '=', 'ch.analyticsMaker')
                ->addSelect(DB::raw("CONCAT(user.firstName, ' ', user.lastName) as userName"))
                ->whereIn('postStatusMakerChecker', ['send_to_post_checker', 'approved']);
        } else {
            $query->where('postStatusMakerChecker', '!=', 'send_to_post_checker');
        }

        $query->where('finalStatus', '!=', 'deleted')
            ->when($search, function ($query, $search) {
                return $query->where(function ($q) use ($search) {
                    $q->where('ch.Title', 'like', "%{$search}%")
                        ->orWhere('ch.SubTitle', 'like', "%{$search}%");
                });
            });
        $query->orderByDesc(DB::raw("STR_TO_DATE(dateOfCreation, '%d-%b-%y %H:%i:%s')"));

        return $query->paginate(31)->appends([
            'cityCode' => $cityCode,
            'search' => $search,
        ]);

    }

    private function users($query, $field)
    {
        return $query->leftJoin('muser as user', 'user.userId', '=', $field);
    }
}
