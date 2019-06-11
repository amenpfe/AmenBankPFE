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
        $request->user_doc = $inputs['user_doc'];
        $request->logiciel_doc = $inputs['logiciel_doc'];
        $request->ced_doc = $inputs['ced_doc'];
        $request->organisation_doc = $inputs['organisation_doc'];
        $request->chd_doc = $inputs['chd_doc'];
        $request->analyse_doc = $inputs['analyse_doc'];
        $request->conception_doc = $inputs['conception_doc'];
        $request->test_doc = $inputs['test_doc'];
        $request->recette_doc = $inputs['recette_doc'];
        $request->circulaire_doc = $inputs['circulaire_doc'];
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
        $request->user_doc = $inputs['user_doc'];
        $request->logiciel_doc = $inputs['logiciel_doc'];
        $request->ced_doc = $inputs['ced_doc'];
        $request->organisation_doc = $inputs['organisation_doc'];
        $request->chd_doc = $inputs['chd_doc'];
        $request->analyse_doc = $inputs['analyse_doc'];
        $request->conception_doc = $inputs['conception_doc'];
        $request->test_doc = $inputs['test_doc'];
        $request->recette_doc = $inputs['recette_doc'];
        $request->circulaire_doc = $inputs['circulaire_doc'];
        $request->user_id = $inputs['user_id'];
        $request->save();

        $optimizationRequest->request()->save($request);
    }
}