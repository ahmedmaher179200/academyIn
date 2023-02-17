<?php

use Illuminate\Support\Facades\Route;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

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
date_default_timezone_set('Africa/cairo');

Route::group(['prefix' => 'offices'], function(){
    Route::get('/', 'App\Http\Controllers\office\dashbourd@index')->middleware('auth:office');

    Route::get('/login', 'App\Http\Controllers\office\authentication@loginView')->name('officelogin')->middleware('guest:office');
    Route::post('/login', 'App\Http\Controllers\office\authentication@login')->middleware('guest:office');

    Route::get('/logout', 'App\Http\Controllers\office\authentication@logout')->middleware('auth:office');

    Route::group(['prefix' => 'teachers'],function(){
        Route::get('/', 'App\Http\Controllers\office\teachers@index')->middleware('auth:office');
        Route::get('/delete/{id}', 'App\Http\Controllers\office\teachers@delete')->middleware('auth:office');
        Route::get('/create', 'App\Http\Controllers\office\teachers@createView')->middleware('auth:office');
        Route::post('/create', 'App\Http\Controllers\office\teachers@create')->middleware('auth:office');
        Route::get('/edit/{id}', 'App\Http\Controllers\office\teachers@editView')->middleware('auth:office');
        Route::post('/edit/{id}', 'App\Http\Controllers\office\teachers@edit')->middleware('auth:office');
    });
});


