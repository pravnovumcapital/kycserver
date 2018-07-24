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
Route::post('/register', 'UserController@create');
Route::post('/login/account', 'UserController@login');
Route::post('/upload/passport', 'UserController@uploadPassport');
Route::get('/send-otp', 'UserController@sendVerificationSms')->name('user.register');
Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
Route::prefix('otp/')->group(function () {
    Route::post('send', 'UserController@startVerification');
    Route::post('verify', 'UserController@verifyCode');
});
Route::get('/citizenship/list', 'DashboardController@citizenshipList');
