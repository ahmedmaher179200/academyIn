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
Route::post('/balanceCharging/{student_id}', 'App\Http\Controllers\site\student\BalanceChargerController@balance_charging');
Route::post('/payment/return', 'App\Http\Controllers\site\student\BalanceChargerController@payment_return');

Route::group(['middleware' => ['changeLang'] ,'prefix' => 'students'], function(){
    Route::post('/payment/check', 'App\Http\Controllers\site\student\BalanceChargerController@payment_check');

    Route::post('/whiteboard/token', 'App\Http\Controllers\site\student\home@whiteboard');

    Route::get('/', 'App\Http\Controllers\Controller@test');
    
    Route::post('login', 'App\Http\Controllers\site\student\authentication\AuthController@login');
    Route::post('register', 'App\Http\Controllers\site\student\authentication\AuthController@register');

    Route::post('get_dialing_code', 'App\Http\Controllers\site\student\authentication\AuthController@get_dialing_code');
    Route::group(['prefix' => 'passwordReset'], function(){
        Route::post('/', 'App\Http\Controllers\site\student\authentication\resetPasswored@new_resetPassword')->middleware('checkJWTToken:student');
        Route::post('checkCode', 'App\Http\Controllers\site\student\authentication\resetPasswored@checkCode');
        Route::post('sendCode', 'App\Http\Controllers\site\student\authentication\resetPasswored@sendCode');
    });

    Route::get('/offers', 'App\Http\Controllers\site\student\OfferController@index');
    Route::post('generate_agora_rtm_token', 'App\Http\Controllers\site\student\home@generate_agora_rtm_token')->middleware('checkJWTToken:student');

    Route::group(['middleware' => 'checkJWTToken:student'], function(){
        Route::group(['prefix' => 'verification'], function(){
            Route::post('/', 'App\Http\Controllers\site\student\authentication\verification@new_verification');
            Route::post('sendCode', 'App\Http\Controllers\site\student\authentication\verification@sendCode');
        });

        Route::post('/rating/add', 'App\Http\Controllers\site\student\home@add_rating');

        Route::group(['prefix' => 'myProfile'], function(){
            Route::get('/', 'App\Http\Controllers\site\student\authentication\ProfileController@myProfile');
            Route::post('/setup_profile', 'App\Http\Controllers\site\student\authentication\ProfileController@updateYear');
            Route::post('changePassword', 'App\Http\Controllers\site\student\authentication\ProfileController@changePassword');
            Route::post('changeImage', 'App\Http\Controllers\site\student\authentication\ProfileController@changeImage');
            Route::post('update', 'App\Http\Controllers\site\student\authentication\ProfileController@updateProfile');
        });

        Route::group(['prefix' => 'questions'], function(){
            Route::get('/', 'App\Http\Controllers\site\student\QuestionController@index');
            Route::get('/my-question', 'App\Http\Controllers\site\student\QuestionController@myQuestion');
            Route::post('/create', 'App\Http\Controllers\site\student\QuestionController@create');
            Route::post('/delete', 'App\Http\Controllers\site\student\QuestionController@delete');
            Route::post('/edit', 'App\Http\Controllers\site\student\QuestionController@update');
        });

        Route::group(['prefix' => 'answers'], function(){
            Route::get('/', 'App\Http\Controllers\site\student\AnswerController@index');
            Route::post('/create', 'App\Http\Controllers\site\student\AnswerController@create');
            Route::post('/delete', 'App\Http\Controllers\site\student\AnswerController@delete');
            Route::post('/edit', 'App\Http\Controllers\site\student\AnswerController@update');
        });

        Route::group(['prefix' => 'offers'], function(){
            Route::post('/take', 'App\Http\Controllers\site\student\OfferController@take_offer');;
        });

        Route::group(['prefix' => 'notifications'], function(){
            Route::get('/', 'App\Http\Controllers\site\student\NotificaitonController@index');
            Route::get('/notifications-count', 'App\Http\Controllers\site\student\NotificaitonController@notification_count');
        });

        Route::group(['prefix' => 'schedules'], function(){
            Route::get('/', 'App\Http\Controllers\site\student\home@schedule');
            Route::post('/cancel', 'App\Http\Controllers\site\student\home@cancel_schedule');
        });

        Route::group(['prefix' => 'balance-charger'], function(){
            Route::post('/request', 'App\Http\Controllers\site\student\BalanceChargerController@payment_request')->middleware('checkJWTToken:student');
        });

        Route::get('/home', 'App\Http\Controllers\site\student\home@index');

        Route::post('/reservations', 'App\Http\Controllers\site\student\home@my_reservations');

        Route::post('/available_classes', 'App\Http\Controllers\site\student\home@available_classes');
        Route::post('/booking', 'App\Http\Controllers\site\student\home@booking');
        Route::post('/buy/video', 'App\Http\Controllers\site\student\home@buy_video');

        Route::post('leave', 'App\Http\Controllers\site\student\home@leave');

        Route::post('logout', 'App\Http\Controllers\site\student\authentication\auth@logout');
    });
});
Route::get('test', 'App\Http\Controllers\site\student\home@test');
