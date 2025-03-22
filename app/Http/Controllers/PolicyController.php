<?php

namespace App\Http\Controllers;

use App\Services\PolicyService;
use Illuminate\Http\Request;

class PolicyController extends Controller
{
    private $policyService;

    public function __construct(PolicyService $policyService)
    {
        $this->policyService = $policyService;
    }

    public function index(Request $request)
    {
        return $this->policyService->getAll($request);
    }

    public function show(Request $request, $id)
    {
        return $this->policyService->getById($request, $id);
    }

    public function store(Request $request)
    {
        return $this->policyService->create($request);
    }

    public function update(Request $request, $id)
    {
        return $this->policyService->update($request, $id);
    }

    public function patch(Request $request, $id)
    {
        return $this->policyService->patch($request, $id);
    }

    public function destroy(Request $request, $id)
    {
        return $this->policyService->delete($request, $id);
    }
}
