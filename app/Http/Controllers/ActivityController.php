<?php

namespace App\Http\Controllers;

use App\Services\ActivityService;
use Illuminate\Http\Request;

class ActivityController extends Controller
{
    private $activityService;

    public function __construct(ActivityService $activityService)
    {
        $this->activityService = $activityService;
    }

    public function index(Request $request)
    {
        return $this->activityService->getAllActivities($request);
    }

    public function store(Request $request)
    {
        return $this->activityService->createActivity($request);
    }

    public function show(Request $request, $id)
    {
        return $this->activityService->getActivityById($request, $id);
    }

    public function update(Request $request, $id)
    {
        return $this->activityService->updateActivity($request, $id);
    }

    public function destroy(Request $request, $id)
    {
        return $this->activityService->deleteActivity($request, $id);
    }

    public function patch(Request $request, $id)
    {
        return $this->activityService->patchActivity($request, $id);
    }
}
