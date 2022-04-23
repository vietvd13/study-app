<?php
use Illuminate\Support\Facades\Route;
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
Route::namespace('Api')->group(function() {
    Route::post('/auth/login','AuthController@login');
    Route::middleware(['auth:sanctum'])->group(function () {
        Route::get('/auth/user','AuthController@user');
        Route::apiResource('/users', 'UserController');
        Route::get('/roles', 'UserController@roles');

        Route::apiResource('/classes', 'ClassController');
        Route::post('/classes/students', 'ClassController@AddStudent');
        Route::post('/classes/courses', 'ClassController@AddCourses');
        Route::get('user/students', 'UserController@students');
        Route::get('user/teacher', 'UserController@teacher');
        Route::get('/classes/student/list', 'ClassController@getClassByStudent');
        Route::get('class/teacher/list', 'ClassController@getClassByTeacher');

        Route::prefix('class/action')->group(function () {
            Route::post('create-action', 'ClassController@ClassAction');
            Route::post('student/handin', 'ClassController@ActionHandin');
            Route::post('teacher/grade', 'ClassController@ActionGrading');
            Route::get('teacher/handin', 'ClassController@GetAllHandinTeacher');
            Route::get('teacher/actions', 'ClassController@ListActionInClass');
            Route::get('student/actions', 'ClassController@ListClassStudent');
            Route::get('student/action-detail', 'ClassController@studentGetActionDetail');
        });

        Route::apiResource('courses', 'CourseController');

        Route::group(['prefix' => 'course'], function () {
            Route::post('add-teacher', 'CourseController@AddTeacher');
            Route::post('add-document', 'CourseController@courseDocuments');
            Route::delete('delete-document', 'CourseController@deleteDocument');
            Route::get('download', 'CourseController@downloadDocument');
        });

        Route::group(['prefix' => 'test'], function () {
            Route::post('import', 'TestController@importTest');
        });
    });
});
