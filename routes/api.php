<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Tool API Routes
|--------------------------------------------------------------------------
|
| Here is where you may register API routes for your tool. These routes
| are loaded by the ServiceProvider of your tool. They are protected
| by your tool's "Authorize" middleware by default. Now, go build!
|
*/

Route::get('/index', 'MediableController@index');
Route::post('/upload', 'MediableController@upload');
Route::get('/delete/{id}', 'MediableController@delete');
Route::get('/stats', 'MediableController@stats');
Route::post('/mediable', 'MediableController@mediable');
Route::post('/attach/{id}', 'MediableController@attach');
Route::post('/detach/{id}', 'MediableController@detach');
