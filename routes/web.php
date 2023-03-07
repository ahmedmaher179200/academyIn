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
    //teacher
    Route::get('/teachers', 'App\Http\Controllers\site\guest\TeacherController@teachersBysubject');
    Route::get('/onlineTeachers', 'App\Http\Controllers\site\guest\TeacherController@online_teachers_bysubject');
    Route::get('teacher/profile', 'App\Http\Controllers\site\guest\TeacherController@show');
    Route::get('/teachers_search', 'App\Http\Controllers\site\guest\TeacherController@index');
    Route::get('/teachers-answers-questions', 'App\Http\Controllers\site\teacher\QuestionController@myAnswersQuestions');

    //contact us
    Route::post('/contact_us', 'App\Http\Controllers\site\guest\ContactUsController@create');

    //subjects
    Route::get('/subjects', 'App\Http\Controllers\site\guest\SubjectController@index');
    Route::get('/subjects_year', 'App\Http\Controllers\site\guest\SubjectController@subjects_year');
    Route::get('/materials', 'App\Http\Controllers\site\guest\SubjectController@materials');

    //stduent
    Route::get('student/profile', 'App\Http\Controllers\site\guest\StudentController@show');

    //home
    Route::get('/countries', 'App\Http\Controllers\site\guest\HomeController@countries');
    Route::get('/answers', 'App\Http\Controllers\site\guest\HomeController@answers');
    Route::get('/questions', 'App\Http\Controllers\site\guest\HomeController@questions');
    Route::get('/curriculums', 'App\Http\Controllers\site\guest\HomeController@curriculums');
    Route::get('/level_year', 'App\Http\Controllers\site\guest\HomeController@level_year');
    Route::get('/level_year_subjects', 'App\Http\Controllers\site\guest\HomeController@level_year_subjects');
    Route::get('/classes_types_cost', 'App\Http\Controllers\site\guest\HomeController@classes_type_cost');
    Route::get('/Terms_and_Conditions', 'App\Http\Controllers\site\guest\HomeController@Terms_and_Conditions');
});
