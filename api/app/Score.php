<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Score extends Model
{
    const CREATED_AT = null;
    const UPDATED_AT = null;
    protected $primaryKey = 'user_uuid';
    public $incrementing = false;
    protected $fillable = [
        'room_code',
        'user_uuid',
        'points',
        'rounds_played'
    ];

    public function user() {
        return $this->hasOne(User::class, 'uuid', 'user_uuid');
    }
}
