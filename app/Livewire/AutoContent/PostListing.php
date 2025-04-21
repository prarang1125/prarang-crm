<?php

namespace App\Livewire\AutoContent;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;

class PostListing extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';
    public $tags = [], $cities = [];
    public $city, $startDate, $endDate, $tag,$comparator, $value;
    public $profession, $education, $emotion;
    public $professionArr, $educationArr, $emotionArr;

    public $selectedTags = [], $selectAllTags = false;
    public $selectedProfessions = [], $selectedEducations = [], $selectedEmotions = [],$selectedPosts=[];
    public $forAbout;
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
        $start = microtime(true);
        $this->validate();
        $this->submitted = true;
        $this->resetPage();
        $end = microtime(true);
        $this->loadTimeInSeconds = round($end - $start, 2);
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

        return view('livewire.auto-content.post-listing', compact('posts'))->layout('components.layouts.admin.base');
    }

    function getFilteredPosts()
    {
        $data = DB::table('chitti as post')
            ->select(
                'post.chittiId as id',
                'post.dateOfApprove as uploadDate',
                'post.Title',
                'vgeo.geographycode as geoCode',
                'vgeo.geography as geography',
                'emotion.name as emotionName',
                'emotion.id as emotionId',
                'emotion.colorcode as colorCode',
                'image.imageUrl as image',
                'post.totalViewerCount as totalViews',
                'post.makerId',
                'post.checkerId',
                'post.uploaderId',
                'maker.firstName as makerName',
                'checker.firstName as checkerName',
                'uploader.firstName as uploaderName',
                'tagInfo.tagId as tagId',
                'tagInfo.tagInUnicode as tagName',
                'tagInfo.tagInEnglish as tagEnglish',
                'lgb.value as localGlobal',
                'post.description'
            )
            ->join('chittiimagemapping as image', 'post.chittiId', '=', 'image.chittiId')
            ->join('chittitagmapping as tag', 'post.chittiId', '=', 'tag.chittiId')
            ->join('mtag as tagInfo', 'tag.tagId', '=', 'tagInfo.tagId')
            ->leftJoin('muser as maker', 'post.makerId', '=', 'maker.userId')
            ->leftJoin('muser as checker', 'post.checkerId', '=', 'checker.userId')
            ->leftJoin('muser as uploader', 'post.uploaderId', '=', 'uploader.userId')
            ->join('vChittiGeography as geo', 'geo.chittiId', '=', 'post.chittiId')
            ->join('vGeography as vgeo', 'vgeo.geographycode', '=', 'geo.Geography')
            ->join('colorinfo as emotion', function ($join) {
                $join->on('emotion.id', '=', 'post.color_value')
                     ->where('emotion.emotionType', '=', 0);
            })
            ->join('facity as lgb', 'lgb.chittiId', '=', 'post.chittiId')
            ->where('post.finalStatus', 'Approved')
            ->where('vgeo.geographycode', $this->city)
            ->when($this->startDate && $this->endDate, function ($query) {
                $query->whereBetween(
                    DB::raw("STR_TO_DATE(post.dateOfApprove, '%d-%m-%Y %h:%i %p')"),
                    [
                        Carbon::parse($this->startDate)->format('Y-m-d H:i:s'),
                        Carbon::parse($this->endDate)->format('Y-m-d H:i:s'),
                    ]
                );
            })
            ->when(!empty($this->selectedTags), function ($query) {
                $query->whereIn('tag.tagId', $this->selectedTags);
            })
            ->when(!empty($this->selectedEmotions), function ($query) {
                $query->whereIn('emotion.id', $this->selectedEmotions);
            })
            ->when(!empty($this->forAbout), function ($query) {
                $query->where('lgb.value', $this->selectedEmotions);
            })
            ->paginate(10);


        return $data;
    }

}
