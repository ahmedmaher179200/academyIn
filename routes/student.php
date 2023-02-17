<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

date_default_timezone_set('Africa/cairo');
Route::post('/balanceCharging/{student_id}', 'App\Http\Controllers\site\student\payment@balance_charging');
Route::post('/payment/return', 'App\Http\Controllers\site\student\payment@payment_return');

Route::group(['middleware' => ['changeLang'] ,'prefix' => 'students'], function(){
    Route::post('/payment/check', 'App\Http\Controllers\site\student\payment@payment_check');

    Route::post('/whiteboard/token', 'App\Http\Controllers\site\student\home@whiteboard');

    Route::get('/', 'App\Http\Controllers\Controller@test');
    
    Route::post('login', 'App\Http\Controllers\site\student\authentication\auth@login');
    Route::post('register', 'App\Http\Controllers\site\student\authentication\auth@register');

    Route::post('get_dialing_code', 'App\Http\Controllers\site\student\authentication\auth@get_dialing_code');
    Route::group(['prefix' => 'passwordReset'], function(){
        Route::post('/', 'App\Http\Controllers\site\student\authentication\resetPasswored@new_resetPassword')->middleware('checkJWTToken:student');
        Route::post('checkCode', 'App\Http\Controllers\site\student\authentication\resetPasswored@checkCode');
        Route::post('sendCode', 'App\Http\Controllers\site\student\authentication\resetPasswored@sendCode');
    });

    Route::get('/offers', 'App\Http\Controllers\site\student\offers@index');
    Route::post('generate_agora_rtm_token', 'App\Http\Controllers\site\student\home@generate_agora_rtm_token')->middleware('checkJWTToken:student');

    Route::group(['middleware' => 'checkJWTToken:student'], function(){
        Route::group(['prefix' => 'verification'], function(){
            Route::post('/', 'App\Http\Controllers\site\student\authentication\verification@new_verification');
            Route::post('sendCode', 'App\Http\Controllers\site\student\authentication\verification@sendCode');
        });

        Route::post('/rating/add', 'App\Http\Controllers\site\student\home@add_rating');

        Route::group(['prefix' => 'myProfile'], function(){
            Route::get('/', 'App\Http\Controllers\site\student\authentication\profile@myProfile');
            Route::post('/setup_profile', 'App\Http\Controllers\site\student\authentication\profile@updateYear');
            Route::post('changePassword', 'App\Http\Controllers\site\student\authentication\profile@changePassword');
            Route::post('changeImage', 'App\Http\Controllers\site\student\authentication\profile@change_image');
            Route::post('update', 'App\Http\Controllers\site\student\authentication\profile@updateProfile');
        });

        Route::group(['prefix' => 'questions'], function(){
            Route::get('/', 'App\Http\Controllers\site\student\questions@index');
            Route::get('/my-question', 'App\Http\Controllers\site\student\questions@myQuestion');
            Route::post('/create', 'App\Http\Controllers\site\student\questions@create');
            Route::post('/delete', 'App\Http\Controllers\site\student\questions@delete');
            Route::post('/edit', 'App\Http\Controllers\site\student\questions@update');
        });

        Route::group(['prefix' => 'answers'], function(){
            Route::get('/', 'App\Http\Controllers\site\student\answers@index');
            Route::post('/create', 'App\Http\Controllers\site\student\answers@create');
            Route::post('/delete', 'App\Http\Controllers\site\student\answers@delete');
            Route::post('/edit', 'App\Http\Controllers\site\student\answers@update');
        });

        Route::group(['prefix' => 'offers'], function(){
            Route::post('/take', 'App\Http\Controllers\site\student\offers@take_offer');;
        });

        Route::group(['prefix' => 'notifications'], function(){
            Route::get('/', 'App\Http\Controllers\site\student\notificaitons@index');
            Route::get('/pagination', 'App\Http\Controllers\site\student\notificaitons@index_paginate');

            Route::get('/notifications-count', 'App\Http\Controllers\site\student\notificaitons@notification_count');
        });

        Route::group(['prefix' => 'schedules'], function(){
            Route::get('/', 'App\Http\Controllers\site\student\home@schedule');
            Route::post('/cancel', 'App\Http\Controllers\site\student\home@cancel_schedule');
        });

        Route::group(['prefix' => 'payment'], function(){
            Route::post('/request', 'App\Http\Controllers\site\student\payment@payment_request')->middleware('checkJWTToken:student');
        });

        Route::get('/home', 'App\Http\Controllers\site\student\home@index');

        Route::post('/reservations', 'App\Http\Controllers\site\student\home@my_reservations');

        Route::post('/available_classes', 'App\Http\Controllers\site\student\home@available_classes');
        Route::post('/booking', 'App\Http\Controllers\site\student\home@booking');
        Route::post('/buy/video', 'App\Http\Controllers\site\student\home@buy_video');

        Route::post('leave', 'App\Http\Controllers\site\student\home@leave');

        Route::get('/lives', 'App\Http\Controllers\site\student\lives@lives');

        Route::post('logout', 'App\Http\Controllers\site\student\authentication\auth@logout');
    });
});
Route::get('test', 'App\Http\Controllers\site\student\home@test');
