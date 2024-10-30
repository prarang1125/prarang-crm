<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mtag extends Model
{
    use HasFactory;

    protected $table = 'mtag';
    protected $primaryKey = 'tagId';
    public $timestamps = false;

    protected $fillable = [
        'tagInEnglish',
        'tagInUnicode',
        'tagCategoryId',
        'tagIcon',
        'isActive',
        'created_at',
        'created_by',
        'updated_at',
        'updated_by',
    ];

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    public function tagcategory()
    {
        return $this->belongsTo(Mtagcategory::class, 'tagCategoryId', 'tagCategoryId');
    }
}
