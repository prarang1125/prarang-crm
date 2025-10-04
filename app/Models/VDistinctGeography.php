<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VDistinctGeography extends Model
{
    use HasFactory;

    protected $table = 'VDistinctGeography';
    public $timestamps = false;

    protected $fillable = [
        'Geography'
    ];
}
