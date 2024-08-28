<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mregion extends Model
{
    use HasFactory;

    protected $table = 'mregion';
    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $fillable = [
        'regionId',
        'regionCode',
        'regionnameInUnicode',
        'regionnameInEnglish',
        'isActive',
        'image',
        'map',
        'Image_Name',
        'Map_Name',
        'text',
        'Culture_Nature',
        'created_at',
        'created_by',
        'updated_at',
        'updated_by',
    ];

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';
}
