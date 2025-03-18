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
        return $this->sonService->getAllSons($request);
    }

    public function store(Request $request)
    {
        return $this->sonService->createSon($request);
    }

    public function show(Request $request, $id)
    {
        return $this->sonService->getSonById($request, $id);
    }

    public function update(Request $request, $id)
    {
        return $this->sonService->updateSon($request, $id);
    }

    public function destroy($id)
    {
        return $this->sonService->deleteSon($id);
    }

    public function patch(Request $request, $id)
    {
        return $this->sonService->patchSon($request, $id);
    }
}
