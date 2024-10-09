<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Chittigeographymapping extends Model
{
    use HasFactory;

    protected $table = 'chittigeographymapping';
    protected $primeryKey = 'id';
    public $timestamps = false;

    protected $fillable = [
        'areaId',
        'geographyId',
        'chittiId',
        'created_at',
        'created_by',
        'updated_at',
        'updated_by'
    ];

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    // Relationship with Region
    public function region()
    {
        return $this->belongsTo(Mregion::class, 'areaId', 'regionId');
    }

    // Relationship with City
    public function city()
    {
        return $this->belongsTo(Mcity::class, 'areaId', 'cityId');
    }

    // Relationship with Country
    public function country()
    {
        return $this->belongsTo(Mcountry::class, 'areaId', 'countryId');
    }

    public function chitti()
    {
        return $this->belongsTo(Chitti::class, 'chittiId', 'chittiId');
    }
}
