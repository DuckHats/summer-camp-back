<?php

namespace App\Http\Controllers;

use App\Services\ActivityService;
use App\Services\ExportService;
use Illuminate\Http\Request;
use App\Models\Activity;

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

    public function bulkActivities(Request $request)
    {
        return $this->activityService->bulkActivities($request);
    }

    public function update(Request $request, $id)
    {
        return $this->activityService->update($request, $id);
    }

    public function patch(Request $request, $id)
    {
        return $this->activityService->patch($request, $id);
    }

    public function uploadImage(Request $request, $id)
    {
        return $this->activityService->uploadImage($request, $id, 'cover_image');
    }

    public function destroy(Request $request, $id)
    {
        return $this->activityService->delete($request, $id);
    }

    public function export(Request $request)
    {
        $exportService = new ExportService(new Activity);
        return $exportService->export($request);
    }
}
