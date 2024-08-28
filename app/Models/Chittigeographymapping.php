<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Chittigeographymapping extends Model
{
    use HasFactory;

    protected $table = 'chittigeographymapping';
    protected $primeryKey = 'id';
    public $timestamps = false;

    protected $fillable = [
        'areaId',
        'geographyId',
        'chittiId',
        'created_at',
        'created_by',
        'updated_at',
        'updated_by'
    ];

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';
}
