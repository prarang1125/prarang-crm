<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Facity extends Model
{
    use HasFactory;

    protected $table = 'facity';

    protected $primaryKey = 'chittiId';

    public $timestamps = false;

    protected $fillable = [
        'value',
        'chittiId',
        'created_at',
        'created_by',
        'updated_at',
        'updated_by',
    ];

    const CREATED_AT = 'created_at';

    const UPDATED_AT = 'updated_at';

    public function chitti()
    {
        return $this->belongsTo(Chitti::class, 'chittiId', 'chittiId');
    }
}
