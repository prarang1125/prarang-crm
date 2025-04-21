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
    public $city, $startDate, $endDate, $tag,$comparator, $value;
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
        dd($this->getFilteredPosts());
        $this->submitted = true;
        $this->resetPage();
    }

    public function updating($field)
    {
        if ($this->submitted) {
            $this->resetPage();
        }
    }
    public function ok(){
        return ;
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

        $query = DB::table('chitti as post')
        ->select(
            'post.chittiId as id',
            'post.dateOfApprove as uploadDate',
            'post.Title',
            'vgeo.geographycode as geoCode',
            'vgeo.geography',
            'emotion.name as emotionName',
            'emotion.colorcode as colorCode',
            'image.imageUrl as image',
            'post.totalViewerCount as totalViews',
            'post.makerId',
            'post.checkerId',
            'post.uploaderId',
            'maker.firstName as makerName',
            'checker.firstName as checkerName',
            'uploader.firstName as uploaderName',
            DB::raw('GROUP_CONCAT(DISTINCT tagInfo.tagId) as tagIds'),
            DB::raw('GROUP_CONCAT(DISTINCT tagInfo.tagInUnicode) as tagNames'),
            'lgb.value as localGlobal',
            DB::raw('GROUP_CONCAT(DISTINCT pro.professioncode) as professionCodes'),
            DB::raw('GROUP_CONCAT(DISTINCT pro.profession) as professions'),
            DB::raw('GROUP_CONCAT(DISTINCT sub.subjectcode) as subjectCodes'),
            DB::raw('GROUP_CONCAT(DISTINCT sub.subjectname) as subjectNames'),
            'post.description'
        )
        ->join('chittiimagemapping as image', 'post.chittiId', '=', 'image.chittiId')
        ->join('chittitagmapping as tag', 'post.chittiId', '=', 'tag.chittiId')
        ->join('mtag as tagInfo', 'tag.tagId', '=', 'tagInfo.tagId')
        ->join('professiontagmapping as pt', 'tagInfo.tagId', '=', 'pt.tagId')
        ->join('professionmapping as pro', 'pt.professioncode', '=', 'pro.professioncode')
        ->join('submaptag as subtag', 'tagInfo.tagId', '=', 'subtag.tagid')
        ->join('subjectmapping as sub', 'subtag.subjectcode', '=', 'sub.subjectcode')
        ->join('muser as maker', 'post.makerId', '=', 'maker.userId')
        ->join('muser as checker', 'post.checkerId', '=', 'checker.userId')
        ->join('muser as uploader', 'post.uploaderId', '=', 'uploader.userId')
        ->join('vchittigeography as geo', 'geo.chittiId', '=', 'post.chittiId')
        ->join('vgeography as vgeo', 'vgeo.geographycode', '=', 'geo.Geography')
        ->join('colorinfo as emotion', 'emotion.id', '=', 'post.color_value')
        ->join('facity as lgb', 'lgb.chittiId', '=', 'post.chittiId')
        ->whereIn('post.chittiId', [9111, 9112, 9113, 9114])
        ->where('emotion.emotionType', 0)
        ->where('post.finalStatus', 'Approved')
        ->groupBy('post.chittiId')
        ->orderByDesc('post.dateOfApprove');

    // Apply filters
    $query->when($this->city, function ($query) {
        $query->where('vgeo.geographycode', $this->city);
    });

    $query->when($this->startDate && $this->endDate, function ($query) {
        $query->whereBetween(DB::raw("STR_TO_DATE(post.dateOfApprove, '%d-%m-%Y %h:%i %p')"), [
            Carbon::parse($this->startDate)->format('Y-m-d H:i:s'),
            Carbon::parse($this->endDate)->format('Y-m-d H:i:s'),
        ]);
    });

    $query->when(!empty($this->selectedTags), function ($query) {
        $query->whereIn('tagInfo.tagId', $this->selectedTags);
    });

    $query->when(!empty($this->selectedProfessions), function ($query) {
        $query->whereIn('pro.professioncode', $this->selectedProfessions);
    });

    $query->when(!empty($this->selectedEducations), function ($query) {
        $query->whereIn('sub.subjectcode', $this->selectedEducations);
    });

    $query->when(!empty($this->selectedEmotions), function ($query) {
        $query->whereIn('post.color_value', $this->selectedEmotions);
    });

    $posts = $query->paginate(6);

        $end = microtime(true);
        $this->loadTimeInSeconds = round($end - $start, 2);
            // dd($posts);
        return $posts;
    }
}
