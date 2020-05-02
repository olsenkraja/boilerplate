<?php

use Illuminate\Support\Facades\Route;

Route::get('/users/{roomCode}', 'UserController@index');
Route::post('/users', 'UserController@store');
Route::delete('/users/{uuid}', 'UserController@destroy');

Route::post('/rooms', 'RoomController@store');
Route::patch('/rooms/{code}/prepare', 'RoomController@prepare');
Route::patch('/rooms/{code}/playing', 'RoomController@playing');
Route::get('/rooms/{code}/finished', 'RoomController@finished');
Route::patch('/rooms/{code}/reset', 'RoomController@reset');

Route::patch('/rounds/vote/{userUuid}/{votedForUserUuid}', 'RoundController@vote');
Route::patch('/rounds/reveal/{userUuid}/{placeLabel}', 'RoundController@reveal');

Route::get('/scores/{roomCode}', 'ScoreController@index');
