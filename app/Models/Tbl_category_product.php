<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tbl_category_product extends Model
{
    use HasFactory;

    protected $table = 'tbl_category_product';
    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $fillable = [
        'category_id',
        'sub_id',
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
