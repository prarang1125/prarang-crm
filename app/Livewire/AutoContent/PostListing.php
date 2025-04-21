<?php

namespace App\Livewire\AutoContent;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;


class PostListing extends Component
{
    use WithPagination;

    // public $posts = [];
    public $tags = [], $cities = [];
    public $city, $startDate, $endDate, $tag, $comparator, $value;
    public $profession, $education, $professionArr, $educationArr, $emotion, $emotionArr;
    public $selectedTags = [], $selectAllTags = false;
    public $selectedProfessions = [];
    public $selectedEducations = [];
    public $selectedEmotions = [];
    public $loadTimeInSeconds = null;
    public $submitted = false;


    protected $rules = [
        'city' => 'required',
        'startDate' => 'required|date',
        'endDate' => 'required|date|after_or_equal:startDate',
    ];
    protected $messages = [
        'city.required' => 'Geography selection is required.',
        'startDate.required' => 'Start Date is required.',
        'endDate.required' => 'End Date is required.',
        'endDate.after_or_equal' => 'End Date must be after or equal to Start Date.',
    ];
    public function mount()
    {
        // tags
        $this->tags = DB::table('mtag')->select('mtag.tagId', 'mtag.tagInEnglish as tagName', 'mtag.tagInUnicode as tagUnicode', 'mtagcategory.tagCategoryInEnglish as category')
            ->join('mtagcategory', 'mtag.tagCategoryId', '=', 'mtagcategory.tagCategoryId')
            ->orderBy('category')
            ->get()->groupBy('category');
        // city
        $this->cities = DB::table('vGeography')->select('geography as citynameInEnglish', 'geographycode as cityId')->get();
        $this->professionArr = DB::table('professionmapping')->select('professioncode', 'profession')->get();
        $this->educationArr = DB::table('subjectmapping')->select('subjectname', 'subjectcode')->get();
        $this->emotionArr = DB::table('colorinfo')->select('id', 'name', 'colorcode')->where('emotionType', 0)->get();
    }

    public function submit()
    {
        $this->validate();

        $this->submitted = true;
        $this->resetPage(); // reset pagination on submit

        $start = microtime(true);
        $end = microtime(true);
        $this->loadTimeInSeconds = round($end - $start, 2);
    }

    public function updating($field)
    {
        // Reset pagination if anything changes (only after submit)
        if ($this->submitted) {
            $this->resetPage();
        }
    }


    public function toggleSelectAll($modelKey, $allValues)
    {
        if (count($this->$modelKey) === count($allValues)) {
            // Uncheck all
            $this->$modelKey = [];
        } else {
            // Select all
            $this->$modelKey = $allValues;
        }
    }

    public function ok()
    {
        return;
    }

    public function render()
    {

        $posts=[];
        if ($this->submitted) {
            $start = microtime(true);
            $posts = $this->getDailyPost(); // fetch only after submit
            $end = microtime(true);
            $this->loadTimeInSeconds = round($end - $start, 2);
        }

        return view('livewire.auto-content.post-listing', [
            'posts' => $posts
        ])->layout('components.layouts.admin.base');
    }

    function getDailyPost()
{


    return DB::table('chitiInfo')
        ->where('geoCode', $this->city)
        ->when($this->startDate && $this->endDate, function ($query) {
            $query->whereBetween(
                DB::raw("STR_TO_DATE(uploadDate, '%d-%m-%Y %h:%i %p')"),
                [
                    Carbon::parse($this->startDate)->format('Y-m-d H:i:s'),
                    Carbon::parse($this->endDate)->format('Y-m-d H:i:s'),
                ]
            );
        })

        ->when(!empty($this->selectedTags), function ($query) {
            $query->whereIn('tagId', $this->selectedTags);
        })
        ->when(!empty($this->selectedProfessions), function ($query) {
            $query->whereIn('professioncode', $this->selectedProfessions);
        })
        ->when(!empty($this->selectedEducations), function ($query) {
            $query->whereIn('subjectcode', $this->selectedEducations);
        })
        ->when(!empty($this->selectedEmotions), function ($query) {
            $query->whereIn('color_value', $this->selectedEmotions);
        })
        ->paginate(6);
}

}
