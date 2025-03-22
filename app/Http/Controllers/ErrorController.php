<?php

namespace App\Http\Controllers;

use App\Services\ErrorService;
use Illuminate\Http\Request;

class ErrorController extends Controller
{
    private $errorService;

    public function __construct(ErrorService $errorService)
    {
        $this->errorService = $errorService;
    }

    public function index(Request $request)
    {
        return $this->errorService->getAll($request);
    }

    public function show(Request $request, $id)
    {
        return $this->errorService->getById($request, $id);
    }
    
    public function store(Request $request)
    {
        return $this->errorService->create($request);
    }

    public function update(Request $request, $id)
    {
        return $this->errorService->update($request, $id);
    }

    public function destroy(Request $request, $id)
    {
        return $this->errorService->delete($request, $id);
    }
}
