<?php

use Illuminate\Http\Request;

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


// auth
Route::post('/sign-in', 'UserController@signIn');
Route::post('/register', 'UserController@register');
Route::post('/forgot-password', 'UserController@resetPassword');
Route::post('/update-password', 'UserController@updatePassword');
Route::post('/reset-password-otp', 'UserController@updatePasswordWithOtp');

// jobs
Route::get('/jobs', 'JobController@index');
Route::post('/apply/{slug}', 'JobController@apply');
Route::patch('/undo-application/{slug}', 'JobController@revertApply');
Route::post('/create-job', 'JobController@create');
Route::patch('/update-job/{slug}', 'JobController@update');
Route::delete('/delete-job/{slug}', 'JobController@delete');
Route::get('/view-applied', 'JobController@applied');
Route::get('/view-created', 'JobController@created');

