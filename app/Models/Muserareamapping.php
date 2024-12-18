<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Muserareamapping extends Model
{
    use HasFactory;

    protected $table = 'muserareamapping';
    protected $primaryKey = 'id';
    public $timestamps = false;

    public $fillable = [
        'userId',
        'areaCode',
    ];
}
