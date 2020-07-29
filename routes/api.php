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

Route::get("/test", function() {
    return json_encode([ "message" => "ok" ]);
});

Route::apiResource('students', 'StudentController');
Route::apiResource('courses', 'CourseController');
Route::apiResource('registrations', 'RegistrationController');


