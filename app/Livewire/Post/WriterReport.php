<?php

namespace App\Livewire\Post;

use App\Models\Muser;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class WriterReport extends Component
{


    public $writers = [];
    public $writerId = null;
    public $startDate = null;
    public $endDate = null;

    public function mount()
    {
        $this->startDate = Carbon::now()->startOfMonth()->format('d-m-Y h:i A');
        $this->endDate = Carbon::now()->endOfMonth()->format('d-m-Y h:i A');

        $this->writers = Muser::where('roleId', 2)
            ->join('chitti', 'chitti.makerId', '=', 'muser.userId')
            ->selectRaw('muser.userId, count(chitti.chittiId) as count')
            ->whereBetween(
                DB::raw("STR_TO_DATE(chitti.dateOfApprove, '%d-%m-%Y %h:%i %p')"),
                [
                    Carbon::parse($this->startDate)->format('Y-m-d H:i:s'),
                    Carbon::parse($this->endDate)->format('Y-m-d H:i:s'),
                ]
            )
            ->groupBy('muser.userId')
            ->get()
            ->pluck('count', 'userId')
            ->toArray();


    }


    public function render()
    {
        return view('livewire.post.writer-report')->layout('components.layouts.admin.base');
    }
}
