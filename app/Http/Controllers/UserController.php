<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\Request\RequestRepositoryInterface;
use Illuminate\Support\Facades\Input;
use App\Project;
use App\OptimizationRequest;
use App\NewProjectRequest;
use App\Enums\UserRole;
use App\User;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\NewProjectRequestRequest;
use App\Http\Requests\OptimizationRequestRequest;
use Illuminate\Support\Facades\Redirect;
use App\ProjectRequest;
use Illuminate\Config\Repository;
use App\Repositories\User\UserRepositoryInterface;

class UserController extends Controller
{
    protected $user;
    public function __construct(RequestRepositoryInterface $requestRepository, UserRepositoryInterface $userRepository)
    {
        $this->requestRepository = $requestRepository;
        $this->userRepository = $userRepository;
    }

    public function getNewProjectRequests() {
        return view('user-show-request')->with('newprojectrequests', NewProjectRequest::all());
    }

    public function getOptRequests() {
        return view('user-show-opt-requests')->with('optimizationRequests', OptimizationRequest::all());
    }

    public function getOptimizationRequestForm(){
        return view('user-optimization-request')->with('projects', Project::all());
    }

    public function getNewProjectRequestForm(){
        return view('user-new-request');
    }

    public function getRequestsFrom(){
        return view('user-show-request');
    }

    public function submitNewProjectRequestForm(NewProjectRequestRequest $newProjectRequestRequest){
        $inputs = $newProjectRequestRequest->all();
        $file = $newProjectRequestRequest->file('chd');
        $newFileName = 'chd_'  . str_random(8) . '.' . $file->getClientOriginalExtension();
        $file->move('files', $newFileName);

        $inputs['chd'] = $newFileName;
        $inputs['status'] = 1;
        $inputs['livrable'] = null;
        $inputs['user_id'] = 1;

        $this->requestRepository->saveNewProjectRequest(new NewProjectRequest(), $inputs);
        return redirect()->back();
    }

    public function submitOptimizationRequestForm(OptimizationRequestRequest $optimizationRequestRequest){
        $inputs = $optimizationRequestRequest->all();
        $file = $optimizationRequestRequest->file('chd');
        $newFileName = 'chd_'  . str_random(8) . '.' . $file->getClientOriginalExtension();
        $file->move('files', $newFileName);

        $inputs['chd'] = $newFileName;
        $inputs['status'] = 1;
        $inputs['livrable'] = null;
        $inputs['user_id'] = 1;

        $this->requestRepository->saveOptimizationRequest(new OptimizationRequest(), $inputs);
        return redirect()->back();
    }

    public function usersByRole($role){
        return print_r($this->userRepository->getUsersByRole($role));
    }

}
