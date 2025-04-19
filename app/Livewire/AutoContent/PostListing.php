<?php

namespace App\Livewire\AutoContent;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class PostListing extends Component
{
    public $posts = [];
    public $tags = [], $cities = [];
    public $city, $startDate, $endDate, $tag, $comparator, $value;
    public $profession, $education, $professionArr, $educationArr, $emotion, $emotionArr;
    public $selectedTags = [], $selectAllTags = false;
    public $selectedProfessions = [];
    public $selectedEducations = [];
    public $selectedEmotions = [];
    public $loadTimeInSeconds = null;


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
        $start = microtime(true);
        $this->validate();
        $this->posts = $this->getDailyPost();
        $end = microtime(true);
        $this->loadTimeInSeconds = round($end - $start, 2);
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

    public function ok(){
        return;
    }

    public function render()
    {
        return view('livewire.auto-content.post-listing')->layout('components.layouts.admin.base');
    }

    function getDailyPost()
    {
        $data = DB::table('chitti as post')
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
            ->join('colorinfo as emotion', function ($join) {
                $join->on('emotion.id', '=', 'post.color_value')
                    ->where('emotion.emotionType', '=', 0);
            })
            ->join('facity as lgb', 'lgb.chittiId', '=', 'post.chittiId')
            ->where('post.finalStatus', 'Approved')
            ->select(
                'post.chittiId as id',
                'post.dateOfApprove as uploadDate',
                'post.Title',
                'vgeo.geographycode as geoCode',
                'vgeo.geography as geography',
                'emotion.name as emptionName',
                'emotion.colorcode as colorCode',
                'image.imageUrl as image',
                'post.totalViewerCount as totalViews',
                'post.makerId',
                'post.checkerId',
                'post.uploaderId',
                'maker.firstName as makerName',
                'checker.firstName as CheckerName',
                'uploader.firstName as UploaderName',
                'tagInfo.tagId as tagId',
                'tagInfo.tagInUnicode as tagName',
                'lgb.value as localGlobal',
                'pro.professioncode',
                'pro.profession',
                'sub.subjectcode',
                'sub.subjectname',
                'post.description'
            )
            ->limit(20)
            ->get();

        return $data;
    }
}
