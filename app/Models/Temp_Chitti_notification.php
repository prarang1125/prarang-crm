<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Temp_Chitti_notification extends Model
{
    use HasFactory;

    protected $table = 'temp_Chitti_notification';
    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $fillable = [
        'text',
        'Admin_Id',
        'page_name',
        'chID',
        'temp_text',
        'created_at',
        'created_by',
        'updated_at',
        'updated_by',
    ];

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';
}
