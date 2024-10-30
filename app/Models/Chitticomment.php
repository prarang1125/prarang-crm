<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Chitticomment extends Model
{
    use HasFactory;

    protected $table = 'chitticomment';

    protected $primaryKey = 'id';

    public $timestamps = false;

    protected $fillable = [
        'subscriberId',
        'name',
        'chittiId',
        'Comment',
        'commentDate',
        'imageUrl',
        'IP',
        'GeographyCode',
        'UserCity',
        'isLike',
        'isActive',
        'CreatedDate',
        'created_at',
        'created_by',
        'updated_at',
        'updated_by'
    ];

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';
}
