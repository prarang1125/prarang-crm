<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VGeography extends Model
{
    use HasFactory;

    protected $table = 'VGeography';
    public $timestamps = false;

    protected $fillable = [
        'geography',
        'geographycode',
        'image',
        'map',
        'text',
        'Culture_Nature',
    ];
}
