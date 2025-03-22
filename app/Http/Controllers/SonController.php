<?php

namespace App\Http\Controllers;

use App\Services\SonService;
use Illuminate\Http\Request;

class SonController extends Controller
{
    private $sonService;

    public function __construct(SonService $sonService)
    {
        $this->sonService = $sonService;
    }

    public function index(Request $request)
    {
        return $this->sonService->getAll($request);
    }

    public function store(Request $request)
    {
        return $this->sonService->create($request);
    }

    public function show(Request $request, $id)
    {
        return $this->sonService->getById($request, $id);
    }

    public function update(Request $request, $id)
    {
        return $this->sonService->update($request, $id);
    }

    public function patch(Request $request, $id)
    {
        return $this->sonService->patch($request, $id);
    }

    public function destroy(Request $request, $id)
    {
        return $this->sonService->delete($request, $id);
    }
}
