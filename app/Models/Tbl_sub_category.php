<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tbl_sub_category extends Model
{
    use HasFactory;
    protected $table = 'tbl_sub_category';
    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $fillable = [
        'category_id',
        'title',
        'status',
        'created_on',
        'created_by',
        'updated_at',
        'updated_by',
    ];

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';
}
