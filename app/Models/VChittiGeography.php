<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VChittiGeography extends Model
{
    use HasFactory;

    protected $table = 'vChittiGeography';
    // protected $primaryKey = 'id';
    public $timestamps = false;

    protected $fillable = [
        'Geography',
        'chittiId',
    ];

    public function chittiData()
    {
        return $this->belongsTo(Chitti::class, 'chittiId', 'chittiId');
    }
}

