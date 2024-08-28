<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tbl_prarang_user extends Model
{
    use HasFactory;

    protected $table = 'tbl_prarang_user';
    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $fillable = [
        'users_id',
        'fb_id',
        'fb_pic',
        'google_pic',
        'google_token_id',
        'social_acount',
        'acount_type',
        'advertiser_city',
        'name',
        'email',
        'contact_no',
        'password',
        'digital_leader_status',
        'profile_complete',
        'display_image',
        'company_logo',
        'occupation',
        'city',
        'state',
        'about_me',
        'company',
        'highest_education',
        'dob',
        'category',
        'sub_category',
        'job_title',
        'company_address',
        'company_description',
        'term_check',
        'status',
        'email_verify',
        'created_by',
        'created_on',
        'updated_by',
        'updated_on',
        'ipAddress',
        'lastLogin',
        'timeTaken',
        'loginDate',
        'loginTime',
        'monthlyCount',
        'ipAddressLocation',
    ];

    const CREATED_AT = 'created_on';
    const UPDATED_AT = 'updated_on';
}
