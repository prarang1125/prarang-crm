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
        'status',
        'created_at',
        'created_by',
        'updated_at',
        'updated_by',
    ];

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';
}
