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
    Route::post('/adminedit', 'UserController@editProfilPostAdmin')->name('submit_adminedit');
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
    Route::post('/editprofil', 'UserController@editProfilChdSubmit')->name('submit_chd_edit');

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
    Route::post('/editprofil', 'UserController@editProfilCEDSubmit')->name('submit_ced_edit');

});

Route::group(['prefix' => 'prop', 'middleware' => 'prop'], function () {
    Route::get('/showopt', 'RequestController@getPropOptRequest')->name('get_prop_opt');

    Route::get('/detailopt/{id}', 'RequestController@getPropOptDetails')->middleware('request_details')->name('opt-request-details-prop');
    Route::any('/detailaccept/{id}', 'RequestController@AcceptOptRequest')->name('accept_request');
    Route::any('/detailrefu/{id}', 'RequestController@RefuseOptRequest')->name('refuse_request');

    Route::get('/allnew', 'RequestController@getPropAllNewProjectRequests')->name('all_new_request_prop');
    Route::get('/allopt', 'RequestController@getPropAllOptRequests')->name('all_opt_request_prop');

    Route::get('/edit', 'UserController@getEditProfilProp')->name('edit_prop');
    Route::post('/editprofil', 'UserController@editProfilPropSubmit')->name('submit_prop_edit');
});

Route::group(['prefix' => 'cdd', 'middleware' => 'cdd'], function () {
    Route::get('/shownew', 'RequestController@getCDDNewProjectRequest')->name('get_cdd_new');
    Route::get('/showopt', 'RequestController@getCDDOptRequest')->name('get_cdd_opt');

    Route::get('/detail/{id}', 'RequestController@getCDDNewDetails')->middleware('request_details')->name('new-request-details-cdd');
    Route::post('/detailsubmit', 'RequestController@submitCDDNewRequestForm')->name('new-request-detail-cdd-submit');

    Route::get('/detailopt/{id}', 'RequestController@getCDDOptDetails')->middleware('request_details')->name('opt-request-details-cdd');
    Route::post('/detailoptsubmit', 'RequestController@submitCDDOptRequestForm')->name('opt-request-detail-cdd-submit');

    Route::get('/allnew', 'RequestController@getCDDAllNewProjectRequests')->name('all_new_request_cdd');
    Route::get('/allopt', 'RequestController@getCDDAllOptRequests')->name('all_opt_request_cdd');
//next
Route::get('/shownewp', 'RequestController@getCDDPNewProjectRequest')->name('get_cddp_new');
    Route::get('/showoppt', 'RequestController@getCDDOptRequest')->name('get_cddp_opt');

    Route::get('/detailp/{id}', 'RequestController@getCDDPNewDetails')->middleware('request_details')->name('new-p-details-cdd');
    Route::post('/detailsubmitp', 'RequestController@submitCDDPNewRequestForm')->name('new-p-detail-cdd-submit');

    Route::get('/detailoptp/{id}', 'RequestController@getCDDPOptDetails')->middleware('request_details')->name('opt-p-details-cdd');
    Route::post('/detailoptsubmitp', 'RequestController@submitCDDPOptRequestForm')->name('opt-p-detail-cdd-submit');

    
//

    Route::get('/edit', 'UserController@getEditProfilCDD')->name('edit_cdd');
    Route::post('/editprofil', 'UserController@editProfilCDDSubmit')->name('submit_cdd_edit');
    //stat
    Route::get('/stat', 'RequestController@getStatCdd')->name('get_cdd_stat');

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
    Route::post('/editprofil', 'UserController@editProfilORGSubmit')->name('submit_org_edit');


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
    Route::post('/editprofil', 'UserController@editProfilDSSubmit')->name('submit_ds_edit');

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
    Route::post('/editprofil', 'UserController@editProfilCDQSubmit')->name('submit_cdq_edit');

});

//dv
Route::group(['prefix' => 'dv', 'middleware' => 'dv'], function () {
    Route::get('/shownew', 'RequestController@getdvNewProjectRequest')->name('get_dv_new');
    Route::get('/showopt', 'RequestController@getdvOptRequest')->name('get_dv_opt');

    Route::get('/detail/{id}', 'RequestController@getdvNewDetails')->middleware('request_details')->name('new-request-details-dv');
    Route::post('/detailsubmit', 'RequestController@submitdvNewRequestForm')->name('new-request-detail-dv-submit');

    Route::get('/detailopt/{id}', 'RequestController@getdvOptDetails')->middleware('request_details')->name('opt-request-details-dv');
    Route::post('/detailoptsubmit', 'RequestController@submitdvOptRequestForm')->name('opt-request-detail-dv-submit');

    Route::get('/allnew', 'RequestController@getdvAllNewProjectRequests')->name('all_new_request_dv');
    Route::get('/allopt', 'RequestController@getdvAllOptRequests')->name('all_opt_request_dv');
//next
Route::get('/shownewd', 'RequestController@getdvsecNewProjectRequest')->name('get_sec_new');
    Route::get('/showoptd', 'RequestController@getdvsecOptRequest')->name('get_sec_opt');

    Route::get('/detaild/{id}', 'RequestController@getdvsecNewDetails')->middleware('request_details')->name('new-sec-details-dv');
    Route::post('/detailsubmitd', 'RequestController@submitdvsecNewRequestForm')->name('new-sec-detail-dv-submit');

    Route::get('/detailoptd/{id}', 'RequestController@getdvsecOptDetails')->middleware('request_details')->name('opt-sec-details-dv');
    Route::post('/detailoptsubmitd', 'RequestController@submitdvsecOptRequestForm')->name('opt-sec-detail-dv-submit');

    
//
    Route::get('/archiveOpt', 'RequestController@getdvOptArchive')->name('get_dv_opt_archive');
    Route::get('/archiveOptDetails{id}', 'RequestController@getdvOptArchiveDetails')->name('get_dv_opt_archive_details');

    Route::get('/archiveNew', 'RequestController@getdvNewArchive')->name('get_dv_new_archive');
    Route::get('/archiveNewDetails{id}', 'RequestController@getdvNewArchiveDetails')->name('get_dv_new_archive_details');

    Route::get('/edit', 'UserController@getEditProfildv')->name('edit_dv');
    Route::post('/editprofil', 'UserController@editProfildvSubmit')->name('submit_dv_edit');
    //stat
    Route::get('/stat', 'RequestController@getStat')->name('get_dev_stat');

});

Route::group(['prefix' => 'cai', 'middleware' => 'cai'], function () {

    Route::get('/shownew', 'RequestController@getcaiNewProjectRequest')->name('get_cai_new');
    Route::get('/showopt', 'RequestController@getcaiOptRequest')->name('get_cai_opt');

    Route::get('/detail/{id}', 'RequestController@getcaiNewDetails')->middleware('request_details')->name('new-request-details-cai');
    Route::any('/detailaccept/{id}', 'RequestController@caiAcceptOptRequest')->name('caiaccept_request');
    Route::any('/detailrefu/{id}', 'RequestController@caiRefuseOptRequest')->name('cairefuse_request');

    Route::get('/detailopt/{id}', 'RequestController@getcaiOptDetails')->middleware('request_details')->name('opt-request-details-cai');
 
    Route::get('/allnew', 'RequestController@getcaiAllNewProjectRequests')->name('all_new_request_cai');
    Route::get('/allopt', 'RequestController@getcaiAllOptRequests')->name('all_opt_request_cai');

    Route::get('/edit', 'UserController@getEditProfilcai')->name('edit_cai');
    Route::post('/editprofil', 'UserController@editProfilcaiSubmit')->name('submit_cai_edit'); 
    //
    Route::get('/archiveOpt', 'RequestController@getcaiOptArchive')->name('get_cai_opt_archive');
    Route::get('/archiveOptDetails{id}', 'RequestController@getcaiOptArchiveDetails')->name('get_cai_opt_archive_details');

    Route::get('/archiveNew', 'RequestController@getcaiNewArchive')->name('get_cai_new_archive');
    Route::get('/archiveNewDetails{id}', 'RequestController@getcaiNewArchiveDetails')->name('get_cai_new_archive_details');
    Route::get('/stat', 'RequestController@getStatcai')->name('get_cai_stat'); 

});


/*Route::get('users/{role}', 'UserController@usersByRole');
Route::get('/detailopt/{id}', 'RequestController@getOptDetails')->middleware('request_details')->name('opt_detail_request');
*/



