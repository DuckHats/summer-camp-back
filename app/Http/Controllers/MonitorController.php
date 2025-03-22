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
        return $this->monitorController->getAll($request);
    }

    public function show(Request $request, $id)
    {
        return $this->monitorController->getById($request, $id);
    }

    public function store(Request $request)
    {
        return $this->monitorController->create($request);
    }

    public function update(Request $request, $id)
    {
        return $this->monitorController->update($request, $id);
    }

    public function patch(Request $request, $id)
    {
        return $this->monitorController->patch($request, $id);
    }

    public function destroy(Request $request, $id)
    {
        return $this->monitorController->delete($request, $id);
    }
}
