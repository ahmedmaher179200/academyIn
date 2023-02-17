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
Route::get('/', function(){
    return redirect('/admins');
});

Route::group(
    [
        'prefix' => LaravelLocalization::setLocale(),
        'middleware' => [ 'localeSessionRedirect', 'localizationRedirect', 'localeViewPath' ]
    ], function(){

    Route::group(['prefix' => 'admins'], function(){
        Route::get('/login', 'App\Http\Controllers\admin\authentication@loginView')->name('adminlogin')->middleware('guest:admin');
        Route::post('/login', 'App\Http\Controllers\admin\authentication@login')->middleware('guest:admin');


        Route::group(['middleware' => 'auth:admin'], function () {
            Route::get('/', 'App\Http\Controllers\admin\dashbourd@index');
            Route::get('/logout', 'App\Http\Controllers\admin\authentication@logout');
            Route::get('/agora/join/{id}', 'App\Http\Controllers\admin\classes@join')->middleware('adminPermations:read-classes');

            
            Route::group(['prefix' => 'admins'],function(){
                Route::get('/', 'App\Http\Controllers\admin\admins@index')->middleware('adminPermations:read-admins');
                Route::get('/delete/{id}', 'App\Http\Controllers\admin\admins@delete')->middleware('adminPermations:delete-admins');
                Route::get('/create', 'App\Http\Controllers\admin\admins@createView')->middleware('adminPermations:create-admins');
                Route::post('/create', 'App\Http\Controllers\admin\admins@create')->middleware('adminPermations:create-admins');
                Route::get('/edit/{id}', 'App\Http\Controllers\admin\admins@editView')->middleware('adminPermations:update-admins');
                Route::post('/edit/{id}', 'App\Http\Controllers\admin\admins@edit')->middleware('adminPermations:update-admins');
            });

            Route::group(['prefix' => 'roles'],function(){
                Route::get('/', 'App\Http\Controllers\admin\roles@index')->middleware('adminPermations:read-roles');
                Route::get('/delete/{id}', 'App\Http\Controllers\admin\roles@delete')->middleware('adminPermations:delete-roles');
                Route::get('/create', 'App\Http\Controllers\admin\roles@createView')->middleware('adminPermations:create-roles');
                Route::post('/create', 'App\Http\Controllers\admin\roles@create')->middleware('adminPermations:create-roles');
                Route::get('/edit/{id}', 'App\Http\Controllers\admin\roles@editView')->middleware('adminPermations:update-roles');
                Route::post('/edit/{id}', 'App\Http\Controllers\admin\roles@edit')->middleware('adminPermations:update-roles');
            });

            Route::group(['prefix' => 'contact_us'], function(){
                Route::get('/', 'App\Http\Controllers\admin\contactUs@index');
                Route::get('/delete/{id}', 'App\Http\Controllers\admin\contactUs@destroy');
            });

            Route::group(['prefix' => 'countries'],function(){
                Route::get('/', 'App\Http\Controllers\admin\country@index')->middleware('adminPermations:read-countries');
                Route::get('/delete/{id}', 'App\Http\Controllers\admin\country@delete')->middleware('adminPermations:delete-countries');
                Route::get('/create', 'App\Http\Controllers\admin\country@createView')->middleware('adminPermations:create-countries');
                Route::post('/create', 'App\Http\Controllers\admin\country@create')->middleware('adminPermations:create-countries');
                Route::get('/edit/{id}', 'App\Http\Controllers\admin\country@editView')->middleware('adminPermations:update-countries');
                Route::post('/edit/{id}', 'App\Http\Controllers\admin\country@edit')->middleware('adminPermations:update-countries');
            });

            Route::group(['prefix' => 'main_subjects'],function(){
                Route::get('/', 'App\Http\Controllers\admin\main_subjects@index')->middleware('adminPermations:read-curriculums');
                Route::get('/delete/{id}', 'App\Http\Controllers\admin\main_subjects@delete')->middleware('adminPermations:delete-curriculums');
                Route::get('/create', 'App\Http\Controllers\admin\main_subjects@createView')->middleware('adminPermations:create-curriculums');
                Route::post('/create', 'App\Http\Controllers\admin\main_subjects@create')->middleware('adminPermations:create-curriculums');
                Route::get('/edit/{id}', 'App\Http\Controllers\admin\main_subjects@editView')->middleware('adminPermations:update-curriculums');
                Route::post('/edit/{id}', 'App\Http\Controllers\admin\main_subjects@edit')->middleware('adminPermations:update-curriculums');
            });

            Route::group(['prefix' => 'curriculums'],function(){
                Route::get('/', 'App\Http\Controllers\admin\curriculums@index')->middleware('adminPermations:read-curriculums');
                Route::get('/delete/{id}', 'App\Http\Controllers\admin\curriculums@delete')->middleware('adminPermations:delete-curriculums');
                Route::get('/create', 'App\Http\Controllers\admin\curriculums@createView')->middleware('adminPermations:create-curriculums');
                Route::post('/create', 'App\Http\Controllers\admin\curriculums@create')->middleware('adminPermations:create-curriculums');
                Route::get('/edit/{id}', 'App\Http\Controllers\admin\curriculums@editView')->middleware('adminPermations:update-curriculums');
                Route::post('/edit/{id}', 'App\Http\Controllers\admin\curriculums@edit')->middleware('adminPermations:update-curriculums');
            });

            Route::group(['prefix' => 'levels'],function(){
                Route::get('/', 'App\Http\Controllers\admin\levels@index')->middleware('adminPermations:read-curriculums');
                Route::get('/delete/{id}', 'App\Http\Controllers\admin\levels@delete')->middleware('adminPermations:delete-curriculums');
                Route::get('/create', 'App\Http\Controllers\admin\levels@createView')->middleware('adminPermations:create-curriculums');
                Route::post('/create', 'App\Http\Controllers\admin\levels@create')->middleware('adminPermations:create-curriculums');
                Route::get('/edit/{id}', 'App\Http\Controllers\admin\levels@editView')->middleware('adminPermations:update-curriculums');
                Route::post('/edit/{id}', 'App\Http\Controllers\admin\levels@edit')->middleware('adminPermations:update-curriculums');
            });

            Route::group(['prefix' => 'years'],function(){
                Route::get('/', 'App\Http\Controllers\admin\years@index')->middleware('adminPermations:read-curriculums');
                Route::get('/delete/{id}', 'App\Http\Controllers\admin\years@delete')->middleware('adminPermations:delete-curriculums');
                Route::get('/create', 'App\Http\Controllers\admin\years@createView')->middleware('adminPermations:create-curriculums');
                Route::post('/create', 'App\Http\Controllers\admin\years@create')->middleware('adminPermations:create-curriculums');
                Route::get('/edit/{id}', 'App\Http\Controllers\admin\years@editView')->middleware('adminPermations:update-curriculums');
                Route::post('/edit/{id}', 'App\Http\Controllers\admin\years@edit')->middleware('adminPermations:update-curriculums');
            });

            Route::group(['prefix' => 'terms'],function(){
                Route::get('/', 'App\Http\Controllers\admin\terms@index')->middleware('adminPermations:read-curriculums');
                Route::get('/delete/{id}', 'App\Http\Controllers\admin\terms@delete')->middleware('adminPermations:delete-curriculums');
                Route::get('/create', 'App\Http\Controllers\admin\terms@createView')->middleware('adminPermations:create-curriculums');
                Route::post('/create', 'App\Http\Controllers\admin\terms@create')->middleware('adminPermations:create-curriculums');
                Route::get('/edit/{id}', 'App\Http\Controllers\admin\terms@editView')->middleware('adminPermations:update-curriculums');
                Route::post('/edit/{id}', 'App\Http\Controllers\admin\terms@edit')->middleware('adminPermations:update-curriculums');
            });

            Route::group(['prefix' => 'terms'],function(){
                Route::get('/', 'App\Http\Controllers\admin\terms@index')->middleware('adminPermations:read-curriculums');
                Route::get('/delete/{id}', 'App\Http\Controllers\admin\terms@delete')->middleware('adminPermations:delete-curriculums');
                Route::get('/create', 'App\Http\Controllers\admin\terms@createView')->middleware('adminPermations:create-curriculums');
                Route::post('/create', 'App\Http\Controllers\admin\terms@create')->middleware('adminPermations:create-curriculums');
                Route::get('/edit/{id}', 'App\Http\Controllers\admin\terms@editView')->middleware('adminPermations:update-curriculums');
                Route::post('/edit/{id}', 'App\Http\Controllers\admin\terms@edit')->middleware('adminPermations:update-curriculums');
            });

            Route::group(['prefix' => 'subjects'],function(){
                Route::get('/', 'App\Http\Controllers\admin\subjects@index')->middleware('adminPermations:read-curriculums');
                Route::get('/delete/{id}', 'App\Http\Controllers\admin\subjects@delete')->middleware('adminPermations:delete-curriculums');
                Route::get('/create', 'App\Http\Controllers\admin\subjects@createView')->middleware('adminPermations:create-curriculums');
                Route::post('/create', 'App\Http\Controllers\admin\subjects@create')->middleware('adminPermations:create-curriculums');
                Route::get('/edit/{id}', 'App\Http\Controllers\admin\subjects@editView')->middleware('adminPermations:update-curriculums');
                Route::post('/edit/{id}', 'App\Http\Controllers\admin\subjects@edit')->middleware('adminPermations:update-curriculums');
            });

            Route::group(['prefix' => 'materials'],function(){
                Route::get('/', 'App\Http\Controllers\admin\materials@index')->middleware('adminPermations:read-curriculums');
                Route::get('/delete/{id}', 'App\Http\Controllers\admin\materials@delete')->middleware('adminPermations:delete-curriculums');
                Route::get('/create', 'App\Http\Controllers\admin\materials@createView')->middleware('adminPermations:create-curriculums');
                Route::post('/create', 'App\Http\Controllers\admin\materials@create')->middleware('adminPermations:create-curriculums');
                Route::get('/edit/{id}', 'App\Http\Controllers\admin\materials@editView')->middleware('adminPermations:update-curriculums');
                Route::post('/edit/{id}', 'App\Http\Controllers\admin\materials@edit')->middleware('adminPermations:update-curriculums');
            });

            Route::group(['prefix' => 'promo_codes'],function(){
                Route::get('/', 'App\Http\Controllers\admin\promo_codes@index')->middleware('adminPermations:read-promo_codes');
                Route::get('/create', 'App\Http\Controllers\admin\promo_codes@createView')->middleware('adminPermations:create-promo_codes');
                Route::post('/create', 'App\Http\Controllers\admin\promo_codes@create')->middleware('adminPermations:create-promo_codes');
                Route::get('/delete/{id}', 'App\Http\Controllers\admin\promo_codes@delete')->middleware('adminPermations:delete-promo_codes');
                Route::get('/edit/{id}', 'App\Http\Controllers\admin\promo_codes@editView')->middleware('adminPermations:update-promo_codes');
                Route::post('/edit/{id}', 'App\Http\Controllers\admin\promo_codes@edit')->middleware('adminPermations:update-promo_codes');
            });
            
            Route::group(['prefix' => 'questions'],function(){
                Route::get('/', 'App\Http\Controllers\admin\questions@index')->middleware('adminPermations:read-questions');
                Route::get('/delete/{id}', 'App\Http\Controllers\admin\questions@delete')->middleware('adminPermations:delete-questions');
            });

            Route::group(['prefix' => 'answers'],function(){
                Route::get('/', 'App\Http\Controllers\admin\answers@index')->middleware('adminPermations:read-questions');
                Route::get('/delete/{id}', 'App\Http\Controllers\admin\answers@delete')->middleware('adminPermations:delete-questions');
            });

            Route::group(['prefix' => 'students'],function(){
                Route::get('/', 'App\Http\Controllers\admin\students@index')->middleware('adminPermations:read-students');
                Route::get('/block/{id}', 'App\Http\Controllers\admin\students@block')->middleware('adminPermations:delete-students');
            });

            Route::group(['prefix' => 'teachers'],function(){
                Route::get('/', 'App\Http\Controllers\admin\teachers@index')->middleware('adminPermations:read-teachers');
                Route::get('/block/{id}', 'App\Http\Controllers\admin\teachers@block')->middleware('adminPermations:delete-teachers');
            });

            Route::group(['prefix' => 'levels_cost'],function(){
                Route::get('/', 'App\Http\Controllers\admin\levels_cost@index')->middleware('adminPermations:read-class_types');
                Route::get('/create', 'App\Http\Controllers\admin\levels_cost@createView')->middleware('adminPermations:create-class_types');
                Route::post('/create', 'App\Http\Controllers\admin\levels_cost@create')->middleware('adminPermations:create-class_types');
                Route::get('/delete/{id}', 'App\Http\Controllers\admin\levels_cost@delete')->middleware('adminPermations:delete-class_types');
                Route::get('/edit/{id}', 'App\Http\Controllers\admin\levels_cost@editView')->middleware('adminPermations:update-class_types');
                Route::post('/edit/{id}', 'App\Http\Controllers\admin\levels_cost@edit')->middleware('adminPermations:update-class_types');
            });

            Route::group(['prefix' => 'years_cost'],function(){
                Route::get('/', 'App\Http\Controllers\admin\years_cost@index')->middleware('adminPermations:read-class_types');
                Route::get('/create', 'App\Http\Controllers\admin\years_cost@createView')->middleware('adminPermations:create-class_types');
                Route::post('/create', 'App\Http\Controllers\admin\years_cost@create')->middleware('adminPermations:create-class_types');
                Route::get('/delete/{id}', 'App\Http\Controllers\admin\years_cost@delete')->middleware('adminPermations:delete-class_types');
                Route::get('/edit/{id}', 'App\Http\Controllers\admin\years_cost@editView')->middleware('adminPermations:update-class_types');
                Route::post('/edit/{id}', 'App\Http\Controllers\admin\years_cost@edit')->middleware('adminPermations:update-class_types');
            });

            Route::group(['prefix' => 'countries_cost'],function(){
                Route::get('/', 'App\Http\Controllers\admin\countries_cost@index')->middleware('adminPermations:read-class_types');
                Route::get('/create', 'App\Http\Controllers\admin\countries_cost@createView')->middleware('adminPermations:create-class_types');
                Route::post('/create', 'App\Http\Controllers\admin\countries_cost@create')->middleware('adminPermations:create-class_types');
                Route::get('/delete/{id}', 'App\Http\Controllers\admin\countries_cost@delete')->middleware('adminPermations:delete-class_types');
                Route::get('/edit/{id}', 'App\Http\Controllers\admin\countries_cost@editView')->middleware('adminPermations:update-class_types');
                Route::post('/edit/{id}', 'App\Http\Controllers\admin\countries_cost@edit')->middleware('adminPermations:update-class_types');
            });

            Route::group(['prefix' => 'company_percentages'],function(){
                Route::get('/', 'App\Http\Controllers\admin\company_percentages@index')->middleware('adminPermations:read-class_types');
                Route::get('/create', 'App\Http\Controllers\admin\company_percentages@createView')->middleware('adminPermations:create-class_types');
                Route::post('/create', 'App\Http\Controllers\admin\company_percentages@create')->middleware('adminPermations:create-class_types');
                Route::get('/delete/{id}', 'App\Http\Controllers\admin\company_percentages@delete')->middleware('adminPermations:delete-class_types');
                Route::get('/edit/{id}', 'App\Http\Controllers\admin\company_percentages@editView')->middleware('adminPermations:update-class_types');
                Route::post('/edit/{id}', 'App\Http\Controllers\admin\company_percentages@edit')->middleware('adminPermations:update-class_types');
            });

            Route::group(['prefix' => 'students_numbers_cost'],function(){
                Route::get('/', 'App\Http\Controllers\admin\students_numbers_cost@index')->middleware('adminPermations:read-class_types');
                Route::get('/create', 'App\Http\Controllers\admin\students_numbers_cost@createView')->middleware('adminPermations:create-class_types');
                Route::post('/create', 'App\Http\Controllers\admin\students_numbers_cost@create')->middleware('adminPermations:create-class_types');
                Route::get('/delete/{id}', 'App\Http\Controllers\admin\students_numbers_cost@delete')->middleware('adminPermations:delete-class_types');
                Route::get('/edit/{id}', 'App\Http\Controllers\admin\students_numbers_cost@editView')->middleware('adminPermations:update-class_types');
                Route::post('/edit/{id}', 'App\Http\Controllers\admin\students_numbers_cost@edit')->middleware('adminPermations:update-class_types');
            });

            Route::group(['prefix' => 'settings'],function(){
                Route::get('/edit', 'App\Http\Controllers\admin\settings@editView')->middleware('adminPermations:update-settings');
                Route::post('/edit', 'App\Http\Controllers\admin\settings@edit')->middleware('adminPermations:update-settings');
            });

            Route::group(['prefix' => 'class_types'],function(){
                Route::get('/', 'App\Http\Controllers\admin\class_types@index')->middleware('adminPermations:read-class_types');
                Route::get('/create', 'App\Http\Controllers\admin\class_types@createView')->middleware('adminPermations:create-class_types');
                Route::post('/create', 'App\Http\Controllers\admin\class_types@create')->middleware('adminPermations:create-class_types');
                Route::get('/delete/{id}', 'App\Http\Controllers\admin\class_types@delete')->middleware('adminPermations:delete-class_types');
                Route::get('/edit/{id}', 'App\Http\Controllers\admin\class_types@editView')->middleware('adminPermations:update-class_types');
                Route::post('/edit/{id}', 'App\Http\Controllers\admin\class_types@edit')->middleware('adminPermations:update-class_types');
            });

            Route::group(['prefix' => 'offers'],function(){
                Route::get('/', 'App\Http\Controllers\admin\offers@index')->middleware('adminPermations:read-class_types');
                Route::get('/delete/{id}', 'App\Http\Controllers\admin\offers@delete')->middleware('adminPermations:delete-class_types');
                Route::get('/create', 'App\Http\Controllers\admin\offers@createView')->middleware('adminPermations:create-class_types');
                Route::post('/create', 'App\Http\Controllers\admin\offers@create')->middleware('adminPermations:create-class_types');
                Route::get('/edit/{id}', 'App\Http\Controllers\admin\offers@editView')->middleware('adminPermations:update-class_types');
                Route::post('/edit/{id}', 'App\Http\Controllers\admin\offers@edit')->middleware('adminPermations:update-class_types');
            });

            Route::group(['prefix' => 'classes'],function(){
                Route::get('/', 'App\Http\Controllers\admin\classes@index')->middleware('adminPermations:read-classes');
                Route::get('/delete/{id}', 'App\Http\Controllers\admin\classes@delete')->middleware('adminPermations:delete-classes');
            });
        });
    });

});
