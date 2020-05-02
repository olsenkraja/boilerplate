<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Place extends Model
{
    const CREATED_AT = null;
    const UPDATED_AT = null;
    protected $fillable = [
        'label'
    ];
}
