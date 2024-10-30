<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Muser extends Authenticatable
{
    use HasFactory;

    // Specify the table name
    protected $table = 'muser';

    // Specify the primary key if it's not 'id'
    protected $primaryKey = 'userId';

    // If the primary key is not auto-incrementing, uncomment the following line:
    // public $incrementing = false;

    // If your primary key is a non-integer type, specify the key type
    // protected $keyType = 'string';

    // Disable Laravel's timestamps if you are not using `created_at` and `updated_at` fields
    public $timestamps = false;

    protected $fillable = [
        'firstName',
        'lastName',
        'emailId',
        'empPassword',
        'roleId',
        'languageId',
        'isActive',
        'created_at',
        'created_by',
        'updated_at',
        'updated_by',
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
}
