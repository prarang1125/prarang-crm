<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Intent extends Model
{
    protected $table = 'intentdb';
    protected $primaryKey = 'id';

    public $timestamps = false;
    protected $fillable = [
        'id',
        'chitti_id',
        'intent_type',
        'summary',
        'created_at',
        'intent',
    ];

}
