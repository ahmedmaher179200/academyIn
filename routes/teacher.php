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

Route::group(['middleware' => ['changeLang'] ,'prefix' => 'teachers'], function(){
    Route::post('/whiteboard/token', 'App\Http\Controllers\site\teacher\home@whiteboard');
    Route::get('/test', 'App\Http\Controllers\site\teacher\home@test');


    Route::post('login', 'App\Http\Controllers\site\teacher\authentication\auth@login');
    Route::post('register', 'App\Http\Controllers\site\teacher\authentication\auth@register');

    Route::post('get_dialing_code', 'App\Http\Controllers\site\teacher\authentication\auth@get_dialing_code');
    Route::group(['prefix' => 'passwordReset'], function(){
        Route::post('/', 'App\Http\Controllers\site\teacher\authentication\resetPasswored@new_resetPassword')->middleware('checkJWTToken:teacher');
        Route::post('checkCode', 'App\Http\Controllers\site\teacher\authentication\resetPasswored@checkCode');
        Route::post('sendCode', 'App\Http\Controllers\site\teacher\authentication\resetPasswored@sendCode');
    });

    
    //auth
    Route::group(['middleware' => 'checkJWTToken:teacher'], function(){
        Route::group(['prefix' => 'profile'], function(){
            Route::get('/', 'App\Http\Controllers\site\teacher\authentication\profile@myProfile');
            Route::post('changePassword', 'App\Http\Controllers\site\teacher\authentication\profile@changePassword');
            Route::post('changeImage', 'App\Http\Controllers\site\teacher\authentication\profile@change_image');
            Route::post('update', 'App\Http\Controllers\site\teacher\authentication\profile@updateProfile');
            Route::post('setup_profile', 'App\Http\Controllers\site\teacher\authentication\profile@setup_profile');
        });

        Route::group(['prefix' => 'myProfile'], function(){
            Route::get('/', 'App\Http\Controllers\site\teacher\authentication\profile@myProfile');
            Route::post('changePassword', 'App\Http\Controllers\site\teacher\authentication\profile@changePassword');
            Route::post('changeImage', 'App\Http\Controllers\site\teacher\authentication\profile@change_image');
            Route::post('update', 'App\Http\Controllers\site\teacher\authentication\profile@updateProfile');
            Route::post('setup_profile', 'App\Http\Controllers\site\teacher\authentication\profile@setup_profile');
        });

        Route::group(['prefix' => 'verification'], function(){
            Route::post('/', 'App\Http\Controllers\site\teacher\authentication\verification@new_verification');
            Route::post('sendCode', 'App\Http\Controllers\site\teacher\authentication\verification@sendCode');
        });

        Route::group(['prefix' => 'answers'], function(){
            Route::get('/', 'App\Http\Controllers\site\teacher\answers@index');
            Route::get('/my-answers', 'App\Http\Controllers\site\teacher\answers@myAnswers');
            Route::post('/create', 'App\Http\Controllers\site\teacher\answers@create');
            Route::post('/delete', 'App\Http\Controllers\site\teacher\answers@delete');
            Route::post('/edit', 'App\Http\Controllers\site\teacher\answers@update');
        });

        Route::get('questions', 'App\Http\Controllers\site\teacher\questions@index');

        //pages
        Route::group(['prefix' => 'schedules'], function(){
            Route::get('/', 'App\Http\Controllers\site\teacher\home@schedule');
            Route::get('/date', 'App\Http\Controllers\site\teacher\home@schedule_date');
            Route::get('/classes_type', 'App\Http\Controllers\site\teacher\home@class_type');
            Route::post('add', 'App\Http\Controllers\site\teacher\home@add_schedule');
            Route::post('cancel', 'App\Http\Controllers\site\teacher\home@cancel_schedule');
            Route::post('/start', 'App\Http\Controllers\site\teacher\home@start_class');
        });

        Route::group(['prefix' => 'videos'], function(){
            Route::get('/', 'App\Http\Controllers\site\teacher\videos@videos');
            Route::post('add', 'App\Http\Controllers\site\teacher\videos@add_video');
            Route::post('cancel', 'App\Http\Controllers\site\teacher\videos@cancel_video');
        });

        Route::group(['prefix' => 'notifications'], function(){
            Route::get('/', 'App\Http\Controllers\site\teacher\notificaitons@index');
            Route::get('/pagination', 'App\Http\Controllers\site\teacher\notificaitons@index_pagination');
            Route::get('/notifications-count', 'App\Http\Controllers\site\teacher\notificaitons@notification_count');
        });

        Route::get('/years', 'App\Http\Controllers\site\teacher\home@teacher_years');


        Route::post('logout', 'App\Http\Controllers\site\teacher\authentication\auth@logout');
    });
});
