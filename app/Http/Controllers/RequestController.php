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
use Illuminate\Support\Facades\Redirect;
use App\Http\Requests\OptRequestDetailsRequest;
use App\OptimizationRequest;
use App\User;
use App\Notifications\DeploymentNotification;

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

        $this->requestRepository->saveNewProjectRequest(new NewProjectRequest(), $inputs);
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

        $this->requestRepository->saveOptimizationRequest(new OptimizationRequest(), $inputs);
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

        $inputs['status'] = StatusRequest::byKey("progressing_div")->getValue();
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

        $this->requestRepository->saveNewProjectRequest($request->requestable, $inputs);
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

        $inputs['status'] = StatusRequest::byKey("progressing_div")->getValue();
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
        return redirect()->route('get_chd_opt');
    }

    //All Requests

    public function getCDAllNewProjectRequests() {
        return view('chd/all-new-requests')->with('newprojectrequests', NewProjectRequest::all());
    }

    public function getCDAllOptRequests() {
        return view('chd/all-opt-requests')->with('optimizationRequests', OptimizationRequest::all());
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
        return redirect()->route('get_ced_opt');
    }

    //All Requests

    public function getCEDAllNewProjectRequests() {
        return view('ced/all-new-requests')->with('newprojectrequests', NewProjectRequest::all());
    }

    public function getCEDAllOptRequests() {
        return view('ced/all-opt-requests')->with('optimizationRequests', OptimizationRequest::all());
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
        return redirect()->route('get_prop_opt');
        
    }

    //All Requests

    public function getPropAllNewProjectRequests() {
        return view('prop/all-new-requests')->with('newprojectrequests', NewProjectRequest::all());
    }

    public function getPropAllOptRequests() {
        return view('chd/all-opt-requests')->with('optimizationRequests', OptimizationRequest::all());
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
        return redirect()->route('get_cdd_opt');
    }
    
    //CDD1

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
        return redirect()->route('get_cddp_opt');
    }
    
    //All Requests

    public function getCDDAllNewProjectRequests() {
        return view('cdd/all-new-requests')->with('newprojectrequests', NewProjectRequest::all());
    }

    public function getCDDAllOptRequests() {
        return view('cdd/all-opt-requests')->with('optimizationRequests', OptimizationRequest::all());
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
        return redirect()->route('get_cdq_opt');
    }

    //All Requests

    public function getCDQAllNewProjectRequests() {
        return view('cdq/all-new-requests')->with('newprojectrequests', NewProjectRequest::all());
    }

    public function getCDQAllOptRequests() {
        return view('cdq/all-opt-requests')->with('optimizationRequests', OptimizationRequest::all());
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
        return redirect()->route('get_org_opt');
    }

    //All Requests

    public function getORGAllNewProjectRequests() {
        return view('organisation/all-new-requests')->with('newprojectrequests', NewProjectRequest::all());
    }

    public function getORGAllOptRequests() {
        return view('organisation/all-opt-requests')->with('optimizationRequests', OptimizationRequest::all());
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
        return redirect()->route('get_ds_new');

        $user->notify(new DeploymentNotification($name));
        return redirect()->back();
    
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

        $user->notify(new DeploymentNotification($name));

        $this->requestRepository->saveOptimizationRequest($request->requestable, $inputs);
        return redirect()->route('get_ds_opt');
    }

    //All Requests

    public function getDSAllNewProjectRequests() {
        return view('ds/all-new-requests')->with('newprojectrequests', NewProjectRequest::all());
    }

    public function getDSAllOptRequests() {
        return view('ds/all-opt-requests')->with('optimizationRequests', OptimizationRequest::all());
    }

    //End DS

      //dv

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
        return redirect()->route('get_dv_opt');
    }


    //dvsec

    public function getdvsecNewProjectRequest(){ 
        return view('dv/new-sec-table')->with('newprojectrequests', 
            ProjectRequest::where(['requestable_type' => 'App\NewProjectRequest', 'status' => StatusRequest::byKey('progressing_devlop')->getValue()])->get());
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
        return redirect()->route('get_sec_new');
    }

    //opt request

    public function getdvsecOptRequest(){ 
        return view('dv/opt-sec-table')->with('optimizationRequests', 
            ProjectRequest::where(['requestable_type' => 'App\OptimizationRequest', 'status' => StatusRequest::byKey('progressing_devlop')->getValue()])->get());
    }

    public function getdvsecPOptDetails($id){
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
        return redirect()->route('get_sec_opt');
    }
    
    //All Requests

    public function getdvAllNewProjectRequests() {
        return view('dv/all-new-requests')->with('newprojectrequests', NewProjectRequest::all());
    }

    public function getdvAllOptRequests() {
        return view('dv/all-opt-requests')->with('optimizationRequests', OptimizationRequest::all());
    }

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

    public function submitcaiNewRequestForm(NewRequestDetailsRequest $newRequestDetailsRequest){
        $inputs = $newRequestDetailsRequest->all();
        $requestId = $inputs['requestId'];
        $request = ProjectRequest::find($requestId);

        $file = $newRequestDetailsRequest->file('doc');
        $newFileName = 'conception_doc'  . str_random(8) . '.' . $file->getClientOriginalExtension();
        $file->move('files', $newFileName);

        $inputs['status'] = StatusRequest::byKey("progressing_devlop")->getValue();
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
        return redirect()->route('get_cai_new');
    }
    public function caiAcceptOptRequest($id){
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
        return redirect()->route('get_cai_new');
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
        return redirect()->route('get_cai_opt');
    }

    //opt request

    public function getcaiOptRequest(){ 
        return view('cai/opt-request-table')->with('optimizationRequests', 
            ProjectRequest::where(['requestable_type' => 'App\OptimizationRequest', 'status' => StatusRequest::byKey('progressing_devlop')->getValue()])->get());
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


}   
