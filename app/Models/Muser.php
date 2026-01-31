<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Muser extends Authenticatable
{
    use HasFactory;

    // Specify the table name
    protected $table = 'muser';

    // Specify the primary key if it's not 'id'
    protected $primaryKey = 'userId';

    use SoftDeletes;
    protected $dates = ['deleted_at'];


    public $timestamps = false;

    protected $fillable = [
        'firstName',
        'lastName',
        'emailId',
        'empPassword',
        'roleId',
        'languageId',
        'isActive',
        'geography',
        'created_at',
        'created_by',
        'updated_at',
        'updated_by',
    ];

    protected $casts = [
        'geography' => 'array',
    ];


    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    /**
     * Get the password for the user.
    */
    public function getAuthPassword()
    {
        return $this->empPassword;
    }

    // Optionally, define the date format for the timestamps
    // protected $dateFormat = 'Y-m-d H:i:s';

    public function setPasswordAttribute($value)
    {
        $this->attributes['empPassword'] = bcrypt($value);
    }

    public function role()
    {
        return $this->hasOne(Mrole::class, 'roleID', 'roleId');
    }

    public function languageScript()
    {
        return $this->hasOne(Mlanguagescript::class, 'id', 'languageId');
    }

    public function analyticsMaker()
    {
        return $this->belongsTo(Muser::class, 'analyticsMaker', 'userId');
    }
    public function latestChitti()
{
    return $this->hasMany(Chitti::class, 'makerId', 'userId');
}

}
