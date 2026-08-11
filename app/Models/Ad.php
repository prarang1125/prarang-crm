<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ad extends Model
{
    protected $table = 'ads';
    protected $fillable = [
        'partner_id',
        'city_id',
        'creative_link',
        'ad_link',
        'ad_title',
        'cta_title',
        'cta_text',
        'status',
        'ad_type',
    ];
}
