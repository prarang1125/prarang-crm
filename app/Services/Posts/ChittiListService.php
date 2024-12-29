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
            ->where('finalStatus', '!=', 'deleted')
            ->orderByDesc(DB::raw("STR_TO_DATE(dateOfCreation, '%d-%b-%y %H:%i:%s')"));

        if ($makerStatus) {
            $query->where('makerStatus', '=', $makerStatus);

        }
        if ($listingType == 'checker') {
            $query->whereIn('checkerStatus', ['maker_to_checker']);
            $query->whereNotIn('finalStatus', ['approved', 'deleted']);
            $query = $this->users($query, 'ch.makerId');
        } elseif ($listingType == 'uploader') {
            $query->whereIn('uploaderStatus', ['sent_to_uploader', 'approved']);
            // $query->whereNotIn('finalStatus', ['approved', 'deleted']);
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

        $chittis = $query->paginate(30);

        return $chittis;
    }

    private function users($query, $field)
    {
        return $query->leftJoin('muser as user', 'user.userId', '=', $field);
    }
}
