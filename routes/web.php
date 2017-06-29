<?php

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

Auth::routes();

/**
 * Backend Routes
 */
Route::get('app/{vuerouter?}', 'AppController@start')
    ->middleware('auth')
    ->where('vuerouter', '.*')
    ->name('app');

/**
 * Frontend
 */
Route::get('/', function () {
    return view('welcome');
});
