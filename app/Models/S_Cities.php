<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class S_Cities extends Model
{
    use HasFactory;

    protected $table = 'S_Cities';
    protected $primaryKey = 'cityId';
    public $timestamps = false;

    protected $fillable = [
        'cityCode',
        'citynameInUnicode',
        'citynameInEnglish',
        'image',
        'Image_Name',
        'State',
        'text',
        'IsActive',
        'created_at',
        'created_by',
        'updated_at',
        'updated_by',
    ];

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

}
