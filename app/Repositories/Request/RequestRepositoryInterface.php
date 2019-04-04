<?php

namespace App\Repositories\Request;

use App\OptimizationRequest;
use App\NewProjectRequest;



interface RequestRepositoryInterface {
    public function saveOptimizationRequest(OptimizationRequest $optimizationRequest, $inputs);
    public function saveNewProjectRequest(NewProjectRequest $newProjectRequest, $inputs);
}