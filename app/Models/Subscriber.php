<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Subscriber extends Model
{
    protected $connection = 'yp';
    protected $table = 'users';
    protected $guarded = [];
}
