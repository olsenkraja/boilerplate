<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Room extends Model
{
    const CREATED_AT = null;
    const UPDATED_AT = null;
    protected $primaryKey = 'code';
    public $incrementing = false;
    protected $hidden = [
        'spy_uuid'
    ];
    protected $fillable = [
        'code',
        'status',
        'round_duration',
        'round_ends_at',
        'min_vote_percentage',
        'spy_uuid',
        'place_label',
        'points_for_spy_win',
        'points_for_spy_loss',
        'points_for_spy_found',
    ];

    /**
     * @param array $attributes
     * @return \Illuminate\Database\Eloquent\Builder|Model
     */
    public static function create(array $attributes = [])
    {
        $attributes['code'] = strtoupper(Str::random(6));
        return static::query()->create($attributes);
    }
}
