<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Misreport extends Model
{
    use HasFactory;

    protected $table = 'misreport';
    protected $primaryKey = 'Id';
    public $timestamps = false;

    protected $fillable = [
        'SubscriberId',
        'UserCity',
        'UserName',
        'MobileNumber',
        'EmailId',
        'DateOfJoining',
        'AppDownloadDate',
        'TotalColorFeeds',
        'AppUsageTime',
        'FeedsComments',
        'FeedsLikes',
        'FeedShares',
        'SavedBank',
        'LucknowColorFeeds',
        'MeerutColorFeeds',
        'RampurColorFeeds',
        'JaunpurColorFeeds',
        'Rampur',
        'Meerut',
        'Lucknow',
        'Jaunpur',
        'JaunpurLikes',
        'LucknowLikes',
        'MeerutLikes',
        'RampurLikes',
        'JaunpurShare',
        'LucknowShare',
        'MeerutShare',
        'RampurShare',
        'JaunpurBank',
        'LucknowBank',
        'MeerutBank',
        'RampurBank',
        'JaunpurCommnet',
        'LucknowCommnet',
        'MeerutCommnet',
        'RampurCommnet',
    ];

    const CREATED_AT = 'CreatedDate';
    const UPDATED_AT = 'UpdatedDate';

    public function userCity()
    {
        return $this->belongsTo(UserCity::class, 'UserCity', 'cityId');
    }
}
