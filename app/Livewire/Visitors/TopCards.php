<?php

namespace App\Livewire\Visitors;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;

class TopCards extends Component
{
    use WithPagination;
    public $city, $startDate, $endDate, $reffData, $smd, $emd, $postTitle;
    public $postStartDate, $postEndDate, $postReffData, $chartdate, $userType, $scroll, $get5th31th;
    protected $paginationTheme = 'bootstrap';
    public function mount(string $city, string $startDate, string $endDate): void
    {
        $this->city = $city;
        $this->startDate = $this->postStartDate = $startDate;
        $this->endDate = $this->postEndDate = $endDate;
        $this->postStartDate = Carbon::now()->startOfMonth()->format('d-m-Y H:i:s');
        $this->postEndDate = Carbon::now()->endOfMonth()->format('d-m-Y H:i:s');

        $this->reffData = $this->getDataBasedOnRefference();
        $this->postReffData = $this->reffData;
        $this->userType = $this->getDataBasedOnBots();
        $this->scroll = $this->getScrollDuration();

        // $this->dispatch('updateChart', $this->reffData);
    }


    public function render()
    {
        $visitorDats = $this->getPostWiseData();
        $visitors = $visitorDats['visitors'];
        $totalHits = $visitorDats['totalHits'];
        $totalVisit = $visitorDats['totalVisit'];
        return view('livewire.visitors.top-cards', compact('visitors'));
    }
    public function applyFilters()
    {
        $this->dispatch('hideDateChangeModal');
        $this->postTitle = '';

        //  $this->dispatch('updateChart', $this->reffData);
        $this->reffData = $this->getDataBasedOnRefference();
        $this->reffData = $this->getDataBasedOnRefference();
        $this->postReffData = $this->reffData;
        $this->userType = $this->getDataBasedOnBots();
        $this->scroll = $this->getScrollDuration();
        $this->resetPage();
    }
    public function getPostAnalytics($postId, $title)

    {

        $this->postTitle = $title;
        $this->postReffData = $this->getDataBasedOnRefference($postId);
        $this->userType = $this->getDataBasedOnBots($postId);
        $this->scroll = $this->getScrollDuration($postId);
        $this->get5th31th = $this->day5th31th($postId);
    }

    public function changePostData()
    {
        $this->resetPage();
    }

    private function getDataBasedOnRefference($post_id = null)
    {
        $startDate = Carbon::parse($this->startDate)->format('Y-m-d H:i:s');
        $endDate = Carbon::parse($this->endDate)->format('Y-m-d H:i:s');
        $city = $this->city;

        $groupedData = DB::table('visitors')
            ->select(DB::raw('
            CASE
                WHEN referrer IN ("facebook", "google", "prarang") THEN referrer
                ELSE "others"
            END as referrer,
            SUM(visit_count) as total_visits,
            COUNT(*) as total_hits
        '))
            ->whereBetween('created_at', [$startDate, $endDate])
            ->where('post_city', $city)
            ->when($post_id, function ($query, $post_id) {
                return $query->where('post_id', $post_id);
            })
            ->groupBy(DB::raw('
            CASE
                WHEN referrer IN ("facebook", "google", "prarang") THEN referrer
                ELSE "others"
            END
        '))
            ->get();

        // Convert to required format
        $formattedData = [];
        foreach ($groupedData as $row) {
            $formattedData[$row->referrer] = [$row->total_visits ?? 0, $row->total_hits ?? 0];
        }

        return $formattedData;
    }


    private function getScrollDuration($post_id = null)
    {
        $startDate = Carbon::parse($this->startDate)->format('Y-m-d H:i:s');
        $endDate = Carbon::parse($this->endDate)->format('Y-m-d H:i:s');
        $city = $this->city;
        $query = DB::table('visitors')
            ->select(
                DB::raw('AVG(scroll) as scroll'),
                DB::raw('SUM(duration)/60 as duration'),
                DB::raw('SUM(CASE WHEN screen_width < 768 THEN visit_count ELSE 0 END) as mobile'),
                DB::raw('SUM(CASE WHEN screen_width BETWEEN 768 AND 1024 THEN visit_count ELSE 0 END) as tablet'),
                DB::raw('SUM(CASE WHEN screen_width > 1024 THEN visit_count ELSE 0 END) as desktop')
            )
            ->whereBetween('created_at', [$startDate, $endDate])
            ->where('post_city', $city)
            ->when($post_id, function ($query, $post_id) {
                return $query->where('post_id', $post_id);
            })
            ->first();

        return $query;
    }

    private function getDataBasedOnBots($post_id = null)
    {
        $startDate = Carbon::parse($this->startDate)->format('Y-m-d H:i:s');
        $endDate = Carbon::parse($this->endDate)->format('Y-m-d H:i:s');
        $city = $this->city;

        $groupedData = DB::table('visitors')
            ->select(DB::raw('
            CASE
                WHEN user_type IN ("facebook_bot", "google_bot","bing_bot", "user") THEN user_type
                ELSE "others"
            END as user_type,
            COUNT(*) as bot_count
        '))
            ->whereBetween('created_at', [$startDate, $endDate])
            ->where('post_city', $city)
            ->when($post_id, function ($query, $post_id) {
                return $query->where('post_id', $post_id);
            })
            ->groupBy(DB::raw('
            CASE
                WHEN user_type IN ("facebook_bot", "google_bot","bing_bot", "user") THEN user_type
                ELSE "others"
            END
        '))
            ->pluck('bot_count', 'user_type');
        return $groupedData;
    }


    private function getPostWiseData()
    {
        try {
            $startDate = Carbon::parse($this->startDate)->format('Y-m-d H:i:s');
            $endDate = Carbon::parse($this->endDate)->format('Y-m-d H:i:s');
            $postStartDate = Carbon::parse($this->postStartDate)->format('Y-m-d H:i:s');
            $postEndDate = Carbon::parse($this->postEndDate)->format('Y-m-d H:i:s');
        } catch (\Exception $e) {
            return ['error' => 'Invalid date format'];
        }
        $city = $this->city;
        $query = DB::table('chitti')
            ->leftJoin('visitors', 'chitti.chittiId', '=', 'visitors.post_id')
            ->whereRaw("STR_TO_DATE(chitti.dateOfApprove, '%d-%m-%Y %h:%i %p') BETWEEN ? AND ?", [$postStartDate, $postEndDate])
            ->where(function ($query) use ($city,$startDate,$endDate) {
                $query->where('visitors.post_city', $city)
                ->whereBetween('visitors.created_at', [$startDate, $endDate])
                    ->orWhereNull('visitors.post_city');
            });

        $totalHits = (clone $query)->count();
        $totalVisit = (clone $query)->sum('visit_count');
        $visitors =  $query->select(
            'visitors.post_id',
            'visitors.post_city',
            'chitti.dateOfApprove',
            'chitti.Title',
            DB::raw('COUNT(visitors.id) as record_count'),
            DB::raw('SUM(visitors.visit_count) as visit_count')
        )
            ->where('chitti.finalStatus', '=', 'approved')
            ->groupBy('visitors.post_id', 'chitti.Title', 'chitti.dateOfApprove', 'visitors.post_city')
            ->orderBy(DB::raw("STR_TO_DATE(chitti.dateOfApprove, '%d-%m-%Y %h:%i %p')"))
            ->paginate(52);

        return [
            'visitors' => $visitors,
            'totalHits' => $totalHits,
            'totalVisit' => $totalVisit
        ];
    }


    private function day5th31th($post_id)
    {
        if (is_null($post_id)) {
            return (object)[
                'view_5th' => 0,
                'click_5th' => 0,
                'view_31st' => 0,
                'click_31st' => 0
            ];
        }
        $query = DB::table('visitors')
            ->join('chitti', 'chitti.chittiId', '=', 'visitors.post_id')
            ->where('visitors.post_id', $post_id)
            ->select(

                DB::raw("SUM(CASE WHEN visitors.created_at BETWEEN STR_TO_DATE(chitti.dateOfApprove, '%d-%m-%Y %h:%i %p')
                    AND DATE_ADD(STR_TO_DATE(chitti.dateOfApprove, '%d-%m-%Y %h:%i %p'), INTERVAL 5 DAY) THEN visit_count ELSE 0 END) as view_5th"),

                DB::raw("COUNT(CASE WHEN visitors.created_at BETWEEN STR_TO_DATE(chitti.dateOfApprove, '%d-%m-%Y %h:%i %p')
                    AND DATE_ADD(STR_TO_DATE(chitti.dateOfApprove, '%d-%m-%Y %h:%i %p'), INTERVAL 5 DAY) THEN 1 ELSE NULL END) as click_5th"),

                DB::raw("SUM(CASE WHEN visitors.created_at BETWEEN STR_TO_DATE(chitti.dateOfApprove, '%d-%m-%Y %h:%i %p')
                    AND DATE_ADD(STR_TO_DATE(chitti.dateOfApprove, '%d-%m-%Y %h:%i %p'), INTERVAL 31 DAY) THEN visit_count ELSE 0 END) as view_31st"),

                DB::raw("COUNT(CASE WHEN visitors.created_at BETWEEN STR_TO_DATE(chitti.dateOfApprove, '%d-%m-%Y %h:%i %p')
                    AND DATE_ADD(STR_TO_DATE(chitti.dateOfApprove, '%d-%m-%Y %h:%i %p'), INTERVAL 31 DAY) THEN 1 ELSE NULL END) as click_31st")
            )
            ->first();

        if (is_null($query)) {
            return (object)[
                'view_5th' => 0,
                'click_5th' => 0,
                'view_31st' => 0,
                'click_31st' => 0
            ];
        }

        return $query;
    }
}
