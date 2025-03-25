<?php

namespace App\Livewire\Marketing;

use App\Models\Subscriber;
use Livewire\Component;
use Livewire\WithPagination;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class SubscriberList extends Component
{
    use WithPagination;

    public $date,$cities;
    public $subscriberCounts;

    public function mount($date = null)
    {

        $this->date = request()->date;
        $this->fetchSubscriberCounts();
        $this->cities = DB::connection('yp')->table('cities')->select('id','name')->distinct('city')->pluck('name','id');

    }

    public function fetchSubscriberCounts()
    {
        $query = Subscriber::whereIn('role', [2, 4]);

        if ($this->date) {
            $query->whereDate('created_at', Carbon::parse($this->date)->format('Y-m-d'));
        }

        $this->subscriberCounts = $query
            ->selectRaw('role, count(*) as count')
            ->groupBy('role')
            ->get();
    }

    // Automatically triggers when $date changes
    public function updatedDate()
    {
        $this->fetchSubscriberCounts();
        $this->resetPage();
    }

    public function render()
    {
        $subscribers = Subscriber::whereIn('role', [2, 4])
            ->when($this->date, function ($query) {
                return $query->whereDate('created_at', Carbon::parse($this->date)->format('Y-m-d'));
            })
            ->orderBy('created_at', 'desc')
            ->paginate(50);

        return view('livewire.marketing.subscriber-list', compact('subscribers'))
            ->layout('layouts.admin.admin');
    }
}
