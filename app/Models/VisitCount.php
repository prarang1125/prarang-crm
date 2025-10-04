<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VisitCount extends Model
{
    use HasFactory;
    protected $table = 'visitCount';
    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $fillable = [
        'subscriberId',
        'dateTime',
        'created_at',
        'created_by',
        'updated_at',
        'updated_by',
    ];

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';
}
