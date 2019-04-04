<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\Request\RequestRepositoryInterface;
use Illuminate\Support\Facades\Input;
use App\Project;
use App\OptimizationRequest;
use App\NewProjectRequest;

class UserController extends Controller
{
    public function __construct(RequestRepositoryInterface $requestRepository)
    {
        $this->requestRepository = $requestRepository;
    }
    public function getOptimizationRequestForm(){
        return view('user-optimization-request')->with('projects', Project::all());
    }

    public function submitOptimizationRequestForm(){
        $inputs['type'] = 1;
        $inputs['project_id'] = 1;

        $inputs['status'] = 1;
        $inputs['remarques'] = "Remarque";
        $inputs['livrable'] = "Test Livrable";
        $inputs['user_id'] = 1;
        $this->requestRepository->saveOptimizationRequest(new OptimizationRequest(), $inputs);
    }

    public function submitNewProjectRequestForm(){
        $inputs['title'] = "New Project";

        $inputs['status'] = 1;
        $inputs['remarques'] = "Remarque";
        $inputs['livrable'] = "Test Livrable";
        $inputs['user_id'] = 1;
        $this->requestRepository->saveNewProjectRequest(new NewProjectRequest(), $inputs);
    }

    public function updateOptimizationRequest(){
        $optReq = OptimizationRequest::find(18);
        $inputs['status'] = 2;
        
        $inputs['type'] = $optReq->type;
        $inputs['project_id'] = $optReq->project_id;

        $inputs['remarques'] = $optReq->request->remarques;
        $inputs['livrable'] = $optReq->request->livrable;
        $inputs['user_id'] = $optReq->request->user_id;
        
        $this->requestRepository->saveOptimizationRequest($optReq, $inputs);
    }

}
