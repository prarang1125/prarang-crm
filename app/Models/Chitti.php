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
    protected $primaryKey = 'id';

    // If the primary key is not auto-incrementing, uncomment the following line:
    // public $incrementing = false;

    // If your primary key is a non-integer type, specify the key type
    // protected $keyType = 'string';

    // Disable Laravel's timestamps if you are not using `created_at` and `updated_at` fields
    public $timestamps = false;

    protected $fillable = [
        'chittiId',
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
        'created_at',
        'created_by',
        'updated_at',
        'updated_by'

    ];

    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    // Optionally, define the date format for the timestamps
    // protected $dateFormat = 'Y-m-d H:i:s';
}

