<?php

namespace App\Livewire\AutoContent;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;

class PostListing extends Component
{
    use WithPagination;

    public $tags = [], $cities = [];
    public $city, $startDate, $endDate;
    public $profession, $education, $emotion;
    public $professionArr, $educationArr, $emotionArr;

    public $selectedTags = [], $selectAllTags = false;
    public $selectedProfessions = [], $selectedEducations = [], $selectedEmotions = [];

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
        $this->tags = DB::table('mtag')
            ->select('mtag.tagId', 'mtag.tagInEnglish as tagName', 'mtag.tagInUnicode as tagUnicode', 'mtagcategory.tagCategoryInEnglish as category')
            ->join('mtagcategory', 'mtag.tagCategoryId', '=', 'mtagcategory.tagCategoryId')
            ->orderBy('category')
            ->get()
            ->groupBy('category');

        $this->cities = DB::table('vGeography')
            ->select('geography as citynameInEnglish', 'geographycode as cityId')
            ->get();

        $this->professionArr = DB::table('professionmapping')
            ->select('professioncode', 'profession')
            ->get();

        $this->educationArr = DB::table('subjectmapping')
            ->select('subjectname', 'subjectcode')
            ->get();

        $this->emotionArr = DB::table('colorinfo')
            ->select('id', 'name', 'colorcode')
            ->where('emotionType', 0)
            ->get();
    }

    public function submit()
    {
        $this->validate();

        $this->submitted = true;
        $this->resetPage();
    }

    public function updating($field)
    {
        if ($this->submitted) {
            $this->resetPage();
        }
    }

    public function toggleSelectAll($modelKey, $allValues)
    {
        $this->$modelKey = (count($this->$modelKey) === count($allValues)) ? [] : $allValues;
    }

    public function render()
    {
        $posts = $this->submitted ? $this->getFilteredPosts() : collect();

        return view('livewire.auto-content.post-listing', [
            'posts' => $posts,
        ])->layout('components.layouts.admin.base');
    }

    public function getFilteredPosts()
    {
        $start = microtime(true);

        $query = DB::table('chitiInfo')
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
            });

        $posts = $query->paginate(6);

        $end = microtime(true);
        $this->loadTimeInSeconds = round($end - $start, 2);

        return $posts;
    }
}
