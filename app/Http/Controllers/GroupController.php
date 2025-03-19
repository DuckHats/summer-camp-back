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
        return $this->groupService->getAllGroups($request);
    }

    public function store(Request $request)
    {
        return $this->groupService->createGroup($request);
    }

    public function show(Request $request, $id)
    {
        return $this->groupService->getGroupById($request, $id);
    }

    public function update(Request $request, $id)
    {
        return $this->groupService->updateGroup($request, $id);
    }

    public function destroy(Request $request, $id)
    {
        return $this->groupService->deleteGroup($request, $id);
    }

    public function patch(Request $request, $id)
    {
        return $this->groupService->patchGroup($request, $id);
    }
}
