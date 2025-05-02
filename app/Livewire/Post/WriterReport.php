<?php

namespace App\Livewire\Post;

use App\Models\Chitti;
use App\Models\Muser;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class WriterReport extends Component
{


    public $writers = [];
    public $mUserName = [];
    public $writerId = null;
    public $startDate = null;
    public $endDate = null;
    public $selectedMonth = null;
    public $selectedYear = null;
    public $allWriters = [];
    public $posts = [];

    public function mount()
    {
        $this->allWriters=Muser::whereIn('roleId', [1,2])->get();
        $this->startDate = Carbon::now()->startOfMonth()->format('d-m-Y h:i A');
        $this->endDate = Carbon::now()->endOfMonth()->format('d-m-Y h:i A');

    }


    public function submit(){

        $this->startDate = Carbon::createFromDate($this->selectedYear, $this->selectedMonth, 1)->format('d-m-Y h:i A');
        $this->endDate = Carbon::createFromDate($this->selectedYear, $this->selectedMonth, 1)->endOfMonth()->format('d-m-Y h:i A');
        $this->writers = Muser::where('roleId', 2)
        ->join('chitti', 'chitti.makerId', '=', 'muser.userId')
        ->whereBetween(
            DB::raw("STR_TO_DATE(chitti.dateOfApprove, '%d-%m-%Y %h:%i %p')"),
            [
                Carbon::parse($this->startDate)->format('Y-m-d H:i:s'),
                Carbon::parse($this->endDate)->format('Y-m-d H:i:s'),
            ]
        )
        ->select('muser.userId', DB::raw('COUNT(chitti.chittiId) as count'))
        ->groupBy('muser.userId')
        ->pluck('count', 'userId')
        ->toArray();
        $this->posts = Chitti::where('makerId', $this->writerId)
            ->whereBetween(
                DB::raw("STR_TO_DATE(chitti.dateOfApprove, '%d-%m-%Y %h:%i %p')"),
                [
                    Carbon::parse($this->startDate)->format('Y-m-d H:i:s'),
                    Carbon::parse($this->endDate)->format('Y-m-d H:i:s'),
                ]
            )
            ->join('chittitagmapping as tags', 'chitti.chittiId', '=', 'tags.chittiId')
            ->join('mtag', 'tags.tagId', '=', 'mtag.tagId')
            ->select('chitti.*', 'mtag.tagInEnglish')
            ->get()
            ->toArray();




        $this->mUserName = Muser::select('userId', DB::raw("concat(firstName, ' ', lastName) as name"))
            ->pluck('name', 'userId')
            ->toArray();
    }


    public function render()
    {
        return view('livewire.post.writer-report')->layout('components.layouts.admin.base');
    }
}
