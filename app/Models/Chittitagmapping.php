<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Chittitagmapping extends Model
{
    use HasFactory;

    protected $table = 'chittitagmapping';
    protected $primaryKey = 'id';
    public $timestaps = false;

    protected $fillable = [
        'chittiId',
        'tagId',
        'created_at',
        'created_by',
        'updated_at',
        'updated_by',
    ];

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    public function tag()
    {
        return $this->belongsTo(Mtag::class, 'tagId', 'tagId');
    }
}
