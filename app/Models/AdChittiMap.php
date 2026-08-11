<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AdChittiMap extends Model
{
    protected $table = 'ad_chitti_maps';

    protected $fillable = [
        'ad_id', 'chitti_id', 'ad_code', 'status',
    ];

    public function ad()
    {
        return $this->belongsTo(Ad::class);
    }
}
