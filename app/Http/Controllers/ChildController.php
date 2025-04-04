<?php

namespace App\Http\Controllers;

use App\Services\ChildService;
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
        return $this->childService->create($request);
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
}
