<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ColorInfo extends Model
{
    use HasFactory;

    protected $table = 'colorinfo';
    protected $primaryKey = 'id';
    public $timestaps = false;

    protected $fillable = [
        'name',
        'colorname',
        'colorcode',
        'likeDate',
        'emotionType',
        'Font_Color',
    ];
}
