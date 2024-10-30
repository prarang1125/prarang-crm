<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Makerlebal extends Model
{
    use HasFactory;

    protected $table = 'makerlebal';
    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $fillable = [
        'labelInEnglish',
        'labelInUnicode',
        'languageScriptId',
    ];
}
