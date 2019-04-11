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
    return view('welcome');
});

Auth::routes();

//Route::get('/logout', '\App\Http\Controllers\Auth\LoginController@logout');

Route::get('/home/{name}/{email}/{password}', 'HomeController@addUser');

Route::get('/home', 'HomeController@index')->name('home');

Route::group(['prefix' => 'admin', 'middleware' => 'admin'], function () {
    Route::get('users', 'Admin\ManageUsersController@manage')->name('manage_users');
    Route::post('users/delete', 'Admin\ManageUsersController@delete')->name('delete_user');
    Route::post('users/update', 'Admin\ManageUsersController@update')->name('update_user');
    Route::get('add', 'Admin\ManageUsersController@add')->name('add_users');
    Route::post('add/register', array('uses'=>'Admin\ManageUsersController@addUser'))->name('add_user');
});

Route::get('/edit', 'Admin\ManageUsersController@editProfil')->middleware('auth');
Route::post('/edit', 'Admin\ManageUsersController@editProfilPost')->middleware('auth')->name('user_edit');


Route::group(['prefix' => 'user', 'middleware' => 'user'], function () {
    Route::get('/optform', 'UserController@getOptimizationRequestForm')->name('opt_form');
    Route::post('/optform', 'UserController@submitOptimizationRequestForm')->name('add_opt_request');
    Route::get('/newform', 'UserController@getNewProjectRequestForm');
    Route::post('/newform', 'UserController@submitNewProjectRequestForm')->name('add_new_request');
    Route::get('/shownew', 'UserController@getNewProjectRequests')->name('get_new');
    Route::get('/showopt', 'UserController@getOptRequests')->name('get_opt');
});

Route::get('users/{role}', 'UserController@usersByRole');


