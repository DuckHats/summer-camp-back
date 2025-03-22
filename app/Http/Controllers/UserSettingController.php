<?php

namespace App\Http\Controllers;

use App\Services\UserSettingService;
use Illuminate\Http\Request;

class UserSettingController extends Controller
{
    private $userSettingService;

    public function __construct(UserSettingService $userSettingService)
    {
        $this->userSettingService = $userSettingService;
    }

    public function index(Request $request)
    {
        return $this->userSettingService->getAll($request);
    }

    public function show(Request $request, $id)
    {
        return $this->userSettingService->getById($request, $id);
    }

    public function store(Request $request)
    {
        return $this->userSettingService->create($request);
    }

    public function update(Request $request, $id)
    {
        return $this->userSettingService->update($request, $id);
    }

    public function patch(Request $request, $id)
    {
        return $this->userSettingService->patch($request, $id);
    }

    public function destroy(Request $request, $id)
    {
        return $this->userSettingService->delete($request, $id);
    }
}
