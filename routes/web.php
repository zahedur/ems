<?php

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('master');
});

Route::get('/cache-clear', function () {
    Artisan::call('cache:clear');
    return 'Cleared';
});

Route::get('/config-cache', function () {
    Artisan::call('config:cache');
    return 'cache config success';
});

Route::get('{any}', function () { return view('master'); } )->where('{any}', '.*');

Route::fallback(function () {
    return view('master');
});
