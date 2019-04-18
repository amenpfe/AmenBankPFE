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

        $inputs['status'] = StatusRequest::byKey("send")->getValue();
        
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

    public function getCDNewDetails($id){
        $request = ProjectRequest::find($id);
        return view('chd/new-request-details')->with('request', $request);
    }

    public function submitCDNewRequestForm(NewRequestDetailsRequest $newRequestDetailsRequest){
        $inputs = $newRequestDetailsRequest->all();
        $requestId = $inputs['requestId'];
        $request = ProjectRequest::find($requestId);

        $file = $newRequestDetailsRequest->file('chd_doc');
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

    //End CD
}   