<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserCountry extends Model
{
    use HasFactory;

    protected $table = 'userCountry';
    protected $primaryKey = 'countryId';
    public $timestamps = false;

    protected $fillable = [
        'countryCode',
        'countryNameInHindi',
        'countryNameInEnglish',
        'isActive',
        'created_at',
        'created_by',
        'updated_at',
        'updated_by',
    ];

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';
}
