<?php

namespace App\Http\Controllers;

use App\Models\Child;
use App\Services\ChildService;
use App\Services\ExportService;
use Illuminate\Http\Request;

class ChildController extends Controller
{
    private $childService;

    public function __construct(ChildService $childService)
    {
        $this->childService = $childService;
    }

    public function index(Request $request)
    {
        return $this->childService->getAll($request);
    }

    public function store(Request $request)
    {
        return $this->childService->create($request, 'profile_picture_url');
    }

    public function show(Request $request, $id)
    {
        return $this->childService->getById($request, $id);
    }

    public function update(Request $request, $id)
    {
        return $this->childService->update($request, $id);
    }

    public function patch(Request $request, $id)
    {
        return $this->childService->patch($request, $id);
    }

    public function uploadImage(Request $request, $id)
    {
        return $this->childService->uploadImage($request, $id, 'profile_picture_url');
    }

    public function destroy(Request $request, $id)
    {
        return $this->childService->delete($request, $id);
    }

    public function inspect(Request $request, $id)
    {
        return $this->childService->inspectChild($request, $id);
    }

    public function multipleInspect(Request $request)
    {
        return $this->childService->multipleInspect($request);
    }

    public function getActivitiesByDay(Request $request)
    {
        return $this->childService->getActivitiesByDay($request);
    }

    public function export(Request $request)
    {
        $exportService = new ExportService(new Child);

        return $exportService->export($request);
    }
}
