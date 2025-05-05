<?php

namespace App\Http\Controllers;

use App\Models\ScheduledActivity;
use App\Services\ExportService;
use App\Services\ScheduledActivityService;
use Illuminate\Http\Request;

class ScheduledActivityController extends Controller
{
    private $scheduledActivityService;

    public function __construct(ScheduledActivityService $scheduledActivityService)
    {
        $this->scheduledActivityService = $scheduledActivityService;
    }

    public function index(Request $request)
    {
        return $this->scheduledActivityService->getAll($request);
    }

    public function show(Request $request, $id)
    {
        return $this->scheduledActivityService->getById($request, $id);
    }

    public function store(Request $request)
    {
        return $this->scheduledActivityService->create($request);
    }

    public function bulkScheduledActivities(Request $request)
    {
        return $this->scheduledActivityService->bulkScheduledActivities($request);
    }

    public function update(Request $request, $id)
    {
        return $this->scheduledActivityService->update($request, $id);
    }

    public function patch(Request $request, $id)
    {
        return $this->scheduledActivityService->patch($request, $id);
    }

    public function destroy(Request $request, $id)
    {
        return $this->scheduledActivityService->delete($request, $id);
    }

    public function export(Request $request)
    {
        $exportService = new ExportService(new ScheduledActivity);

        return $exportService->export($request);
    }
}
