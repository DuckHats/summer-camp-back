<?php

namespace App\Http\Controllers;

use App\Services\GroupService;
use Illuminate\Http\Request;

class GroupController extends Controller
{
    private $groupService;

    public function __construct(GroupService $groupService)
    {
        $this->groupService = $groupService;
    }

    public function index(Request $request)
    {
        return $this->groupService->getAll($request);
    }

    public function store(Request $request)
    {
        return $this->groupService->createGroupWithImage($request);
    }

    public function show(Request $request, $id)
    {
        return $this->groupService->getById($request, $id);
    }

    public function update(Request $request, $id)
    {
        return $this->groupService->update($request, $id);
    }

    public function patch(Request $request, $id)
    {
        return $this->groupService->patch($request, $id);
    }

    public function destroy(Request $request, $id)
    {
        return $this->groupService->delete($request, $id);
    }
}
