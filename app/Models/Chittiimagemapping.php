<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Chittiimagemapping extends Model
{
    use HasFactory;

    protected $table = 'chittiimagemapping';
    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $fillable = [
        'imageId',
        'chittiId',
        'imageName',
        'imageUrl',
        'accessUrl',
        'VideoURL',
        'VideoId',
        'VideoExist',
        'isActive',
        'isDefult',
        'imageTag',
        'Mainimageurl',
        'created_at',
        'created_by',
        'updated_at',
        'updated_by',
    ];

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';
}
