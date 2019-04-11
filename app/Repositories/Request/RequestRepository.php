<?php

namespace App\Repositories\Request;

use App\OptimizationRequest;
use App\NewProjectRequest;
use App\ProjectRequest;
use Illuminate\Support\Facades\Log;

class RequestRepository implements RequestRepositoryInterface {

    public function saveNewProjectRequest(NewProjectRequest $newProjectRequest, $inputs){
        $newProjectRequest->title = $inputs['title'];
        $newProjectRequest->save();

        $request = $newProjectRequest->request;
        if ($request == null) {
            $request = new ProjectRequest();
        }
        $request->status = $inputs['status'];
        $request->remarques = $inputs['remarques'];
        $request->chd = $inputs['chd'];
        $request->livrable = $inputs['livrable'];
        $request->user_id = $inputs['user_id'];
        $request->save();

        $newProjectRequest->request()->save($request);
    }

    public function saveOptimizationRequest(OptimizationRequest $optimizationRequest, $inputs) {
        $optimizationRequest->type = $inputs['type'];
        $optimizationRequest->project_id = $inputs['project_id'];
        $optimizationRequest->save();

        $request = $optimizationRequest->request;
        if($request == null){
            $request = new ProjectRequest();
        }

        $request->status = $inputs['status'];
        $request->remarques = $inputs['remarques'];
        $request->chd = $inputs['chd'];
        $request->livrable = $inputs['livrable'];
        $request->user_id = $inputs['user_id'];
        $request->save();

        $optimizationRequest->request()->save($request);
    }
}