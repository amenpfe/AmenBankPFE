<?php
use App\Http\Controllers\RequestController;

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
    Route::get('/adminedit', 'Admin\ManageUsersController@editProfil')->name('edit_admin');
    Route::post('/adminedit', 'Admin\ManageUsersController@editProfilPost')->name('submit_adminedit');
});

Route::group(['prefix' => 'user', 'middleware' => 'user'], function () {
    //Opt
    Route::get('/optform', 'RequestController@getUserOptRequestForm')->name('opt_form');
    Route::post('/optform', 'RequestController@submitOptimizationRequestForm')->name('add_opt_request');

    //New
    Route::get('/newform', 'RequestController@getUserNewProjectRequestForm');
    Route::post('/newform', 'RequestController@submitNewProjectRequestForm')->name('add_new_request');

    Route::get('/shownew', 'RequestController@getUserNewProjectRequests')->name('get_new');
    Route::get('/showopt', 'RequestController@getUserOptRequests')->name('get_opt');
    
    //Edit
    Route::get('/edituser', 'UserController@editProfilUser')->name('edit_user');
    Route::post('/edit', 'UserController@editProfilPostUser')->name('submit_edit');

    Route::get('/detail/{id}', 'RequestController@getUserNewDetails')->middleware('request_details')->name('detail_request');
});

Route::group(['prefix' => 'chd', 'middleware' => 'chd'], function () {
    Route::get('/shownew', 'RequestController@getCDNewProjectRequest')->name('get_chd_new');
    Route::get('/showopt', 'RequestController@getCDOptRequest')->name('get_chd_opt');

    Route::get('/detail/{id}', 'RequestController@getCDNewDetails')->middleware('request_details')->name('new-request-details-chd');
    Route::post('/detailsubmit', 'RequestController@submitCDNewRequestForm')->name('new-request-detail-chd-submit');

    Route::get('/detailopt/{id}', 'RequestController@getCDOptDetails')->middleware('request_details')->name('opt-request-details-chd');
    Route::post('/detailoptsubmit', 'RequestController@submitCDOptRequestForm')->name('opt-request-detail-chd-submit');
});

Route::group(['prefix' => 'ced', 'middleware' => 'ced'], function () {
    Route::get('/shownew', 'RequestController@getCEDNewProjectRequest')->name('get_ced_new');

    Route::get('/detail/{id}', 'RequestController@getCEDNewDetails')->middleware('request_details')->name('new-request-details-ced');
    Route::post('/detailsubmit', 'RequestController@submitCEDNewRequestForm')->name('new-request-detail-ced-submit');
});



/*Route::get('users/{role}', 'UserController@usersByRole');
Route::get('/detailopt/{id}', 'RequestController@getOptDetails')->middleware('request_details')->name('opt_detail_request');
*/



