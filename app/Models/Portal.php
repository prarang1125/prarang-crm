<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
class Portal extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'city_id',
        'city_code',
        'city_slogan',
        'map_link',
        'weather_widget_code',
        'sports_widget_code',
        'news_widget_code',
        'local_matrics',
        'header_image',
        'footer_image',
        'local_info_image',
        'city_name',
        'city_name_local',
        'local_lang',
        'slug',
    ];
}
