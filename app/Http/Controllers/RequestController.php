<?php

namespace App\Http\Controllers;

use App\NewProjectRequest;
use App\ProjectRequest;
use App\Enums\StatusRequest;
use App\Repositories\Request\RequestRepositoryInterface;
use Illuminate\Support\Facades\Auth;
use App\Project;
use App\Http\Requests\NewProjectRequestRequest;
use App\Http\Requests\OptimizationRequestRequest;
use App\Http\Requests\NewRequestDetailsRequest;
use App\Http\Requests\OptRequestDetailsRequest;
use App\OptimizationRequest;
use App\Enums\UserRole;
use App\Notifications\WorkAdded;
use App\Notifications\DeploymentNotification;
use App\Notifications\MiseEnPlaceNotification;
use Illuminate\Support\Facades\DB;
use App\User;

class RequestController extends Controller
{
    public function __construct(RequestRepositoryInterface $requestRepository)
    {
        $this->requestRepository = $requestRepository;
    }

    //User

    public function getUserNewDetails($id){
        $request = ProjectRequest::find($id);
        return view('user/new-request-details')->with('request', $request);
    }

    public function getUserOptDetails($id){
        $request = ProjectRequest::find($id);
        return view('user/opt-request-details')->with('request', $request);
    }

    public function getUserNewProjectRequests() {
        return view('user/new-request-table')->with('newprojectrequests', 
            ProjectRequest::where(['requestable_type' => 'App\NewProjectRequest', 'user_id' => Auth::user()->id])->get());
    }

    public function getUserOptRequests() {
        return view('user/opt-request-table')->with('optimizationRequests', 
        ProjectRequest::where(['requestable_type' => 'App\OptimizationRequest', 'user_id' => Auth::user()->id])->get());
    }

    public function getUserNewProjectRequestForm(){
        return view('user/new-request-form');
    }

    public function submitNewProjectRequestForm(NewProjectRequestRequest $newProjectRequestRequest){
        $user = Auth::user();
        $inputs = $newProjectRequestRequest->all();
        $file = $newProjectRequestRequest->file('chd');
        $newFileName = 'user_doc_'  . str_random(8) . '.' . $file->getClientOriginalExtension();
        $file->move('files', $newFileName);

        $inputs['status'] = StatusRequest::byKey("progressing_CED")->getValue();
        $inputs['user_id'] = $user->id;

        //files
        $inputs['user_doc'] = $newFileName;
        $inputs['logiciel_doc'] = null;
        $inputs['ced_doc'] = null;
        $inputs['organisation_doc'] = null;
        $inputs['chd_doc'] = null;
        $inputs['analyse_doc'] = null;
        $inputs['conception_doc'] = null;
        $inputs['test_doc'] = null;
        $inputs['recette_doc'] = null;
        $inputs['circulaire_doc'] = null;

        $newPorjectRequest = new NewProjectRequest();
        $this->requestRepository->saveNewProjectRequest($newPorjectRequest, $inputs);

        $user = User::where(['role' => UserRole::byKey('CED')])->first();
        $user->notify(new WorkAdded($newPorjectRequest->request));
        return redirect()->back();
    }
    
    public function getUserOptRequestForm(){
        return view('user/opt-request-form')->with('projects', Project::all());
    }

    public function submitOptimizationRequestForm(OptimizationRequestRequest $optimizationRequestRequest){
        $user = Auth::user();
        $inputs = $optimizationRequestRequest->all();
        $file = $optimizationRequestRequest->file('chd');
        $newFileName = 'user_doc_'  . str_random(8) . '.' . $file->getClientOriginalExtension();
        $file->move('files', $newFileName);

        $inputs['status'] = StatusRequest::byKey("waiting")->getValue();

        $inputs['user_id'] = $user->id;

        //files
        $inputs['user_doc'] = $newFileName;
        $inputs['logiciel_doc'] = null;
        $inputs['ced_doc'] = null;
        $inputs['organisation_doc'] = null;
        $inputs['chd_doc'] = null;
        $inputs['analyse_doc'] = null;
        $inputs['conception_doc'] = null;
        $inputs['test_doc'] = null;
        $inputs['recette_doc'] = null;
        $inputs['circulaire_doc'] = null;

        $optRequest = new OptimizationRequest();
        $this->requestRepository->saveOptimizationRequest($optRequest, $inputs);

        $user = User::where(['role' => UserRole::byKey('proprietaire')])->first();
        $user->notify(new WorkAdded($optRequest->request));
        return redirect()->back();
    }

    //End User

    //CD

    public function getCDNewProjectRequest(){ 
        return view('chd/new-request-table')->with('newprojectrequests', 
            ProjectRequest::where(['requestable_type' => 'App\NewProjectRequest', 'status' => StatusRequest::byKey('progressing_chd')->getValue()])->get());
    }

    public function getCDOptRequest(){ 
        return view('chd/opt-request-table')->with('optimizationRequests', 
            ProjectRequest::where(['requestable_type' => 'App\OptimizationRequest', 'status' => StatusRequest::byKey('progressing_chd')->getValue()])->get());
    }

    //new request

    public function getCDNewDetails($id){
        $request = ProjectRequest::find($id);
        return view('chd/new-request-details')->with('request', $request);
    }

    public function submitCDNewRequestForm(NewRequestDetailsRequest $newRequestDetailsRequest){
        $inputs = $newRequestDetailsRequest->all();
        $requestId = $inputs['requestId'];
        $request = ProjectRequest::find($requestId);

        $file = $newRequestDetailsRequest->file('doc');
        $newFileName = 'chd_doc_'  . str_random(8) . '.' . $file->getClientOriginalExtension();
        $file->move('files', $newFileName);

        $inputs['status'] = StatusRequest::byKey("progressing_p")->getValue();
        $inputs['chd_doc'] = $newFileName;
        $inputs['title'] = $request->requestable->title;
        $inputs['remarques'] = $request->remarques;
        $inputs['user_doc'] = $request->user_doc;
        $inputs['ced_doc'] = $request->ced_doc;
        $inputs['organisation_doc'] = $request->organisation_doc;
        $inputs['analyse_doc'] = $request->analyse_doc;
        $inputs['conception_doc'] = $request->conception_doc;
        $inputs['logiciel_doc'] = $request->logiciel_doc;
        $inputs['test_doc'] = $request->test_doc;
        $inputs['recette_doc'] = $request->recette_doc;
        $inputs['circulaire_doc'] = $request->circulaire_doc;
        $inputs['user_id'] = $request->user_id;

        $this->requestRepository->saveNewProjectRequest( $request->requestable, $inputs);
        $this->markNotificationAsReaded($request);
        $user = User::where(['role' => UserRole::byKey('dev_chef')])->first();
        $user->notify(new WorkAdded($request));
        return redirect()->route('get_chd_new');
    }

    //opt request

    public function getCDOptDetails($id){
        $request = ProjectRequest::find($id);
        return view('chd/opt-request-details')->with('request', $request);
    }

    public function submitCDOptRequestForm(OptRequestDetailsRequest $optRequestDetailsRequest){
        $inputs = $optRequestDetailsRequest->all();
        $request_id = $inputs['request_id'];
        $request = ProjectRequest::find($request_id);

        $file = $optRequestDetailsRequest->file('doc');
        $newFileName = 'chd_doc'  . str_random(8) . '.' . $file->getClientOriginalExtension();
        $file->move('files', $newFileName);

        $inputs['status'] = StatusRequest::byKey("progressing_p")->getValue();
        $inputs['chd_doc'] = $newFileName;
        $inputs['type'] = $request->requestable->type;
        $inputs['project_id'] = $request->requestable->project_id;
        $inputs['remarques'] = $request->remarques;
        $inputs['user_doc'] = $request->user_doc;
        $inputs['ced_doc'] = $request->ced_doc;
        $inputs['organisation_doc'] = $request->organisation_doc;
        $inputs['analyse_doc'] = $request->analyse_doc;
        $inputs['conception_doc'] = $request->conception_doc;
        $inputs['logiciel_doc'] = $request->logiciel_doc;
        $inputs['test_doc'] = $request->test_doc;
        $inputs['recette_doc'] = $request->recette_doc;
        $inputs['circulaire_doc'] = $request->circulaire_doc;
        $inputs['user_id'] = $request->user_id;

        $this->requestRepository->saveOptimizationRequest($request->requestable, $inputs);
        $this->markNotificationAsReaded($request);
        $user = User::where(['role' => UserRole::byKey('dev_chef')])->first();
        $user->notify(new WorkAdded($request));
        return redirect()->route('get_chd_opt');
    }

    //All Requests

    public function getCDAllNewProjectRequests() {
        return view('chd/all-new-requests')->with('newprojectrequests', NewProjectRequest::all());
    }

    public function getCDAllOptRequests() {
        return view('chd/all-opt-requests')->with('optimizationRequests', OptimizationRequest::all());
    }

    //archive

    public function getCDOptArchive() {
        return view('chd/archive_opt_project')->with('projectrequest',
            ProjectRequest::where(['requestable_type' => 'App\OptimizationRequest','status' => StatusRequest::byKey('done')->getValue()])->get());
    }
    public function getCDOptArchiveDetails($id){
        $projectrequest = ProjectRequest::find($id);
        return view('chd/archive_opt_project_details')->with('projectrequest', $projectrequest);
    }
    public function getCDNewArchive() {
        return view('chd/archive_new_project')->with('newprojectrequest',
            ProjectRequest::where(['requestable_type' => 'App\NewProjectRequest','status' => StatusRequest::byKey('done')->getValue()])->get());
    }
    public function getCDNewArchiveDetails($id){
        $newprojectrequest = ProjectRequest::find($id);
        return view('chd/archive_new_project_details')->with('newprojectrequest', $newprojectrequest);
    }

    //stat

    public function getStatChd(){
        $untreatedCount = ProjectRequest::where('status', '=', StatusRequest::byKey('progressing_chd')->getValue())->count();
        $avgHours = DB::select(DB::raw('select round(avg(hours)) as avgHours from (select time_to_sec(timediff(updated_at, created_at)) / 3600 as hours from requests where requests.status = 6) as hoursTable'))[0]->avgHours;
        if($avgHours == null) $avgHours = 0;
        $notChdProjCount = ProjectRequest::where('status', '!=', StatusRequest::byKey('done')->getValue())->count();
        $chdProjCount = ProjectRequest::where(['status' => StatusRequest::byKey('progressing_chd')->getValue()])->count();
        $chdProjPercentage = $notChdProjCount == 0 ? 0 : ($chdProjCount / $notChdProjCount) * 100;
        $newProjectRequestData = collect(DB::select(DB::raw('select count(r.id) as count FROM (SELECT * FROM requests WHERE YEAR(created_at) = YEAR(NOW()) AND requestable_type LIKE "%NewProjectRequest") r RIGHT JOIN (SELECT 1 AS month UNION SELECT 1 UNION SELECT 2 UNION SELECT 3 UNION SELECT 4 UNION SELECT 5 UNION SELECT 6 UNION SELECT 7 UNION SELECT 8 UNION SELECT 9 UNION SELECT 10 UNION SELECT 11 UNION SELECT 12) m ON MONTH(r.created_at) = m.month GROUP BY m.month ORDER BY m.month')))->pluck("count");

        $optProjectRequestData = collect(DB::select(DB::raw('select count(r.id) as count FROM (SELECT * FROM requests WHERE YEAR(created_at) = YEAR(NOW()) AND requestable_type LIKE "%OptimizationRequest") r RIGHT JOIN (SELECT 1 AS month UNION SELECT 1 UNION SELECT 2 UNION SELECT 3 UNION SELECT 4 UNION SELECT 5 UNION SELECT 6 UNION SELECT 7 UNION SELECT 8 UNION SELECT 9 UNION SELECT 10 UNION SELECT 11 UNION SELECT 12) m ON MONTH(r.created_at) = m.month GROUP BY m.month ORDER BY m.month')))->pluck("count");

        return view('chd/charts')->with('untreatedCount', $untreatedCount)->with('avgHours', $avgHours)->with('chdProjPercentage', $chdProjPercentage)->with('newProjectRequestData', $newProjectRequestData)->with('optProjectRequestData', $optProjectRequestData);

    }

    //End CD

    //CED

    public function getCEDNewProjectRequest(){ 
        return view('ced/new-request-table')->with('newprojectrequests', 
            ProjectRequest::where(['requestable_type' => 'App\NewProjectRequest', 'status' => StatusRequest::byKey('progressing_CED')->getValue()])->get());
    }
    
    public function getCEDNewDetails($id){
        $request = ProjectRequest::find($id);
        return view('ced/new-request-details')->with('request', $request);
    }

    public function submitCEDNewRequestForm(NewRequestDetailsRequest $newRequestDetailsRequest){
        $inputs = $newRequestDetailsRequest->all();
        $requestId = $inputs['requestId'];
        $request = ProjectRequest::find($requestId);

        $file = $newRequestDetailsRequest->file('doc');
        $newFileName = 'ced_doc'  . str_random(8) . '.' . $file->getClientOriginalExtension();
        $file->move('files', $newFileName);

        $inputs['status'] = StatusRequest::byKey("progressing_chd")->getValue();
        $inputs['ced_doc'] = $newFileName;
        $inputs['title'] = $request->requestable->title;
        $inputs['remarques'] = $request->remarques;
        $inputs['user_doc'] = $request->user_doc;
        $inputs['chd_doc'] = $request->chd_doc;
        $inputs['organisation_doc'] = $request->organisation_doc;
        $inputs['analyse_doc'] = $request->analyse_doc;
        $inputs['conception_doc'] = $request->conception_doc;
        $inputs['logiciel_doc'] = $request->logiciel_doc;
        $inputs['test_doc'] = $request->test_doc;
        $inputs['recette_doc'] = $request->recette_doc;
        $inputs['circulaire_doc'] = $request->circulaire_doc;
        $inputs['user_id'] = $request->user_id;

        $this->requestRepository->saveNewProjectRequest($request->requestable, $inputs);
        $this->markNotificationAsReaded($request);
        $user = User::where(['role' => UserRole::byKey('ChefCD')])->first();
        $user->notify(new WorkAdded($request));
        return redirect()->route('get_ced_new');
    }

    //opt request

    public function getCEDOptRequest(){ 
        return view('ced/opt-request-table')->with('optimizationRequests', 
            ProjectRequest::where(['requestable_type' => 'App\OptimizationRequest', 'status' => StatusRequest::byKey('progressing_CED')->getValue()])->get());
    }

    public function getCEDOptDetails($id){
        $request = ProjectRequest::find($id);
        return view('ced/opt-request-details')->with('request', $request);
    }

    public function submitCEDOptRequestForm(OptRequestDetailsRequest $optRequestDetailsRequest){
        $inputs = $optRequestDetailsRequest->all();
        $request_id = $inputs['request_id'];
        $request = ProjectRequest::find($request_id);

        $file = $optRequestDetailsRequest->file('doc');
        $newFileName = 'ced_doc'  . str_random(8) . '.' . $file->getClientOriginalExtension();
        $file->move('files', $newFileName);

        $inputs['status'] = StatusRequest::byKey("progressing_chd")->getValue();
        $inputs['chd_doc'] = $request->chd_doc;
        $inputs['type'] = $request->requestable->type;
        $inputs['project_id'] = $request->requestable->project_id;
        $inputs['remarques'] = $request->remarques;
        $inputs['user_doc'] = $request->user_doc;
        $inputs['ced_doc'] = $newFileName;
        $inputs['organisation_doc'] = $request->organisation_doc;
        $inputs['analyse_doc'] = $request->analyse_doc;
        $inputs['conception_doc'] = $request->conception_doc;
        $inputs['logiciel_doc'] = $request->logiciel_doc;
        $inputs['test_doc'] = $request->test_doc;
        $inputs['recette_doc'] = $request->recette_doc;
        $inputs['circulaire_doc'] = $request->circulaire_doc;
        $inputs['user_id'] = $request->user_id;

        $this->requestRepository->saveOptimizationRequest($request->requestable, $inputs);
        $this->markNotificationAsReaded($request);
        $user = User::where(['role' => UserRole::byKey('ChefCD')])->first();
        $user->notify(new WorkAdded($request));
        return redirect()->route('get_ced_opt');
    }

    //All Requests

    public function getCEDAllNewProjectRequests() {
        return view('ced/all-new-requests')->with('newprojectrequests', NewProjectRequest::all());
    }

    public function getCEDAllOptRequests() {
        return view('ced/all-opt-requests')->with('optimizationRequests', OptimizationRequest::all());
    }

    //archive

    public function getCEDOptArchive() {
        return view('ced/archive_opt_project')->with('projectrequest',
            ProjectRequest::where(['requestable_type' => 'App\OptimizationRequest','status' => StatusRequest::byKey('done')->getValue()])->get());
    }
    public function getCEDOptArchiveDetails($id){
        $projectrequest = ProjectRequest::find($id);
        return view('ced/archive_opt_project_details')->with('projectrequest', $projectrequest);
    }
    public function getCEDNewArchive() {
        return view('ced/archive_new_project')->with('newprojectrequest',
            ProjectRequest::where(['requestable_type' => 'App\NewProjectRequest','status' => StatusRequest::byKey('done')->getValue()])->get());
    }
    public function getCEDNewArchiveDetails($id){
        $newprojectrequest = ProjectRequest::find($id);
        return view('ced/archive_new_project_details')->with('newprojectrequest', $newprojectrequest);
    }

    //stat

    public function getStatCed(){
        $untreatedCount = ProjectRequest::where('status', '=', StatusRequest::byKey('progressing_CED')->getValue())->count();
        $avgHours = DB::select(DB::raw('select round(avg(hours)) as avgHours from (select time_to_sec(timediff(updated_at, created_at)) / 3600 as hours from requests where requests.status = 6) as hoursTable'))[0]->avgHours;
        if($avgHours == null) $avgHours = 0;
        $notCedProjCount = ProjectRequest::where('status', '!=', StatusRequest::byKey('done')->getValue())->count();
        $cedProjCount = ProjectRequest::where(['status' => StatusRequest::byKey('progressing_CED')->getValue()])->count();
        $cedProjPercentage = $notCedProjCount == 0 ? 0 : ($cedProjCount / $notCedProjCount) * 100;
        $newProjectRequestData = collect(DB::select(DB::raw('select count(r.id) as count FROM (SELECT * FROM requests WHERE YEAR(created_at) = YEAR(NOW()) AND requestable_type LIKE "%NewProjectRequest") r RIGHT JOIN (SELECT 1 AS month UNION SELECT 1 UNION SELECT 2 UNION SELECT 3 UNION SELECT 4 UNION SELECT 5 UNION SELECT 6 UNION SELECT 7 UNION SELECT 8 UNION SELECT 9 UNION SELECT 10 UNION SELECT 11 UNION SELECT 12) m ON MONTH(r.created_at) = m.month GROUP BY m.month ORDER BY m.month')))->pluck("count");

        $optProjectRequestData = collect(DB::select(DB::raw('select count(r.id) as count FROM (SELECT * FROM requests WHERE YEAR(created_at) = YEAR(NOW()) AND requestable_type LIKE "%OptimizationRequest") r RIGHT JOIN (SELECT 1 AS month UNION SELECT 1 UNION SELECT 2 UNION SELECT 3 UNION SELECT 4 UNION SELECT 5 UNION SELECT 6 UNION SELECT 7 UNION SELECT 8 UNION SELECT 9 UNION SELECT 10 UNION SELECT 11 UNION SELECT 12) m ON MONTH(r.created_at) = m.month GROUP BY m.month ORDER BY m.month')))->pluck("count");

        return view('ced/charts')->with('untreatedCount', $untreatedCount)->with('avgHours', $avgHours)->with('cedProjPercentage', $cedProjPercentage)->with('newProjectRequestData', $newProjectRequestData)->with('optProjectRequestData', $optProjectRequestData);

    }

    //End CED

    //Propriétaire
    
    //opt request

    public function getPropOptRequest(){ 
        return view('prop/opt-request-table')->with('optimizationRequests', 
            ProjectRequest::where(['requestable_type' => 'App\OptimizationRequest', 'status' => StatusRequest::byKey('waiting')->getValue()])->get());
    }

    public function getPropOptDetails($id){
        $request = ProjectRequest::find($id);
        return view('prop/opt-request-details')->with('request', $request);
    }

    public function AcceptOptRequest($id){
        $request = ProjectRequest::find($id);

        $inputs['status'] = StatusRequest::byKey("progressing_CED")->getValue();
        $inputs['chd_doc'] = $request->chd_doc;
        $inputs['type'] = $request->requestable->type;
        $inputs['project_id'] = $request->requestable->project_id;
        $inputs['remarques'] = $request->remarques;
        $inputs['user_doc'] = $request->user_doc;
        $inputs['ced_doc'] = $request->ced_doc;
        $inputs['organisation_doc'] = $request->organisation_doc;
        $inputs['analyse_doc'] = $request->analyse_doc;
        $inputs['conception_doc'] = $request->conception_doc;
        $inputs['logiciel_doc'] = $request->logiciel_doc;
        $inputs['test_doc'] = $request->test_doc;
        $inputs['recette_doc'] = $request->recette_doc;
        $inputs['circulaire_doc'] = $request->circulaire_doc;
        $inputs['user_id'] = $request->user_id;

        $this->requestRepository->saveOptimizationRequest($request->requestable, $inputs);
        $this->markNotificationAsReaded($request);
        $user = User::where(['role' => UserRole::byKey('CED')])->first();
        $user->notify(new WorkAdded($request));
        return redirect()->route('get_prop_opt');
    }

    public function RefuseOptRequest($id){
        $request = ProjectRequest::find($id);

        $inputs['status'] = StatusRequest::byKey("refus")->getValue();
        $inputs['chd_doc'] = $request->chd_doc;
        $inputs['type'] = $request->requestable->type;
        $inputs['project_id'] = $request->requestable->project_id;
        $inputs['remarques'] = $request->remarques;
        $inputs['user_doc'] = $request->user_doc;
        $inputs['ced_doc'] = $request->ced_doc;
        $inputs['organisation_doc'] = $request->organisation_doc;
        $inputs['analyse_doc'] = $request->analyse_doc;
        $inputs['conception_doc'] = $request->conception_doc;
        $inputs['logiciel_doc'] = $request->logiciel_doc;
        $inputs['test_doc'] = $request->test_doc;
        $inputs['recette_doc'] = $request->recette_doc;
        $inputs['circulaire_doc'] = $request->circulaire_doc;
        $inputs['user_id'] = $request->user_id;

        $this->requestRepository->saveOptimizationRequest($request->requestable, $inputs);
        $this->markNotificationAsReaded($request);
        return redirect()->route('get_prop_opt');
        
    }

    //All Requests

    public function getPropAllNewProjectRequests() {
        return view('prop/all-new-requests')->with('newprojectrequests', NewProjectRequest::all());
    }

    public function getPropAllOptRequests() {
        return view('prop/all-opt-requests')->with('optimizationRequests', OptimizationRequest::all());
    }

    //archive

    public function getPropOptArchive() {
        return view('prop/archive_opt_project')->with('projectrequest',
            ProjectRequest::where(['requestable_type' => 'App\OptimizationRequest','status' => StatusRequest::byKey('done')->getValue()])->get());
    }
    public function getPropOptArchiveDetails($id){
        $projectrequest = ProjectRequest::find($id);
        return view('prop/archive_opt_project_details')->with('projectrequest', $projectrequest);
    }
    public function getPropNewArchive() {
        return view('prop/archive_new_project')->with('newprojectrequest',
            ProjectRequest::where(['requestable_type' => 'App\NewProjectRequest','status' => StatusRequest::byKey('done')->getValue()])->get());
    }
    public function getPropNewArchiveDetails($id){
        $newprojectrequest = ProjectRequest::find($id);
        return view('prop/archive_new_project_details')->with('newprojectrequest', $newprojectrequest);
    }

    //stat

    public function getStatProp(){
        $untreatedCount = ProjectRequest::where('status', '=', StatusRequest::byKey('waiting')->getValue())->count();
        $avgHours = DB::select(DB::raw('select round(avg(hours)) as avgHours from (select time_to_sec(timediff(updated_at, created_at)) / 3600 as hours from requests where requests.status = 6) as hoursTable'))[0]->avgHours;
        if($avgHours == null) $avgHours = 0;
        $notPropProjCount = ProjectRequest::where('status', '!=', StatusRequest::byKey('done')->getValue())->count();
        $propProjCount = ProjectRequest::where(['status' => StatusRequest::byKey('waiting')->getValue()])->count();
        $propProjPercentage = $notPropProjCount == 0 ? 0 : ($propProjCount / $notPropProjCount) * 100;
        $newProjectRequestData = collect(DB::select(DB::raw('select count(r.id) as count FROM (SELECT * FROM requests WHERE YEAR(created_at) = YEAR(NOW()) AND requestable_type LIKE "%NewProjectRequest") r RIGHT JOIN (SELECT 1 AS month UNION SELECT 1 UNION SELECT 2 UNION SELECT 3 UNION SELECT 4 UNION SELECT 5 UNION SELECT 6 UNION SELECT 7 UNION SELECT 8 UNION SELECT 9 UNION SELECT 10 UNION SELECT 11 UNION SELECT 12) m ON MONTH(r.created_at) = m.month GROUP BY m.month ORDER BY m.month')))->pluck("count");

        $optProjectRequestData = collect(DB::select(DB::raw('select count(r.id) as count FROM (SELECT * FROM requests WHERE YEAR(created_at) = YEAR(NOW()) AND requestable_type LIKE "%OptimizationRequest") r RIGHT JOIN (SELECT 1 AS month UNION SELECT 1 UNION SELECT 2 UNION SELECT 3 UNION SELECT 4 UNION SELECT 5 UNION SELECT 6 UNION SELECT 7 UNION SELECT 8 UNION SELECT 9 UNION SELECT 10 UNION SELECT 11 UNION SELECT 12) m ON MONTH(r.created_at) = m.month GROUP BY m.month ORDER BY m.month')))->pluck("count");

        return view('prop/charts')->with('untreatedCount', $untreatedCount)->with('avgHours', $avgHours)->with('propProjPercentage', $propProjPercentage)->with('newProjectRequestData', $newProjectRequestData)->with('optProjectRequestData', $optProjectRequestData);

    }

    //End Prop

    //CDD

    public function getCDDNewProjectRequest(){ 
        return view('cdd/new-request-table')->with('newprojectrequests', 
            ProjectRequest::where(['requestable_type' => 'App\NewProjectRequest', 'status' => StatusRequest::byKey('progressing_div')->getValue()])->get());
    }
    
    public function getCDDNewDetails($id){
        $request = ProjectRequest::find($id);
        return view('cdd/new-request-details')->with('request', $request);
    }

    public function submitCDDNewRequestForm(NewRequestDetailsRequest $newRequestDetailsRequest){
        $inputs = $newRequestDetailsRequest->all();
        $requestId = $inputs['requestId'];
        $request = ProjectRequest::find($requestId);

        $file = $newRequestDetailsRequest->file('doc');
        $newFileName = 'test_doc'  . str_random(8) . '.' . $file->getClientOriginalExtension();
        $file->move('files', $newFileName);

        $inputs['status'] = StatusRequest::byKey("progressing_recette")->getValue();
        $inputs['ced_doc'] = $request->ced_doc;
        $inputs['title'] = $request->requestable->title;
        $inputs['remarques'] = $request->remarques;
        $inputs['user_doc'] = $request->user_doc;
        $inputs['chd_doc'] = $request->chd_doc;
        $inputs['organisation_doc'] = $request->organisation_doc;
        $inputs['analyse_doc'] = $request->analyse_doc;
        $inputs['conception_doc'] = $request->conception_doc;
        $inputs['logiciel_doc'] = $request->logiciel_doc;
        $inputs['test_doc'] = $newFileName;
        $inputs['recette_doc'] = $request->recette_doc;
        $inputs['circulaire_doc'] = $request->circulaire_doc;
        $inputs['user_id'] = $request->user_id;

        $this->requestRepository->saveNewProjectRequest($request->requestable, $inputs);
        $this->markNotificationAsReaded($request);
        $user = User::where(['role' => UserRole::byKey('quality_chef')])->first();
        $user->notify(new WorkAdded($request));
        return redirect()->route('get_cdd_new');
    }

    //opt request

    public function getCDDOptRequest(){ 
        return view('cdd/opt-request-table')->with('optimizationRequests', 
            ProjectRequest::where(['requestable_type' => 'App\OptimizationRequest', 'status' => StatusRequest::byKey('progressing_div')->getValue()])->get());
    }

    public function getCDDOptDetails($id){
        $request = ProjectRequest::find($id);
        return view('cdd/opt-request-details')->with('request', $request);
    }

    public function submitCDDOptRequestForm(OptRequestDetailsRequest $optRequestDetailsRequest){
        $inputs = $optRequestDetailsRequest->all();
        $request_id = $inputs['request_id'];
        $request = ProjectRequest::find($request_id);

        $file = $optRequestDetailsRequest->file('doc');
        $newFileName = 'test_doc'  . str_random(8) . '.' . $file->getClientOriginalExtension();
        $file->move('files', $newFileName);

        $inputs['status'] = StatusRequest::byKey("progressing_recette")->getValue();
        $inputs['chd_doc'] = $request->chd_doc;
        $inputs['type'] = $request->requestable->type;
        $inputs['project_id'] = $request->requestable->project_id;
        $inputs['remarques'] = $request->remarques;
        $inputs['user_doc'] = $request->user_doc;
        $inputs['ced_doc'] = $request->chd_doc;
        $inputs['organisation_doc'] = $request->organisation_doc;
        $inputs['analyse_doc'] = $request->analyse_doc;
        $inputs['conception_doc'] = $request->conception_doc;
        $inputs['logiciel_doc'] = $request->logiciel_doc;
        $inputs['test_doc'] = $newFileName;
        $inputs['recette_doc'] = $request->recette_doc;
        $inputs['circulaire_doc'] = $request->circulaire_doc;
        $inputs['user_id'] = $request->user_id;

        $this->requestRepository->saveOptimizationRequest($request->requestable, $inputs);
        $this->markNotificationAsReaded($request);
        $user = User::where(['role' => UserRole::byKey('quality_chef')])->first();
        $user->notify(new WorkAdded($request));
        return redirect()->route('get_cdd_opt');
    }

    //CDDsec

    public function getCDDPNewProjectRequest(){
        return view('cdd/new-p-table')->with('newprojectrequests',
            ProjectRequest::where(['requestable_type' => 'App\NewProjectRequest', 'status' => StatusRequest::byKey('progressing_p')->getValue()])->get());
    }

    public function getCDDPNewDetails($id){
        $request = ProjectRequest::find($id);
        return view('cdd/new-p-details')->with('request', $request);
    }

    public function submitCDDPNewRequestForm(NewRequestDetailsRequest $newRequestDetailsRequest){
        $inputs = $newRequestDetailsRequest->all();
        $requestId = $inputs['requestId'];
        $request = ProjectRequest::find($requestId);

        $file = $newRequestDetailsRequest->file('doc');
        $newFileName = 'analyse_doc'  . str_random(8) . '.' . $file->getClientOriginalExtension();
        $file->move('files', $newFileName);

        $inputs['status'] = StatusRequest::byKey("progressing_devlop")->getValue();
        $inputs['ced_doc'] = $request->ced_doc;
        $inputs['title'] = $request->requestable->title;
        $inputs['remarques'] = $request->remarques;
        $inputs['user_doc'] = $request->user_doc;
        $inputs['chd_doc'] = $request->chd_doc;
        $inputs['organisation_doc'] = $request->organisation_doc;
        $inputs['analyse_doc'] = $newFileName;
        $inputs['conception_doc'] = $request->conception_doc;
        $inputs['logiciel_doc'] = $request->logiciel_doc;
        $inputs['test_doc'] =$request->test_doc;
        $inputs['recette_doc'] = $request->recette_doc;
        $inputs['circulaire_doc'] = $request->circulaire_doc;
        $inputs['user_id'] = $request->user_id;

        $this->requestRepository->saveNewProjectRequest($request->requestable, $inputs);
        $this->markNotificationAsReaded($request);
        $user = User::where(['role' => UserRole::byKey('Developpeur')])->first();
        $user->notify(new WorkAdded($request));
        return redirect()->route('get_cddp_new');
    }

    //opt request

    public function getCDDPOptRequest(){
        return view('cdd/opt-p-table')->with('optimizationRequests',
            ProjectRequest::where(['requestable_type' => 'App\OptimizationRequest', 'status' => StatusRequest::byKey('progressing_p')->getValue()])->get());
    }

    public function getCDDPOptDetails($id){
        $request = ProjectRequest::find($id);
        return view('cdd/opt-p-details')->with('request', $request);
    }

    public function submitCDDPOptRequestForm(OptRequestDetailsRequest $optRequestDetailsRequest){
        $inputs = $optRequestDetailsRequest->all();
        $request_id = $inputs['request_id'];
        $request = ProjectRequest::find($request_id);

        $file = $optRequestDetailsRequest->file('doc');
        $newFileName = 'analyse_doc'  . str_random(8) . '.' . $file->getClientOriginalExtension();
        $file->move('files', $newFileName);

        $inputs['status'] = StatusRequest::byKey("progressing_devlop")->getValue();
        $inputs['chd_doc'] = $request->chd_doc;
        $inputs['type'] = $request->requestable->type;
        $inputs['project_id'] = $request->requestable->project_id;
        $inputs['remarques'] = $request->remarques;
        $inputs['user_doc'] = $request->user_doc;
        $inputs['ced_doc'] = $request->chd_doc;
        $inputs['organisation_doc'] = $request->organisation_doc;
        $inputs['analyse_doc'] = $newFileName;
        $inputs['conception_doc'] = $request->conception_doc;
        $inputs['logiciel_doc'] = $request->logiciel_doc;
        $inputs['test_doc'] =$request->test_doc;
        $inputs['recette_doc'] = $request->recette_doc;
        $inputs['circulaire_doc'] = $request->circulaire_doc;
        $inputs['user_id'] = $request->user_id;

        $this->requestRepository->saveOptimizationRequest($request->requestable, $inputs);
        $this->markNotificationAsReaded($request);
        $user = User::where(['role' => UserRole::byKey('Developpeur')])->first();
        $user->notify(new WorkAdded($request));
        return redirect()->route('get_cddp_opt');
    }

    //All Requests

    public function getCDDAllNewProjectRequests() {
        return view('cdd/all-new-requests')->with('newprojectrequests', NewProjectRequest::all());
    }

    public function getCDDAllOptRequests() {
        return view('cdd/all-opt-requests')->with('optimizationRequests', OptimizationRequest::all());
    }


      //stat

      public function getStatcdd(){
        $untreatedCount = ProjectRequest::where('status', '=', StatusRequest::byKey('progressing_p')->getValue())->where('status', '=', StatusRequest::byKey('progressing_div')->getValue())->count();
        $avgHours = DB::select(DB::raw('select round(avg(hours)) as avgHours from (select time_to_sec(timediff(updated_at, created_at)) / 3600 as hours from requests where requests.status = 6) as hoursTable'))[0]->avgHours;
        if($avgHours == null) $avgHours = 0;
        $notcddProjCount = ProjectRequest::where('status', '!=', StatusRequest::byKey('done')->getValue())->count();
        $cddProjCount = ProjectRequest::where(['status' => StatusRequest::byKey('progressing_p')->getValue(), 'status' => StatusRequest::byKey('progressing_div')->getValue()])->count();
        $cddProjPercentage = $notcddProjCount == 0 ? 0 : ($cddProjCount / $notcddProjCount) * 100;
        $newProjectRequestData = collect(DB::select(DB::raw('select count(r.id) as count FROM (SELECT * FROM requests WHERE YEAR(created_at) = YEAR(NOW()) AND requestable_type LIKE "%NewProjectRequest") r RIGHT JOIN (SELECT 1 AS month UNION SELECT 1 UNION SELECT 2 UNION SELECT 3 UNION SELECT 4 UNION SELECT 5 UNION SELECT 6 UNION SELECT 7 UNION SELECT 8 UNION SELECT 9 UNION SELECT 10 UNION SELECT 11 UNION SELECT 12) m ON MONTH(r.created_at) = m.month GROUP BY m.month ORDER BY m.month')))->pluck("count");

        $optProjectRequestData = collect(DB::select(DB::raw('select count(r.id) as count FROM (SELECT * FROM requests WHERE YEAR(created_at) = YEAR(NOW()) AND requestable_type LIKE "%OptimizationRequest") r RIGHT JOIN (SELECT 1 AS month UNION SELECT 1 UNION SELECT 2 UNION SELECT 3 UNION SELECT 4 UNION SELECT 5 UNION SELECT 6 UNION SELECT 7 UNION SELECT 8 UNION SELECT 9 UNION SELECT 10 UNION SELECT 11 UNION SELECT 12) m ON MONTH(r.created_at) = m.month GROUP BY m.month ORDER BY m.month')))->pluck("count");

        return view('cdd/charts')->with('untreatedCount', $untreatedCount)->with('avgHours', $avgHours)->with('cddProjPercentage', $cddProjPercentage)->with('newProjectRequestData', $newProjectRequestData)->with('optProjectRequestData', $optProjectRequestData);

    }

    public function getCDDOptArchive() {
        return view('cdd/archive_opt_project')->with('projectrequest',
            ProjectRequest::where(['requestable_type' => 'App\OptimizationRequest','status' => StatusRequest::byKey('done')->getValue()])->get());
    }
    public function getCDDOptArchiveDetails($id){
        $projectrequest = ProjectRequest::find($id);
        return view('cdd/archive_opt_project_details')->with('projectrequest', $projectrequest);
    }
    public function getCDDNewArchive() {
        return view('cdd/archive_new_project')->with('newprojectrequest',
            ProjectRequest::where(['requestable_type' => 'App\NewProjectRequest','status' => StatusRequest::byKey('done')->getValue()])->get());
    }
    public function getCDDNewArchiveDetails($id){
        $newprojectrequest = ProjectRequest::find($id);
        return view('cdd/archive_new_project_details')->with('newprojectrequest', $newprojectrequest);
    }
    //End CDD

    //CDQ

    public function getCDQNewProjectRequest(){ 
        return view('cdq/new-request-table')->with('newprojectrequests', 
            ProjectRequest::where(['requestable_type' => 'App\NewProjectRequest', 'status' => StatusRequest::byKey('progressing_recette')->getValue()])->get());
    }
    
    public function getCDQNewDetails($id){
        $request = ProjectRequest::find($id);
        return view('cdq/new-request-details')->with('request', $request);
    }

    public function submitCDQNewRequestForm(NewRequestDetailsRequest $newRequestDetailsRequest){
        $inputs = $newRequestDetailsRequest->all();
        $requestId = $inputs['requestId'];
        $request = ProjectRequest::find($requestId);

        $file = $newRequestDetailsRequest->file('doc');
        $newFileName = 'recette_doc'  . str_random(8) . '.' . $file->getClientOriginalExtension();
        $file->move('files', $newFileName);

        $inputs['status'] = StatusRequest::byKey("progressing_systeme")->getValue();
        $inputs['ced_doc'] = $request->ced_doc;
        $inputs['title'] = $request->requestable->title;
        $inputs['remarques'] = $request->remarques;
        $inputs['user_doc'] = $request->user_doc;
        $inputs['chd_doc'] = $request->chd_doc;
        $inputs['organisation_doc'] = $request->organisation_doc;
        $inputs['analyse_doc'] = $request->analyse_doc;
        $inputs['conception_doc'] = $request->conception_doc;
        $inputs['logiciel_doc'] = $request->logiciel_doc;
        $inputs['test_doc'] = $request->test_doc;
        $inputs['recette_doc'] = $newFileName;
        $inputs['circulaire_doc'] = $request->circulaire_doc;
        $inputs['user_id'] = $request->user_id;

        $this->requestRepository->saveNewProjectRequest($request->requestable, $inputs);
        $this->markNotificationAsReaded($request);
        $user = User::where(['role' => UserRole::byKey('sys_chef')])->first();
        $user->notify(new WorkAdded($request));
        return redirect()->route('get_cdq_new');
    }

    //opt request

    public function getCDQOptRequest(){ 
        return view('cdq/opt-request-table')->with('optimizationRequests', 
            ProjectRequest::where(['requestable_type' => 'App\OptimizationRequest', 'status' => StatusRequest::byKey('progressing_recette')->getValue()])->get());
    }

    public function getCDQOptDetails($id){
        $request = ProjectRequest::find($id);
        return view('cdq/opt-request-details')->with('request', $request);
    }

    public function submitCDQOptRequestForm(OptRequestDetailsRequest $optRequestDetailsRequest){
        $inputs = $optRequestDetailsRequest->all();
        $request_id = $inputs['request_id'];
        $request = ProjectRequest::find($request_id);

        $file = $optRequestDetailsRequest->file('doc');
        $newFileName = 'recette_doc'  . str_random(8) . '.' . $file->getClientOriginalExtension();
        $file->move('files', $newFileName);

        $inputs['status'] = StatusRequest::byKey("progressing_systeme")->getValue();
        $inputs['chd_doc'] = $request->chd_doc;
        $inputs['type'] = $request->requestable->type;
        $inputs['project_id'] = $request->requestable->project_id;
        $inputs['remarques'] = $request->remarques;
        $inputs['user_doc'] = $request->user_doc;
        $inputs['ced_doc'] = $request->chd_doc;
        $inputs['organisation_doc'] = $request->organisation_doc;
        $inputs['analyse_doc'] = $request->analyse_doc;
        $inputs['conception_doc'] = $request->conception_doc;
        $inputs['logiciel_doc'] = $request->logiciel_doc;
        $inputs['test_doc'] = $request->test_doc;
        $inputs['recette_doc'] = $newFileName;
        $inputs['circulaire_doc'] = $request->circulaire_doc;
        $inputs['user_id'] = $request->user_id;

        $this->requestRepository->saveOptimizationRequest($request->requestable, $inputs);
        $this->markNotificationAsReaded($request);
        $user = User::where(['role' => UserRole::byKey('sys_chef')])->first();
        $user->notify(new WorkAdded($request));
        return redirect()->route('get_cdq_opt');
    }

    //All Requests

    public function getCDQAllNewProjectRequests() {
        return view('cdq/all-new-requests')->with('newprojectrequests', NewProjectRequest::all());
    }

    public function getCDQAllOptRequests() {
        return view('cdq/all-opt-requests')->with('optimizationRequests', OptimizationRequest::all());
    }

    //archive

    public function getCDQOptArchive() {
        return view('cdq/archive_opt_project')->with('projectrequest',
            ProjectRequest::where(['requestable_type' => 'App\OptimizationRequest','status' => StatusRequest::byKey('done')->getValue()])->get());
    }
    public function getCDQOptArchiveDetails($id){
        $projectrequest = ProjectRequest::find($id);
        return view('cdq/archive_opt_project_details')->with('projectrequest', $projectrequest);
    }
    public function getCDQNewArchive() {
        return view('cdq/archive_new_project')->with('newprojectrequest',
            ProjectRequest::where(['requestable_type' => 'App\NewProjectRequest','status' => StatusRequest::byKey('done')->getValue()])->get());
    }
    public function getCDQNewArchiveDetails($id){
        $newprojectrequest = ProjectRequest::find($id);
        return view('cdq/archive_new_project_details')->with('newprojectrequest', $newprojectrequest);
    }

    //stat

    public function getStatCdq(){
        $untreatedCount = ProjectRequest::where('status', '=', StatusRequest::byKey('progressing_recette')->getValue())->count();
        $avgHours = DB::select(DB::raw('select round(avg(hours)) as avgHours from (select time_to_sec(timediff(updated_at, created_at)) / 3600 as hours from requests where requests.status = 6) as hoursTable'))[0]->avgHours;
        if($avgHours == null) $avgHours = 0;
        $notCdqProjCount = ProjectRequest::where('status', '!=', StatusRequest::byKey('done')->getValue())->count();
        $cdqProjCount = ProjectRequest::where(['status' => StatusRequest::byKey('progressing_recette')->getValue()])->count();
        $cdqProjPercentage = $cdqProjCount == 0 ? 0 : ($cdqProjCount / $notCdqProjCount) * 100;
        $newProjectRequestData = collect(DB::select(DB::raw('select count(r.id) as count FROM (SELECT * FROM requests WHERE YEAR(created_at) = YEAR(NOW()) AND requestable_type LIKE "%NewProjectRequest") r RIGHT JOIN (SELECT 1 AS month UNION SELECT 1 UNION SELECT 2 UNION SELECT 3 UNION SELECT 4 UNION SELECT 5 UNION SELECT 6 UNION SELECT 7 UNION SELECT 8 UNION SELECT 9 UNION SELECT 10 UNION SELECT 11 UNION SELECT 12) m ON MONTH(r.created_at) = m.month GROUP BY m.month ORDER BY m.month')))->pluck("count");

        $optProjectRequestData = collect(DB::select(DB::raw('select count(r.id) as count FROM (SELECT * FROM requests WHERE YEAR(created_at) = YEAR(NOW()) AND requestable_type LIKE "%OptimizationRequest") r RIGHT JOIN (SELECT 1 AS month UNION SELECT 1 UNION SELECT 2 UNION SELECT 3 UNION SELECT 4 UNION SELECT 5 UNION SELECT 6 UNION SELECT 7 UNION SELECT 8 UNION SELECT 9 UNION SELECT 10 UNION SELECT 11 UNION SELECT 12) m ON MONTH(r.created_at) = m.month GROUP BY m.month ORDER BY m.month')))->pluck("count");

        return view('cdq/charts')->with('untreatedCount', $untreatedCount)->with('avgHours', $avgHours)->with('cdqProjPercentage', $cdqProjPercentage)->with('newProjectRequestData', $newProjectRequestData)->with('optProjectRequestData', $optProjectRequestData);

    }

    //End CDQ

    //ORG

    public function getORGNewProjectRequest(){ 
        return view('organisation/new-request-table')->with('newprojectrequests', 
            ProjectRequest::where(['requestable_type' => 'App\NewProjectRequest', 'status' => StatusRequest::byKey('progressing_circulaire')->getValue()])->get());
    }
    
    public function getORGNewDetails($id){
        $request = ProjectRequest::find($id);
        return view('organisation/new-request-details')->with('request', $request);
    }

    public function submitORGNewRequestForm(NewRequestDetailsRequest $newRequestDetailsRequest){
        $inputs = $newRequestDetailsRequest->all();
        $requestId = $inputs['requestId'];
        $request = ProjectRequest::find($requestId);

        $file = $newRequestDetailsRequest->file('doc');
        $newFileName = 'circulaire_doc'  . str_random(8) . '.' . $file->getClientOriginalExtension();
        $file->move('files', $newFileName);

        $inputs['status'] = StatusRequest::byKey("done")->getValue();
        $inputs['ced_doc'] = $request->ced_doc;
        $inputs['title'] = $request->requestable->title;
        $inputs['remarques'] = $request->remarques;
        $inputs['user_doc'] = $request->user_doc;
        $inputs['chd_doc'] = $request->chd_doc;
        $inputs['organisation_doc'] = $request->organisation_doc;
        $inputs['analyse_doc'] = $request->analyse_doc;
        $inputs['conception_doc'] = $request->conception_doc;
        $inputs['logiciel_doc'] = $request->logiciel_doc;
        $inputs['test_doc'] = $request->test_doc;
        $inputs['recette_doc'] = $request->recette_doc;
        $inputs['circulaire_doc'] = $newFileName;
        $inputs['user_id'] = $request->user_id;

        $this->requestRepository->saveNewProjectRequest($request->requestable, $inputs);
        $this->markNotificationAsReaded($request);
        return redirect()->route('get_org_new');
    }

    //opt request

    public function getORGOptRequest(){ 
        return view('organisation/opt-request-table')->with('optimizationRequests', 
            ProjectRequest::where(['requestable_type' => 'App\OptimizationRequest', 'status' => StatusRequest::byKey('progressing_circulaire')->getValue()])->get());
    }

    public function getORGOptDetails($id){
        $request = ProjectRequest::find($id);
        return view('organisation/opt-request-details')->with('request', $request);
    }

    public function submitORGOptRequestForm(OptRequestDetailsRequest $optRequestDetailsRequest){
        $inputs = $optRequestDetailsRequest->all();
        $request_id = $inputs['request_id'];
        $request = ProjectRequest::find($request_id);

        $file = $optRequestDetailsRequest->file('doc');
        $newFileName = 'circulaire_doc'  . str_random(8) . '.' . $file->getClientOriginalExtension();
        $file->move('files', $newFileName);

        $inputs['status'] = StatusRequest::byKey("done")->getValue();
        $inputs['chd_doc'] = $request->chd_doc;
        $inputs['type'] = $request->requestable->type;
        $inputs['project_id'] = $request->requestable->project_id;
        $inputs['remarques'] = $request->remarques;
        $inputs['user_doc'] = $request->user_doc;
        $inputs['ced_doc'] = $request->chd_doc;
        $inputs['organisation_doc'] = $request->organisation_doc;
        $inputs['analyse_doc'] = $request->analyse_doc;
        $inputs['conception_doc'] = $request->conception_doc;
        $inputs['logiciel_doc'] = $request->logiciel_doc;
        $inputs['test_doc'] = $request->test_doc;
        $inputs['recette_doc'] = $request->circulaire_doc;
        $inputs['circulaire_doc'] = $newFileName;
        $inputs['user_id'] = $request->user_id;

        $this->requestRepository->saveOptimizationRequest($request->requestable, $inputs);
        $this->markNotificationAsReaded($request);
        return redirect()->route('get_org_opt');
    }

    //All Requests

    public function getORGAllNewProjectRequests() {
        return view('organisation/all-new-requests')->with('newprojectrequests', NewProjectRequest::all());
    }

    public function getORGAllOptRequests() {
        return view('organisation/all-opt-requests')->with('optimizationRequests', OptimizationRequest::all());
    }

    //archive

    public function getORGOptArchive() {
        return view('organisation/archive_opt_project')->with('projectrequest',
            ProjectRequest::where(['requestable_type' => 'App\OptimizationRequest','status' => StatusRequest::byKey('done')->getValue()])->get());
    }
    public function getORGOptArchiveDetails($id){
        $projectrequest = ProjectRequest::find($id);
        return view('organisation/archive_opt_project_details')->with('projectrequest', $projectrequest);
    }
    public function getORGNewArchive() {
        return view('organisation/archive_new_project')->with('newprojectrequest',
            ProjectRequest::where(['requestable_type' => 'App\NewProjectRequest','status' => StatusRequest::byKey('done')->getValue()])->get());
    }
    public function getORGNewArchiveDetails($id){
        $newprojectrequest = ProjectRequest::find($id);
        return view('organisation/archive_new_project_details')->with('newprojectrequest', $newprojectrequest);
    }

    //stat

    public function getStatOrg(){
        $untreatedCount = ProjectRequest::where('status', '=', StatusRequest::byKey('progressing_circulaire')->getValue())->count();
        $avgHours = DB::select(DB::raw('select round(avg(hours)) as avgHours from (select time_to_sec(timediff(updated_at, created_at)) / 3600 as hours from requests where requests.status = 6) as hoursTable'))[0]->avgHours;
        if($avgHours == null) $avgHours = 0;
        $notOrgProjCount = ProjectRequest::where('status', '!=', StatusRequest::byKey('done')->getValue())->count();
        $orgProjCount = ProjectRequest::where(['status' => StatusRequest::byKey('progressing_circulaire')->getValue()])->count();
        $orgProjPercentage = $notOrgProjCount == 0 ? 0 : ($orgProjCount / $notOrgProjCount) * 100;
        $newProjectRequestData = collect(DB::select(DB::raw('select count(r.id) as count FROM (SELECT * FROM requests WHERE YEAR(created_at) = YEAR(NOW()) AND requestable_type LIKE "%NewProjectRequest") r RIGHT JOIN (SELECT 1 AS month UNION SELECT 1 UNION SELECT 2 UNION SELECT 3 UNION SELECT 4 UNION SELECT 5 UNION SELECT 6 UNION SELECT 7 UNION SELECT 8 UNION SELECT 9 UNION SELECT 10 UNION SELECT 11 UNION SELECT 12) m ON MONTH(r.created_at) = m.month GROUP BY m.month ORDER BY m.month')))->pluck("count");

        $optProjectRequestData = collect(DB::select(DB::raw('select count(r.id) as count FROM (SELECT * FROM requests WHERE YEAR(created_at) = YEAR(NOW()) AND requestable_type LIKE "%OptimizationRequest") r RIGHT JOIN (SELECT 1 AS month UNION SELECT 1 UNION SELECT 2 UNION SELECT 3 UNION SELECT 4 UNION SELECT 5 UNION SELECT 6 UNION SELECT 7 UNION SELECT 8 UNION SELECT 9 UNION SELECT 10 UNION SELECT 11 UNION SELECT 12) m ON MONTH(r.created_at) = m.month GROUP BY m.month ORDER BY m.month')))->pluck("count");

        return view('organisation/charts')->with('untreatedCount', $untreatedCount)->with('avgHours', $avgHours)->with('orgProjPercentage', $orgProjPercentage)->with('newProjectRequestData', $newProjectRequestData)->with('optProjectRequestData', $optProjectRequestData);

    }

    //End ORG

    //DS

    public function getDSNewProjectRequest(){ 
        return view('ds/new-request-table')->with('newprojectrequests', 
            ProjectRequest::where(['requestable_type' => 'App\NewProjectRequest', 'status' => StatusRequest::byKey('progressing_systeme')->getValue()])->get());
    }
    
    public function getDSNewDetails($id){
        $request = ProjectRequest::find($id);
        return view('ds/new-request-details')->with('request', $request);
    }

    public function submitDSNewRequestForm($id){
        $request = ProjectRequest::find($id);
        $user = User::find($request->user_id); 
        $name = $user->name;
        $creation = $request->created_at;
        $title = $request->requestable->title;

        $inputs['status'] = StatusRequest::byKey("progressing_circulaire")->getValue();
        $inputs['ced_doc'] = $request->ced_doc;
        $inputs['title'] = $request->requestable->title;
        $inputs['remarques'] = $request->remarques;
        $inputs['user_doc'] = $request->user_doc;
        $inputs['chd_doc'] = $request->chd_doc;
        $inputs['organisation_doc'] = $request->organisation_doc;
        $inputs['analyse_doc'] = $request->analyse_doc;
        $inputs['conception_doc'] = $request->conception_doc;
        $inputs['logiciel_doc'] = $request->logiciel_doc;
        $inputs['test_doc'] = $request->test_doc;
        $inputs['recette_doc'] = $request->recette_doc;
        $inputs['circulaire_doc'] = $request->circulaire_doc;
        $inputs['user_id'] = $request->user_id;

        $this->requestRepository->saveNewProjectRequest($request->requestable, $inputs); 
        $user = User::where(['role' => UserRole::byKey('info')])->first();
        $user->notify(new WorkAdded($request));
        $this->markNotificationAsReaded($request);
        $user->notify(new DeploymentNotification($name, $creation, $title));
        return redirect()->route('get_ds_new');
    
    }

    //opt request

    public function getDSOptRequest(){ 
        return view('ds/opt-request-table')->with('optimizationRequests', 
            ProjectRequest::where(['requestable_type' => 'App\OptimizationRequest', 'status' => StatusRequest::byKey('progressing_systeme')->getValue()])->get());
    }

    public function getDSOptDetails($id){
        $request = ProjectRequest::find($id);
        return view('ds/opt-request-details')->with('request', $request);
    }

    public function submitDSOptRequestForm($id){
        $request = ProjectRequest::find($id);
        $user = User::find($request->user_id); 
        $name = $user->name;
        $creation = $request->created_at;
        $project = Project::find($request->requestable->project_id)->name;

        $inputs['status'] = StatusRequest::byKey("progressing_circulaire")->getValue();
        $inputs['chd_doc'] = $request->chd_doc;
        $inputs['type'] = $request->requestable->type;
        $inputs['project_id'] = $request->requestable->project_id;
        $inputs['remarques'] = $request->remarques;
        $inputs['user_doc'] = $request->user_doc;
        $inputs['ced_doc'] = $request->chd_doc;
        $inputs['organisation_doc'] = $request->organisation_doc;
        $inputs['analyse_doc'] = $request->analyse_doc;
        $inputs['conception_doc'] = $request->conception_doc;
        $inputs['logiciel_doc'] = $request->logiciel_doc;
        $inputs['test_doc'] = $request->test_doc;
        $inputs['recette_doc'] = $request->recette;
        $inputs['circulaire_doc'] = $request->circulaire_doc;
        $inputs['user_id'] = $request->user_id;

        $user->notify(new MiseEnPlaceNotification($name, $creation, $project));

        $this->requestRepository->saveOptimizationRequest($request->requestable, $inputs);
        $user = User::where(['role' => UserRole::byKey('info')])->first();
        $user->notify(new WorkAdded($request));        
        $this->markNotificationAsReaded($request);
        return redirect()->route('get_ds_opt');
    }

    //All Requests

    public function getDSAllNewProjectRequests() {
        return view('ds/all-new-requests')->with('newprojectrequests', NewProjectRequest::all());
    }

    public function getDSAllOptRequests() {
        return view('ds/all-opt-requests')->with('optimizationRequests', OptimizationRequest::all());
    }

    //archive

    public function getDSOptArchive() {
        return view('ds/archive_opt_project')->with('projectrequest',
            ProjectRequest::where(['requestable_type' => 'App\OptimizationRequest','status' => StatusRequest::byKey('done')->getValue()])->get());
    }
    public function getDSOptArchiveDetails($id){
        $projectrequest = ProjectRequest::find($id);
        return view('ds/archive_opt_project_details')->with('projectrequest', $projectrequest);
    }
    public function getDSNewArchive() {
        return view('ds/archive_new_project')->with('newprojectrequest',
            ProjectRequest::where(['requestable_type' => 'App\NewProjectRequest','status' => StatusRequest::byKey('done')->getValue()])->get());
    }
    public function getDSNewArchiveDetails($id){
        $newprojectrequest = ProjectRequest::find($id);
        return view('ds/archive_new_project_details')->with('newprojectrequest', $newprojectrequest);
    }

    //stat

    public function getStatDs(){
        $untreatedCount = ProjectRequest::where('status', '=', StatusRequest::byKey('progressing_systeme')->getValue())->count();
        $avgHours = DB::select(DB::raw('select round(avg(hours)) as avgHours from (select time_to_sec(timediff(updated_at, created_at)) / 3600 as hours from requests where requests.status = 6) as hoursTable'))[0]->avgHours;
        if($avgHours == null) $avgHours = 0;
        $notDsProjCount = ProjectRequest::where('status', '!=', StatusRequest::byKey('done')->getValue())->count();
        $dsProjCount = ProjectRequest::where(['status' => StatusRequest::byKey('progressing_systeme')->getValue()])->count();
        $dsProjPercentage = $notDsProjCount == 0 ? 0 : ($dsProjCount / $notDsProjCount) * 100;
        $newProjectRequestData = collect(DB::select(DB::raw('select count(r.id) as count FROM (SELECT * FROM requests WHERE YEAR(created_at) = YEAR(NOW()) AND requestable_type LIKE "%NewProjectRequest") r RIGHT JOIN (SELECT 1 AS month UNION SELECT 1 UNION SELECT 2 UNION SELECT 3 UNION SELECT 4 UNION SELECT 5 UNION SELECT 6 UNION SELECT 7 UNION SELECT 8 UNION SELECT 9 UNION SELECT 10 UNION SELECT 11 UNION SELECT 12) m ON MONTH(r.created_at) = m.month GROUP BY m.month ORDER BY m.month')))->pluck("count");

        $optProjectRequestData = collect(DB::select(DB::raw('select count(r.id) as count FROM (SELECT * FROM requests WHERE YEAR(created_at) = YEAR(NOW()) AND requestable_type LIKE "%OptimizationRequest") r RIGHT JOIN (SELECT 1 AS month UNION SELECT 1 UNION SELECT 2 UNION SELECT 3 UNION SELECT 4 UNION SELECT 5 UNION SELECT 6 UNION SELECT 7 UNION SELECT 8 UNION SELECT 9 UNION SELECT 10 UNION SELECT 11 UNION SELECT 12) m ON MONTH(r.created_at) = m.month GROUP BY m.month ORDER BY m.month')))->pluck("count");

        return view('organisation/charts')->with('untreatedCount', $untreatedCount)->with('avgHours', $avgHours)->with('dsProjPercentage', $dsProjPercentage)->with('newProjectRequestData', $newProjectRequestData)->with('optProjectRequestData', $optProjectRequestData);

    }


    //End DS

    //devloppeurs

      public function getdvNewProjectRequest(){
        return view('dv/new-request-table')->with('newprojectrequests',
            ProjectRequest::where(['requestable_type' => 'App\NewProjectRequest', 'status' => StatusRequest::byKey('progressing_devlop')->getValue()])->get());
    }

    public function getdvNewDetails($id){
        $request = ProjectRequest::find($id);
        return view('dv/new-request-details')->with('request', $request);
    }

    public function submitdvNewRequestForm(NewRequestDetailsRequest $newRequestDetailsRequest){
        $inputs = $newRequestDetailsRequest->all();
        $requestId = $inputs['requestId'];
        $request = ProjectRequest::find($requestId);

        $file = $newRequestDetailsRequest->file('doc');
        $newFileName = 'conception_doc'  . str_random(8) . '.' . $file->getClientOriginalExtension();
        $file->move('files', $newFileName);

        $inputs['status'] = StatusRequest::byKey("progressing_archi")->getValue();
        $inputs['ced_doc'] = $request->ced_doc;
        $inputs['title'] = $request->requestable->title;
        $inputs['remarques'] = $request->remarques;
        $inputs['user_doc'] = $request->user_doc;
        $inputs['chd_doc'] = $request->chd_doc;
        $inputs['organisation_doc'] = $request->organisation_doc;
        $inputs['analyse_doc'] =$request->analyse_doc ;
        $inputs['conception_doc'] =$newFileName ;
        $inputs['logiciel_doc'] = $request->logiciel_doc;
        $inputs['test_doc'] =$request->test_doc;
        $inputs['recette_doc'] = $request->recette_doc;
        $inputs['circulaire_doc'] = $request->circulaire_doc;
        $inputs['user_id'] = $request->user_id;

        $this->requestRepository->saveNewProjectRequest($request->requestable, $inputs);
        $this->markNotificationAsReaded($request);
        $user = User::where(['role' => UserRole::byKey('ChefArchitectureIntegration')])->first();
        $user->notify(new WorkAdded($request));
        return redirect()->route('get_dv_new');
    }

    //opt request

    public function getdvOptRequest(){
        return view('dv/opt-request-table')->with('optimizationRequests',
            ProjectRequest::where(['requestable_type' => 'App\OptimizationRequest', 'status' => StatusRequest::byKey('progressing_devlop')->getValue()])->get());
    }

    public function getdvOptDetails($id){
        $request = ProjectRequest::find($id);
        return view('dv/opt-request-details')->with('request', $request);
    }

    public function submitdvOptRequestForm(OptRequestDetailsRequest $optRequestDetailsRequest){
        $inputs = $optRequestDetailsRequest->all();
        $request_id = $inputs['request_id'];
        $request = ProjectRequest::find($request_id);

        $file = $optRequestDetailsRequest->file('doc');
        $newFileName = 'conception_doc'  . str_random(8) . '.' . $file->getClientOriginalExtension();
        $file->move('files', $newFileName);

        $inputs['status'] = StatusRequest::byKey("progressing_archi")->getValue();
        $inputs['chd_doc'] = $request->chd_doc;
        $inputs['type'] = $request->requestable->type;
        $inputs['project_id'] = $request->requestable->project_id;
        $inputs['remarques'] = $request->remarques;
        $inputs['user_doc'] = $request->user_doc;
        $inputs['ced_doc'] = $request->chd_doc;
        $inputs['organisation_doc'] = $request->organisation_doc;
        $inputs['analyse_doc'] =$request->analyse_doc;
        $inputs['conception_doc'] = $newFileName;
        $inputs['logiciel_doc'] = $request->logiciel_doc;
        $inputs['test_doc'] =$request->test_doc;
        $inputs['recette_doc'] = $request->recette_doc;
        $inputs['circulaire_doc'] = $request->circulaire_doc;
        $inputs['user_id'] = $request->user_id;

        $this->requestRepository->saveOptimizationRequest($request->requestable, $inputs);
        $this->markNotificationAsReaded($request);
        $user = User::where(['role' => UserRole::byKey('ChefArchitectureIntegration')])->first();
        $user->notify(new WorkAdded($request));
        return redirect()->route('get_dv_opt');
    }


    //dvsec

    public function getdvsecNewProjectRequest(){
        return view('dv/new-sec-table')->with('newprojectrequests',
            ProjectRequest::where(['requestable_type' => 'App\NewProjectRequest', 'status' => StatusRequest::byKey('progressing_devp')->getValue()])->get());
    }

    public function getdvsecNewDetails($id){
        $request = ProjectRequest::find($id);
        return view('dv/new-sec-details')->with('request', $request);
    }

    public function submitdvsecNewRequestForm(NewRequestDetailsRequest $newRequestDetailsRequest){
        $inputs = $newRequestDetailsRequest->all();
        $requestId = $inputs['requestId'];
        $request = ProjectRequest::find($requestId);

        $file = $newRequestDetailsRequest->file('doc');
        $newFileName = 'logiciel_doc'  . str_random(8) . '.' . $file->getClientOriginalExtension();
        $file->move('files', $newFileName);

        $inputs['status'] = StatusRequest::byKey("progressing_div")->getValue();
        $inputs['ced_doc'] = $request->ced_doc;
        $inputs['title'] = $request->requestable->title;
        $inputs['remarques'] = $request->remarques;
        $inputs['user_doc'] = $request->user_doc;
        $inputs['chd_doc'] = $request->chd_doc;
        $inputs['organisation_doc'] = $request->organisation_doc;
        $inputs['analyse_doc'] =$request->analyse_doc ;
        $inputs['conception_doc'] =$request->conception_doc ;
        $inputs['logiciel_doc'] = $newFileName ;
        $inputs['test_doc'] =$request->test_doc;
        $inputs['recette_doc'] = $request->recette_doc;
        $inputs['circulaire_doc'] = $request->circulaire_doc;
        $inputs['user_id'] = $request->user_id;

        $this->requestRepository->saveNewProjectRequest($request->requestable, $inputs);
        $this->markNotificationAsReaded($request);
        $user = User::where(['role' => UserRole::byKey('dev_chef')])->first();
        $user->notify(new WorkAdded($request));
        return redirect()->route('get_sec_new');
    }

    //opt request

    public function getdvsecOptRequest(){
        return view('dv/opt-sec-table')->with('optimizationRequests',
            ProjectRequest::where(['requestable_type' => 'App\OptimizationRequest', 'status' => StatusRequest::byKey('progressing_devp')->getValue()])->get());
    }

    public function getdvsecOptDetails($id){
        $request = ProjectRequest::find($id);
        return view('dv/opt-sec-details')->with('request', $request);
    }

    public function submitdvsecOptRequestForm(OptRequestDetailsRequest $optRequestDetailsRequest){
        $inputs = $optRequestDetailsRequest->all();
        $request_id = $inputs['request_id'];
        $request = ProjectRequest::find($request_id);

        $file = $optRequestDetailsRequest->file('doc');
        $newFileName = 'logiciel_doc'  . str_random(8) . '.' . $file->getClientOriginalExtension();
        $file->move('files', $newFileName);

        $inputs['status'] = StatusRequest::byKey("progressing_div")->getValue();
        $inputs['chd_doc'] = $request->chd_doc;
        $inputs['type'] = $request->requestable->type;
        $inputs['project_id'] = $request->requestable->project_id;
        $inputs['remarques'] = $request->remarques;
        $inputs['user_doc'] = $request->user_doc;
        $inputs['ced_doc'] = $request->chd_doc;
        $inputs['organisation_doc'] = $request->organisation_doc;
        $inputs['analyse_doc'] =$request->analyse_doc;
        $inputs['conception_doc'] =$request->conception_doc;
        $inputs['logiciel_doc'] =  $newFileName;
        $inputs['test_doc'] =$request->test_doc;
        $inputs['recette_doc'] = $request->recette_doc;
        $inputs['circulaire_doc'] = $request->circulaire_doc;
        $inputs['user_id'] = $request->user_id;

        $this->requestRepository->saveOptimizationRequest($request->requestable, $inputs);
        $this->markNotificationAsReaded($request);
        $user = User::where(['role' => UserRole::byKey('dev_chef')])->first();
        $user->notify(new WorkAdded($request));
        return redirect()->route('get_sec_opt');
    }

    //All Requests

    public function getdvAllNewProjectRequests() {
        return view('dv/all-new-requests')->with('newprojectrequests', NewProjectRequest::all());
    }

    public function getdvAllOptRequests() {
        return view('dv/all-opt-requests')->with('optimizationRequests', OptimizationRequest::all());
    }

    //archive

      public function getdvOptArchive() {
        return view('dv/archive_opt_project')->with('projectrequest',
            ProjectRequest::where(['requestable_type' => 'App\OptimizationRequest','status' => StatusRequest::byKey('done')->getValue()])->get());
    }
    public function getdvOptArchiveDetails($id){
        $projectrequest = ProjectRequest::find($id);
        return view('dv/archive_opt_project_details')->with('projectrequest', $projectrequest);
    }
    public function getdvNewArchive() {
        return view('dv/archive_new_project')->with('newprojectrequest',
            ProjectRequest::where(['requestable_type' => 'App\NewProjectRequest','status' => StatusRequest::byKey('done')->getValue()])->get());
    }
    public function getdvNewArchiveDetails($id){
        $newprojectrequest = ProjectRequest::find($id);
        return view('dv/archive_new_project_details')->with('newprojectrequest', $newprojectrequest);
    }
    //stat
    public function getStat(){
        $untreatedCount = ProjectRequest::where('status', '!=', StatusRequest::byKey('progressing_devlop')->getValue())->where('status', '!=', StatusRequest::byKey('progressing_devp')->getValue())->count();
        $avgHours = DB::select(DB::raw('select round(avg(hours)) as avgHours from (select time_to_sec(timediff(updated_at, created_at)) / 3600 as hours from requests where requests.status = 6) as hoursTable'))[0]->avgHours;
        if($avgHours == null) $avgHours = 0;
        $notdvProjCount = ProjectRequest::where('status', '!=', StatusRequest::byKey('done')->getValue())->count();
        $dvProjCount = ProjectRequest::where(['status' => StatusRequest::byKey('progressing_devlop')->getValue(), 'status' => StatusRequest::byKey('progressing_devp')->getValue()])->count();
        $dvProjPercentage = $notdvProjCount == 0 ? 0 : ($dvProjCount / $notdvProjCount) * 100;
        $newProjectRequestData = collect(DB::select(DB::raw('select count(r.id) as count FROM (SELECT * FROM requests WHERE YEAR(created_at) = YEAR(NOW()) AND requestable_type LIKE "%NewProjectRequest") r RIGHT JOIN (SELECT 1 AS month UNION SELECT 1 UNION SELECT 2 UNION SELECT 3 UNION SELECT 4 UNION SELECT 5 UNION SELECT 6 UNION SELECT 7 UNION SELECT 8 UNION SELECT 9 UNION SELECT 10 UNION SELECT 11 UNION SELECT 12) m ON MONTH(r.created_at) = m.month GROUP BY m.month ORDER BY m.month')))->pluck("count");

        $optProjectRequestData = collect(DB::select(DB::raw('select count(r.id) as count FROM (SELECT * FROM requests WHERE YEAR(created_at) = YEAR(NOW()) AND requestable_type LIKE "%OptimizationRequest") r RIGHT JOIN (SELECT 1 AS month UNION SELECT 1 UNION SELECT 2 UNION SELECT 3 UNION SELECT 4 UNION SELECT 5 UNION SELECT 6 UNION SELECT 7 UNION SELECT 8 UNION SELECT 9 UNION SELECT 10 UNION SELECT 11 UNION SELECT 12) m ON MONTH(r.created_at) = m.month GROUP BY m.month ORDER BY m.month')))->pluck("count");

        return view('dv/charts')->with('untreatedCount', $untreatedCount)->with('avgHours', $avgHours)->with('dvProjPercentage', $dvProjPercentage)->with('newProjectRequestData', $newProjectRequestData)->with('optProjectRequestData', $optProjectRequestData);

    }

    //End Dev

    //End


      //cai

    public function getcaiNewProjectRequest(){
        return view('cai/new-request-table')->with('newprojectrequests',
            ProjectRequest::where(['requestable_type' => 'App\NewProjectRequest', 'status' => StatusRequest::byKey('progressing_archi')->getValue()])->get());
    }

    public function getcaiNewDetails($id){
        $request = ProjectRequest::find($id);
        return view('cai/new-request-details')->with('request', $request);
    }
    
    public function caiAcceptOptRequest($id){
        $request = ProjectRequest::find($id);

        $inputs['status'] = StatusRequest::byKey("progressing_devp")->getValue();
        $inputs['chd_doc'] = $request->chd_doc;
        $inputs['type'] = $request->requestable->type;
        $inputs['project_id'] = $request->requestable->project_id;
        $inputs['remarques'] = $request->remarques;
        $inputs['user_doc'] = $request->user_doc;
        $inputs['ced_doc'] = $request->ced_doc;
        $inputs['organisation_doc'] = $request->organisation_doc;
        $inputs['analyse_doc'] = $request->analyse_doc;
        $inputs['conception_doc'] = $request->conception_doc;
        $inputs['logiciel_doc'] = $request->logiciel_doc;
        $inputs['test_doc'] = $request->test_doc;
        $inputs['recette_doc'] = $request->recette_doc;
        $inputs['circulaire_doc'] = $request->circulaire_doc;
        $inputs['user_id'] = $request->user_id;

        $this->requestRepository->saveOptimizationRequest($request->requestable, $inputs);
        $this->markNotificationAsReaded($request);
        $user = User::where(['role' => UserRole::byKey('Developpeur')])->first();
        $user->notify(new WorkAdded($request));
        return redirect()->route('get_cai_opt');
    }

    public function caiRefuseOptRequest($id){
        $request = ProjectRequest::find($id);

        $inputs['status'] = StatusRequest::byKey("progressing_devlop")->getValue();
        $inputs['chd_doc'] = $request->chd_doc;
        $inputs['type'] = $request->requestable->type;
        $inputs['project_id'] = $request->requestable->project_id;
        $inputs['remarques'] = $request->remarques;
        $inputs['user_doc'] = $request->user_doc;
        $inputs['ced_doc'] = $request->ced_doc;
        $inputs['organisation_doc'] = $request->organisation_doc;
        $inputs['analyse_doc'] = $request->analyse_doc;
        $inputs['conception_doc'] = $request->conception_doc;
        $inputs['logiciel_doc'] = $request->logiciel_doc;
        $inputs['test_doc'] = $request->test_doc;
        $inputs['recette_doc'] = $request->recette_doc;
        $inputs['circulaire_doc'] = $request->circulaire_doc;
        $inputs['user_id'] = $request->user_id;

        $this->requestRepository->saveOptimizationRequest($request->requestable, $inputs);
        $this->markNotificationAsReaded($request);
        $user = User::where(['role' => UserRole::byKey('Developpeur')])->first();
        $user->notify(new WorkAdded($request));
        return redirect()->route('get_cai_opt');
    }
    
    public function caiAcceptNewRequest($id){
        $request = ProjectRequest::find($id);

        $inputs['status'] = StatusRequest::byKey("progressing_devp")->getValue();
        $inputs['chd_doc'] = $request->chd_doc;
        $inputs['type'] = $request->requestable->type;
        $inputs['project_id'] = $request->requestable->project_id;
        $inputs['remarques'] = $request->remarques;
        $inputs['user_doc'] = $request->user_doc;
        $inputs['ced_doc'] = $request->ced_doc;
        $inputs['organisation_doc'] = $request->organisation_doc;
        $inputs['analyse_doc'] = $request->analyse_doc;
        $inputs['conception_doc'] = $request->conception_doc;
        $inputs['logiciel_doc'] = $request->logiciel_doc;
        $inputs['test_doc'] = $request->test_doc;
        $inputs['recette_doc'] = $request->recette_doc;
        $inputs['circulaire_doc'] = $request->circulaire_doc;
        $inputs['user_id'] = $request->user_id;

        $this->requestRepository->saveOptimizationRequest($request->requestable, $inputs);
        $this->markNotificationAsReaded($request);
        $user = User::where(['role' => UserRole::byKey('Developpeur')])->first();
        $user->notify(new WorkAdded($request));
        return redirect()->route('get_cai_new');
    }

    public function caiRefuseNewRequest($id){
        $request = ProjectRequest::find($id);

        $inputs['status'] = StatusRequest::byKey("progressing_devlop")->getValue();
        $inputs['chd_doc'] = $request->chd_doc;
        $inputs['type'] = $request->requestable->type;
        $inputs['project_id'] = $request->requestable->project_id;
        $inputs['remarques'] = $request->remarques;
        $inputs['user_doc'] = $request->user_doc;
        $inputs['ced_doc'] = $request->ced_doc;
        $inputs['organisation_doc'] = $request->organisation_doc;
        $inputs['analyse_doc'] = $request->analyse_doc;
        $inputs['conception_doc'] = $request->conception_doc;
        $inputs['logiciel_doc'] = $request->logiciel_doc;
        $inputs['test_doc'] = $request->test_doc;
        $inputs['recette_doc'] = $request->recette_doc;
        $inputs['circulaire_doc'] = $request->circulaire_doc;
        $inputs['user_id'] = $request->user_id;

        $this->requestRepository->saveOptimizationRequest($request->requestable, $inputs);
        $this->markNotificationAsReaded($request);
        $user = User::where(['role' => UserRole::byKey('Developpeur')])->first();
        $user->notify(new WorkAdded($request));
        return redirect()->route('get_cai_new');
    }

    //opt request

    public function getcaiOptRequest(){
        return view('cai/opt-request-table')->with('optimizationRequests',
            ProjectRequest::where(['requestable_type' => 'App\OptimizationRequest', 'status' => StatusRequest::byKey('progressing_archi')->getValue()])->get());
    }

    public function getcaiOptDetails($id){
        $request = ProjectRequest::find($id);
        return view('cai/opt-request-details')->with('request', $request);
    }

    public function getcaiAllNewProjectRequests() {
        return view('cai/all-new-requests')->with('newprojectrequests', NewProjectRequest::all());
    }

    public function getcaiAllOptRequests() {
        return view('cai/all-opt-requests')->with('optimizationRequests', OptimizationRequest::all());
    }
    //archive

    public function getcaiOptArchive() {
        return view('cai/archive_opt_project')->with('projectrequest',
            ProjectRequest::where(['requestable_type' => 'App\OptimizationRequest','status' => StatusRequest::byKey('done')->getValue()])->get());
    }
    public function getcaiOptArchiveDetails($id){
        $projectrequest = ProjectRequest::find($id);
        return view('cai/archive_opt_project_details')->with('projectrequest', $projectrequest);
    }
    public function getcaiNewArchive() {
        return view('cai/archive_new_project')->with('newprojectrequest',
            ProjectRequest::where(['requestable_type' => 'App\NewProjectRequest','status' => StatusRequest::byKey('done')->getValue()])->get());
    }
    public function getcaiNewArchiveDetails($id){
        $newprojectrequest = ProjectRequest::find($id);
        return view('cai/archive_new_project_details')->with('newprojectrequest', $newprojectrequest);
    }
    public function getStatcai(){
        $untreatedCount = ProjectRequest::where('status', '!=', StatusRequest::byKey('progressing_archi')->getValue())->count();
        $avgHours = DB::select(DB::raw('select round(avg(hours)) as avgHours from (select time_to_sec(timediff(updated_at, created_at)) / 3600 as hours from requests where requests.status = 6) as hoursTable'))[0]->avgHours;
        if($avgHours == null) $avgHours = 0;
        $notcaiProjCount = ProjectRequest::where('status', '!=', StatusRequest::byKey('done')->getValue())->count();
        $caiProjCount = ProjectRequest::where(['status' => StatusRequest::byKey('progressing_archi')->getValue()])->count();
        $caiProjPercentage = $notcaiProjCount == 0 ? 0 : ($caiProjCount / $notcaiProjCount) * 100;
        $newProjectRequestData = collect(DB::select(DB::raw('select count(r.id) as count FROM (SELECT * FROM requests WHERE YEAR(created_at) = YEAR(NOW()) AND requestable_type LIKE "%NewProjectRequest") r RIGHT JOIN (SELECT 1 AS month UNION SELECT 1 UNION SELECT 2 UNION SELECT 3 UNION SELECT 4 UNION SELECT 5 UNION SELECT 6 UNION SELECT 7 UNION SELECT 8 UNION SELECT 9 UNION SELECT 10 UNION SELECT 11 UNION SELECT 12) m ON MONTH(r.created_at) = m.month GROUP BY m.month ORDER BY m.month')))->pluck("count");

        $optProjectRequestData = collect(DB::select(DB::raw('select count(r.id) as count FROM (SELECT * FROM requests WHERE YEAR(created_at) = YEAR(NOW()) AND requestable_type LIKE "%OptimizationRequest") r RIGHT JOIN (SELECT 1 AS month UNION SELECT 1 UNION SELECT 2 UNION SELECT 3 UNION SELECT 4 UNION SELECT 5 UNION SELECT 6 UNION SELECT 7 UNION SELECT 8 UNION SELECT 9 UNION SELECT 10 UNION SELECT 11 UNION SELECT 12) m ON MONTH(r.created_at) = m.month GROUP BY m.month ORDER BY m.month')))->pluck("count");

        return view('cai/charts')->with('untreatedCount', $untreatedCount)->with('avgHours', $avgHours)->with('caiProjPercentage', $caiProjPercentage)->with('newProjectRequestData', $newProjectRequestData)->with('optProjectRequestData', $optProjectRequestData);

    }

    //End


    private function markNotificationAsReaded(ProjectRequest $projectRequest){
        $userNotifications = Auth::user()->unreadNotifications;
        for($i = 0; $i < sizeof($userNotifications); $i++) {
            if($userNotifications[$i]->data['projectRequest']['id'] == $projectRequest->id){
                $userNotifications[$i]->markAsRead();
                break;
            }
        }
    }
}
