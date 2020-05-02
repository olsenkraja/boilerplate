<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;

class User extends Authenticatable
{
    protected $primaryKey = 'uuid';
    public $incrementing = false;
    protected $fillable = [
        'uuid',
        'room_code',
        'name',
        'role_label',
    ];

    public static function create(array $attributes = [])
    {
        $attributes['uuid'] = Str::uuid()->toString();
        return static::query()->create($attributes);
    }
}
