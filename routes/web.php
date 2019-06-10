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
    Route::get('/edit', 'UserController@editProfilAdmin')->name('edit_admin');
    Route::post('/edit', 'UserController@editProfilPostAdmin')->name('submit_adminedit');
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
    Route::get('/detailopt/{id}', 'RequestController@getUserOptDetails')->middleware('request_details')->name('opt_detail_request');
});

Route::group(['prefix' => 'chd', 'middleware' => 'chd'], function () {
    Route::get('/shownew', 'RequestController@getCDNewProjectRequest')->name('get_chd_new');
    Route::get('/showopt', 'RequestController@getCDOptRequest')->name('get_chd_opt');

    Route::get('/detail/{id}', 'RequestController@getCDNewDetails')->middleware('request_details')->name('new-request-details-chd');
    Route::post('/detailsubmit', 'RequestController@submitCDNewRequestForm')->name('new-request-detail-chd-submit');

    Route::get('/detailopt/{id}', 'RequestController@getCDOptDetails')->middleware('request_details')->name('opt-request-details-chd');
    Route::post('/detailoptsubmit', 'RequestController@submitCDOptRequestForm')->name('opt-request-detail-chd-submit');

    Route::get('/allnew', 'RequestController@getCDAllNewProjectRequests')->name('all_new_request_chd');
    Route::get('/allopt', 'RequestController@getCDAllOptRequests')->name('all_opt_request_chd');

    Route::get('/edit', 'UserController@getEditProfilChd')->name('edit_chd');
    Route::post('/edit', 'UserController@editProfilChdSubmit')->name('submit_chd_edit');

    Route::get('/archiveOpt', 'RequestController@getCDOptArchive')->name('get_chd_opt_archive');
    Route::get('/archiveOptDetails{id}', 'RequestController@getCDOptArchiveDetails')->name('get_chd_opt_archive_details');

    Route::get('/archiveNew', 'RequestController@getCDNewArchive')->name('get_chd_new_archive');
    Route::get('/archiveNewDetails{id}', 'RequestController@getCDNewArchiveDetails')->name('get_chd_new_archive_details');

    Route::get('/stat', 'RequestController@getStatChd')->name('get_chd_stat');

});

Route::group(['prefix' => 'ced', 'middleware' => 'ced'], function () {
    Route::get('/shownew', 'RequestController@getCEDNewProjectRequest')->name('get_ced_new');
    Route::get('/showopt', 'RequestController@getCEDOptRequest')->name('get_ced_opt');

    Route::get('/detail/{id}', 'RequestController@getCEDNewDetails')->middleware('request_details')->name('new-request-details-ced');
    Route::post('/detailsubmit', 'RequestController@submitCEDNewRequestForm')->name('new-request-detail-ced-submit');

    Route::get('/detailopt/{id}', 'RequestController@getCEDOptDetails')->middleware('request_details')->name('opt-request-details-ced');
    Route::post('/detailoptsubmit', 'RequestController@submitCEDOptRequestForm')->name('opt-request-detail-ced-submit');

    Route::get('/allnew', 'RequestController@getCEDAllNewProjectRequests')->name('all_new_request_ced');
    Route::get('/allopt', 'RequestController@getCEDAllOptRequests')->name('all_opt_request_ced');

    Route::get('/edit', 'UserController@getEditProfilCED')->name('edit_ced');
    Route::post('/edit', 'UserController@editProfilCEDSubmit')->name('submit_ced_edit');

    Route::get('/archiveOpt', 'RequestController@getCEDOptArchive')->name('get_ced_opt_archive');
    Route::get('/archiveOptDetails{id}', 'RequestController@getCEDOptArchiveDetails')->name('get_ced_opt_archive_details');

    Route::get('/archiveNew', 'RequestController@getCEDNewArchive')->name('get_ced_new_archive');
    Route::get('/archiveNewDetails{id}', 'RequestController@getCEDNewArchiveDetails')->name('get_ced_new_archive_details');
    
    Route::get('/stat', 'RequestController@getStatCed')->name('get_ced_stat');

});

Route::group(['prefix' => 'prop', 'middleware' => 'prop'], function () {
    Route::get('/showopt', 'RequestController@getPropOptRequest')->name('get_prop_opt');

    Route::get('/detailopt/{id}', 'RequestController@getPropOptDetails')->middleware('request_details')->name('opt-request-details-prop');
    Route::any('/detailaccept/{id}', 'RequestController@AcceptOptRequest')->name('accept_request');
    Route::any('/detailrefu/{id}', 'RequestController@RefuseOptRequest')->name('refuse_request');

    Route::get('/allnew', 'RequestController@getPropAllNewProjectRequests')->name('all_new_request_prop');
    Route::get('/allopt', 'RequestController@getPropAllOptRequests')->name('all_opt_request_prop');

    Route::get('/edit', 'UserController@getEditProfilProp')->name('edit_prop');
    Route::post('/edit', 'UserController@editProfilPropSubmit')->name('submit_prop_edit');

    Route::get('/archiveOpt', 'RequestController@getPropOptArchive')->name('get_prop_opt_archive');
    Route::get('/archiveOptDetails{id}', 'RequestController@getPropOptArchiveDetails')->name('get_prop_opt_archive_details');

    Route::get('/archiveNew', 'RequestController@getPropNewArchive')->name('get_prop_new_archive');
    Route::get('/archiveNewDetails{id}', 'RequestController@getPropNewArchiveDetails')->name('get_prop_new_archive_details');

    Route::get('/stat', 'RequestController@getStatProp')->name('get_prop_stat');
});

Route::group(['prefix' => 'cdd', 'middleware' => 'cdd'], function () {
    Route::get('/shownew', 'RequestController@getCDDNewProjectRequest')->name('get_cdd_new');
    Route::get('/showopt', 'RequestController@getCDDOptRequest')->name('get_cdd_opt');

    Route::get('/archiveOpt', 'RequestController@getCDDOptArchive')->name('get_cdd_opt_archive');
    Route::get('/archiveOptDetails{id}', 'RequestController@getCDDOptArchiveDetails')->name('get_cdd_opt_archive_details');

    Route::get('/archiveNew', 'RequestController@getCDDNewArchive')->name('get_cdd_new_archive');
    Route::get('/archiveNewDetails{id}', 'RequestController@getCDDNewArchiveDetails')->name('get_cdd_new_archive_details');

    Route::get('/detail/{id}', 'RequestController@getCDDNewDetails')->middleware('request_details')->name('new-request-details-cdd');
    Route::post('/detailsubmit', 'RequestController@submitCDDNewRequestForm')->name('new-request-detail-cdd-submit');

    Route::get('/detailopt/{id}', 'RequestController@getCDDOptDetails')->middleware('request_details')->name('opt-request-details-cdd');
    Route::post('/detailoptsubmit', 'RequestController@submitCDDOptRequestForm')->name('opt-request-detail-cdd-submit');

    Route::get('/allnew', 'RequestController@getCDDAllNewProjectRequests')->name('all_new_request_cdd');
    Route::get('/allopt', 'RequestController@getCDDAllOptRequests')->name('all_opt_request_cdd');

    Route::get('/edit', 'UserController@getEditProfilCDD')->name('edit_cdd');
    Route::post('/edit', 'UserController@editProfilCDDSubmit')->name('submit_cdd_edit');

});

Route::group(['prefix' => 'org', 'middleware' => 'org'], function () {
    Route::get('/shownew', 'RequestController@getORGNewProjectRequest')->name('get_org_new');
    Route::get('/showopt', 'RequestController@getORGOptRequest')->name('get_org_opt');

    Route::get('/detail/{id}', 'RequestController@getORGNewDetails')->middleware('request_details')->name('new-request-details-org');
    Route::post('/detailsubmit', 'RequestController@submitORGNewRequestForm')->name('new-request-detail-org-submit');

    Route::get('/detailopt/{id}', 'RequestController@getORGOptDetails')->middleware('request_details')->name('opt-request-details-org');
    Route::post('/detailoptsubmit', 'RequestController@submitORGOptRequestForm')->name('opt-request-detail-org-submit');

    Route::get('/allnew', 'RequestController@getORGAllNewProjectRequests')->name('all_new_request_org');
    Route::get('/allopt', 'RequestController@getORGAllOptRequests')->name('all_opt_request_org');

    Route::get('/edit', 'UserController@getEditProfilORG')->name('edit_org');
    Route::post('/edit', 'UserController@editProfilORGSubmit')->name('submit_org_edit');

    Route::get('/archiveOpt', 'RequestController@getORGOptArchive')->name('get_org_opt_archive');
    Route::get('/archiveOptDetails{id}', 'RequestController@getORGOptArchiveDetails')->name('get_org_opt_archive_details');

    Route::get('/archiveNew', 'RequestController@getORGNewArchive')->name('get_org_new_archive');
    Route::get('/archiveNewDetails{id}', 'RequestController@getORGNewArchiveDetails')->name('get_org_new_archive_details');

    Route::get('/stat', 'RequestController@getStatOrg')->name('get_org_stat');


});

Route::group(['prefix' => 'ds', 'middleware' => 'ds'], function () {
    Route::get('/shownew', 'RequestController@getDSNewProjectRequest')->name('get_ds_new');
    Route::get('/showopt', 'RequestController@getDSOptRequest')->name('get_ds_opt');

    Route::get('/detail/{id}', 'RequestController@getDSNewDetails')->middleware('request_details')->name('new-request-details-ds');
    Route::any('/detailsubmit/{id}', 'RequestController@submitDSNewRequestForm')->name('mail_new_request');

    Route::get('/detailopt/{id}', 'RequestController@getDSOptDetails')->middleware('request_details')->name('opt-request-details-ds');
    Route::any('/detailoptsubmit/{id}', 'RequestController@submitDSOptRequestForm')->name('mail_opt_request');

    Route::get('/allnew', 'RequestController@getDSAllNewProjectRequests')->name('all_new_request_ds');
    Route::get('/allopt', 'RequestController@getDSAllOptRequests')->name('all_opt_request_ds');

    Route::get('/edit', 'UserController@getEditProfilDS')->name('edit_ds');
    Route::post('/edit', 'UserController@editProfilDSSubmit')->name('submit_ds_edit');

    Route::get('/archiveOpt', 'RequestController@getDSOptArchive')->name('get_ds_opt_archive');
    Route::get('/archiveOptDetails{id}', 'RequestController@getDSOptArchiveDetails')->name('get_ds_opt_archive_details');

    Route::get('/archiveNew', 'RequestController@getDSNewArchive')->name('get_ds_new_archive');
    Route::get('/archiveNewDetails{id}', 'RequestController@getDSNewArchiveDetails')->name('get_ds_new_archive_details');

    Route::get('/stat', 'RequestController@getStatDs')->name('get_ds_stat');

});

Route::group(['prefix' => 'cdq', 'middleware' => 'cdq'], function () {

    Route::get('/shownew', 'RequestController@getCDQNewProjectRequest')->name('get_cdq_new');
    Route::get('/showopt', 'RequestController@getCDQOptRequest')->name('get_cdq_opt');

    Route::get('/detail/{id}', 'RequestController@getCDQNewDetails')->middleware('request_details')->name('new-request-details-cdq');
    Route::post('/detailsubmit', 'RequestController@submitCDQNewRequestForm')->name('new-request-detail-cdq-submit');

    Route::get('/detailopt/{id}', 'RequestController@getCDQOptDetails')->middleware('request_details')->name('opt-request-details-cdq');
    Route::post('/detailoptsubmit', 'RequestController@submitCDQOptRequestForm')->name('opt-request-detail-cdq-submit');

    Route::get('/allnew', 'RequestController@getCDQAllNewProjectRequests')->name('all_new_request_cdq');
    Route::get('/allopt', 'RequestController@getCDQAllOptRequests')->name('all_opt_request_cdq');

    Route::get('/edit', 'UserController@getEditProfilCDQ')->name('edit_cdq');
    Route::post('/edit', 'UserController@editProfilCDQSubmit')->name('submit_cdq_edit');

    Route::get('/archiveOpt', 'RequestController@getCDQOptArchive')->name('get_cdq_opt_archive');
    Route::get('/archiveOptDetails{id}', 'RequestController@getCDQOptArchiveDetails')->name('get_cdq_opt_archive_details');

    Route::get('/archiveNew', 'RequestController@getCDQNewArchive')->name('get_cdq_new_archive');
    Route::get('/archiveNewDetails{id}', 'RequestController@getCDQNewArchiveDetails')->name('get_cdq_new_archive_details');

    Route::get('/stat', 'RequestController@getStatCdq')->name('get_cdq_stat');

});

Route::group(['prefix' => 'dev'], function () {

    Route::get('/stat', 'RequestController@getStat')->name('get_dev_stat');

});



/*Route::get('users/{role}', 'UserController@usersByRole');
Route::get('/detailopt/{id}', 'RequestController@getOptDetails')->middleware('request_details')->name('opt_detail_request');
*/



