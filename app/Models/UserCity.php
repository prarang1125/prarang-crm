<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserCity extends Model
{
    use HasFactory;

    protected $table = 'userCity';
    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $fillable = [
        'cityId',
        'cityNameInHindi',
        'cityNameInEnglish',
        'countryId',
        'isActive',
        'created_at',
        'created_by',
        'updated_at',
        'updated_by',
    ];

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';
}
