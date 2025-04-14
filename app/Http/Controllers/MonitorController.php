<?php

namespace App\Http\Controllers;

use App\Services\MonitorService;
use Illuminate\Http\Request;

class MonitorController extends Controller
{
    private $monitorService;

    public function __construct(MonitorService $monitorService)
    {
        $this->monitorService = $monitorService;
    }

    public function index(Request $request)
    {
        return $this->monitorService->getAll($request);
    }

    public function show(Request $request, $id)
    {
        return $this->monitorService->getById($request, $id);
    }

    public function store(Request $request)
    {
        return $this->monitorService->create($request, 'profile_picture');
    }

    public function bulkMonitors(Request $request)
    {
        return $this->monitorService->bulkMonitors($request);
    }

    public function update(Request $request, $id)
    {
        return $this->monitorService->update($request, $id);
    }

    public function patch(Request $request, $id)
    {
        return $this->monitorService->patch($request, $id);
    }

    public function destroy(Request $request, $id)
    {
        return $this->monitorService->delete($request, $id);
    }
}
