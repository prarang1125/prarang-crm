<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mtagcategory extends Model
{
    use HasFactory;

    protected $table = 'mtagcategory';
    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $fillable = [
        'tagCategoryId',
        'tagCategoryInEnglish',
        'tagCategoryInUnicode',
        'isActive',
        'created_at',
        'created_by',
        'updated_at',
        'updated_by',
    ];

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';
}
