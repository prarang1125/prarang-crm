<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Msubscriberlist extends Model
{
    use HasFactory;

    protected $table = 'msubscriberlist';
    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $fillable = [
        'subscriberId',
        'name',
        'mobileNo',
        'profilePicUrl',
        'userCountryId',
        'userCityId',
        'baseCityId',
        'isHindi',
        'isEnglish',
        'isVerified',
        'otp',
        'gcmKey',
        'status',
        'IP',
        'Date',
        'Time',
        'created_at',
        'created_by',
        'updated_at',
        'updated_by',
    ];

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';
}
