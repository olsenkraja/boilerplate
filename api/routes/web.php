<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return [
        'dummy' => "Lorem ipsum dolor!"
    ];
});
