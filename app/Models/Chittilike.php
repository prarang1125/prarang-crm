<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Chittilike extends Model
{
    use HasFactory;

    protected $table = 'chittilike';
    protected $primaryKey = 'id';
    public $timestaps = false;

    protected $fillable = [
        'chittiId',
        'subscriberId',
        'isLiked',
        'likeDate',
        'IP',
        'GeographyCode',
        'CreatedDate',
        'UpdatedDate',
        'updated_by',
        'created_by',
    ];

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';
}
