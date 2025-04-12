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
        return $this->activityService->getAll($request);
    }

    public function show(Request $request, $id)
    {
        return $this->activityService->getById($request, $id);
    }

    public function store(Request $request)
    {
        return $this->activityService->create($request, 'cover_image');
    }

    public function update(Request $request, $id)
    {
        return $this->activityService->update($request, $id);
    }

    public function patch(Request $request, $id)
    {
        return $this->activityService->patch($request, $id);
    }

    public function destroy(Request $request, $id)
    {
        return $this->activityService->delete($request, $id);
    }
}
