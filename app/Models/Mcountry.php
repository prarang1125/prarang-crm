<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mcountry extends Model
{
    use HasFactory;

    protected $table = 'mcountry';
    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $fillable = [
        'countryId',
        'countryCode',
        'countryNameInUnicode',
        'countryNameInEnglish',
        'Image',
        'Map',
        'Image_Name',
        'Map_Name',
        'isActive',
        'text',
        'Culture_Nature',
        'created_at',
        'created_by',
        'updated_at',
        'updated_by',
    ];

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    // public function regions()
    // {
    //     return $this->hasMany(Mregion::class, 'id', 'id');
    // }

    // public function getGeography()
    // {
    //     $countries = Mcountry::select(
    //         'mcountry.countryCode',
    //         'mcountry.countryNameInEnglish',
    //         'mcity.cityCode',
    //         'mcity.citynameInEnglish',
    //         'mregion.regionCode',
    //         'mregion.regionnameInEnglish'
    //     )
    //     ->leftJoin('mcity', 'mcountry.id', '=', 'mcity.id')
    //     ->leftJoin('mregion', 'mcountry.id', '=', 'mregion.id')
    //     ->get();
    //     return $countries;
    // }

}
