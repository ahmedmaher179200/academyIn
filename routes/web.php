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

Route::group(['middleware' => ['changeLang'] ,'prefix' => 'guest'], function(){
    Route::get('/teachers_search', 'App\Http\Controllers\site\guest\search@search');

    Route::get('/Terms_and_Conditions', 'App\Http\Controllers\site\guest\home@Terms_and_Conditions');

    Route::get('/level_year', 'App\Http\Controllers\site\guest\search@level_year');
    Route::get('/level_year_subjects', 'App\Http\Controllers\site\guest\search@level_year_subjects');

    Route::get('/subjects_year', 'App\Http\Controllers\site\guest\search@subjects_year');
    Route::get('/subjects', 'App\Http\Controllers\site\guest\home@main_subjects');
    Route::get('/all-subjects', 'App\Http\Controllers\site\guest\home@subjects');

    Route::get('/teacher-answers', 'App\Http\Controllers\site\teacher\answers@teacher_answers');
    Route::get('/teachers-answers-questions', 'App\Http\Controllers\site\teacher\questions@myAnswersQuestions');


    //get teacher by subject_id
    Route::get('/teachers', 'App\Http\Controllers\site\guest\home@teachersBysubject');
    Route::get('/onlineTeachers', 'App\Http\Controllers\site\guest\home@online_teachers_bysubject');


    Route::get('/classes_types_cost', 'App\Http\Controllers\site\guest\home@classes_type_cost');

    Route::get('/countries', 'App\Http\Controllers\site\guest\home@countries');
    Route::get('/curriculums', 'App\Http\Controllers\site\guest\home@curriculums');

    Route::get('/answers', 'App\Http\Controllers\site\guest\home@answers');
    Route::get('/questions', 'App\Http\Controllers\site\guest\home@questions');


    Route::get('student/profile', 'App\Http\Controllers\site\student\authentication\ProfileController@index');
    Route::get('teacher/profile', 'App\Http\Controllers\site\teacher\authentication\profile@index');

    Route::get('/materials', 'App\Http\Controllers\site\guest\home@materials');
    Route::post('/contact_us', 'App\Http\Controllers\site\guest\home@contact_us');

    Route::get('/test', 'App\Http\Controllers\Controller@test');

});
