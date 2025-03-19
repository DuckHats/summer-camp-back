<?php

namespace App\Http\Controllers;

use App\Services\MonitorService;
use Illuminate\Http\Request;

class MonitorController extends Controller
{
    private $monitorController;

    public function __construct(MonitorService $monitorController)
    {
        $this->monitorController = $monitorController;
    }

    public function index(Request $request)
    {
        return $this->monitorController->getAllMonitors($request);
    }

    public function store(Request $request)
    {
        return $this->monitorController->createMonitor($request);
    }

    public function show(Request $request, $id)
    {
        return $this->monitorController->getMonitorById($request, $id);
    }

    public function update(Request $request, $id)
    {
        return $this->monitorController->updateMonitor($request, $id);
    }

    public function destroy($id)
    {
        return $this->monitorController->deleteMonitor($id);
    }

    public function patch(Request $request, $id)
    {
        return $this->monitorController->patchMonitor($request, $id);
    }
}
