<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mrole extends Model
{
    use HasFactory;

    protected $table = 'mrole';
    protected $primaryKey = 'roleID';
    public $timestamps = false;

    protected $fillable = [
        'roleName',
        'status'
    ];
}
