<?php

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

Route::get('/', function () {
    return redirect('admin');
});

Route::group(['prefix' => 'admin'], function () {
  Route::get('/', function () {
     return redirect('admin/login');
  });
  // Route::get('/home', function () {
  //     return view('admin.dashboard');
  // })->name('admin.home');
  // Route::get('/dashboard', function () {
  //     return view('admin.dashboard');
  // })->name('admin.dashboard');
  Route::get('/dashboard', 'Admin\DashboardController@index')->name('admin.dashboard');
  Route::get('/home', 'Admin\DashboardController@index')->name('admin.home');
  Route::get('/login', 'AdminAuth\LoginController@showLoginForm')->name('login');
  Route::post('/login', 'AdminAuth\LoginController@login');
  Route::get('/logout', 'AdminAuth\LoginController@logout')->name('logout');

  Route::get('/register', 'AdminAuth\RegisterController@showRegistrationForm')->name('register');
  Route::post('/register', 'AdminAuth\RegisterController@register');

  Route::post('/password/email', 'AdminAuth\ForgotPasswordController@sendResetLinkEmail')->name('password.request');
  Route::post('/password/reset', 'AdminAuth\ResetPasswordController@reset')->name('password.email');
  Route::get('/password/reset', 'AdminAuth\ForgotPasswordController@showLinkRequestForm')->name('password.reset');
  Route::get('/password/reset/{token}', 'AdminAuth\ResetPasswordController@showResetForm');

  Route::get('/users/list/{count?}', 'Admin\UserController@userlist')->name('admin.users');
  Route::get('/users/verified/{count?}', 'Admin\UserController@userVerifiedList')->name('admin.users.verified');
  Route::get('/users/pending/{count?}', 'Admin\UserController@userPendingList')->name('admin.users.pending');
  Route::post('/users/delete/{id}', 'Admin\UserController@deleteUser')->name('admin.users.delete');

  //project
  Route::get('/project/create', 'Admin\ProjectController@create')->name('admin.project.create');
  Route::post('/project/create', 'Admin\ProjectController@store')->name('admin.project.store');
  Route::post('/project/update', 'Admin\ProjectController@update')->name('admin.project.update');
  Route::get('/project/lists', 'Admin\ProjectController@listProjects')->name('admin.project.list');
  Route::get('/project/edit/{id}', 'Admin\ProjectController@edit')->name('admin.project.edit');
  Route::post('/project/delete/{id}', 'Admin\ProjectController@deleteProject')->name('admin.project.delete');

  Route::get('/coin/list', 'Admin\ProjectController@listCoin')->name('admin.coin.list');
  Route::get('/coin/create', 'Admin\ProjectController@createCoin')->name('admin.coin.create');
  Route::post('/coin/store', 'Admin\ProjectController@storeCoin')->name('admin.coin.store');
  Route::post('/coin/update', 'Admin\ProjectController@updateCoin')->name('admin.coin.update');

});
