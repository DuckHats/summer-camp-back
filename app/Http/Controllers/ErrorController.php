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
        return $this->errorService->getAllErrors($request);
    }

    public function store(Request $request)
    {
        return $this->errorService->createError($request);
    }

    public function show($id)
    {
        return $this->errorService->getErrorById($id);
    }

    public function update(Request $request, $id)
    {
        return $this->errorService->updateError($request, $id);
    }

    public function destroy($id)
    {
        return $this->errorService->deleteError($id);
    }
}
