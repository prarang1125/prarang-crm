<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Chitti extends Model
{
    use HasFactory;

    // Specify the table name
    protected $table = 'chitti';

    // Specify the primary key if it's not 'id'
    protected $primaryKey = 'chittiId';

    // If the primary key is not auto-incrementing, uncomment the following line:
    // public $incrementing = false;

    // If your primary key is a non-integer type, specify the key type
    // protected $keyType = 'string';

    // Disable Laravel's timestamps if you are not using `created_at` and `updated_at` fields
    public $timestamps = false;

    protected $fillable = [
        'languageId',
        'chittiname',
        'chittiUrl',
        'description',
        'Show_description',
        'makerId',
        'checkerId',
        'uploaderId',
        'dateOfCreation',
        'createDate',
        'dateOfReturnToMaker',
        'returnDateMaker',
        'dateSentToUploader',
        'sendDateToUploader',
        'dateOfReturnToChecker',
        'returnDateToChecker',
        'dateOfApprove',
        'fb_link_click',
        'uploadDataTime',
        'approveDate',
        'dateOfUpload',
        'makerStatus',
        'checkerReason',
        'checkerStatus',
        'uploaderStatus',
        'uploaderReason',
        'finalStatus',
        'Title',
        'SubTitle',
        'metaTag',
        'color_value',
        'postViewershipDate',
        'postViewershipDateTo',
        'noofDaysfromUpload',
        'advertisementPost',
        'citySubscriber',
        'totalViewerCount',
        'prarangApplication',
        'websiteCount',
        'emailCount',
        'sponsoredBy',
        'instagramCount',
        'analyticsChecker',
        'analyticsMaker',
        'postStatusMakerChecker',
        'monthDay',
        'writercolor',
        'cityId',
        'areaId',
        'geographyId',
        'post_anlytics_rtrn_to_mkr',
        'post_anlytics_rtrn_to_mkr_id',
        'return_chitti_post_from_checker',
        'return_chitti_post_from_checker_id',
        'created_at',
        'created_by',
        'updated_at',
        'updated_by'
    ];


    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    // Optionally, define the date format for the timestamps
    // protected $dateFormat = 'Y-m-d H:i:s';

    public function geographyMappings()
    {
        return $this->hasMany(Chittigeographymapping::class, 'chittiId', 'chittiId');
    }

    public function facity()
    {
        return $this->hasOne(Facity::class, 'from_chittiId', 'chittiId');
    }

    public function chittiimagemappings()
    {
        return $this->hasMany(Chittiimagemapping::class, 'chittiId', 'chittiId');
    }

    // public function chittigeographymappings()
    // {
    //     return $this->hasMany(Chittigeographymapping::class, 'chittiId', 'chittiId');
    // }

    // public function chittitagmappings()
    // {
    //     return $this->hasMany(Chittitagmapping::class, 'chittiId', 'chittiId');
    // }

    // public function images()
    // {
    //     return $this->hasMany(Chittiimagemapping::class, 'chittiId', 'chittiId');
    // }

    public function city()
    {
        return $this->belongsTo(Mcity::class, 'cityId', 'cityId'); // adjust the foreign key if necessary
    }

    public function analyticsMaker()
    {
        return $this->belongsTo(Muser::class, 'analyticsMaker', 'userId');
    }

    public function likes()
    {
        return $this->hasMany(Chittilike::class, 'chittiId', 'chittiId');
    }

    public function comments()
    {
        return $this->hasMany(Chitticomment::class, 'chittiId', 'chittiId');
    }

    public function geographyDetails()
    {
        return $this->hasMany(VChittiGeography::class, 'chittiId', 'chittiId');
    }

}

