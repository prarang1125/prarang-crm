<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OurTeam extends Model
{
    protected $fillable = [
        'profile_image',
        'display_name',
        'role',
        'linkedin_link',
        'userId'];
}
