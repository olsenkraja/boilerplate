<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Round extends Model
{
    const CREATED_AT = null;
    const UPDATED_AT = null;
    protected $fillable = [
        'room_code',
        'user_uuid',
        'voted_for_user_uuid',
        'role_reaction',
    ];
}
