<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mlanguagescript extends Model
{
    use HasFactory;

    protected $table = 'mlanguagescript';
    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $fillable = [
        'language',
        'languageInUnicode',
        'languageUnicode',
        'isActive'
    ];
}
