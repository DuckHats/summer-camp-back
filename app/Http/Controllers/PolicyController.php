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

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        return $this->policyService->getPolicy($request);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        return $this->policyService->createPolicy($request);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return $this->policyService->getPolicyById($id);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        return $this->policyService->updatePolicy($request, $id);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        return $this->policyService->deletePolicy($id);
    }

    /**
     * Partially update the specified resource in storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function patch(Request $request, $id)
    {
        return $this->policyService->patchPolicy($request, $id);
    }
}
