<?php

namespace App\Http\Controllers;

use App\Services\NotificationService;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    private $notificationService;

    public function __construct(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }

    public function index(Request $request)
    {
        return $this->notificationService->getAll($request);
    }

    public function store(Request $request)
    {
        return $this->notificationService->create($request);
    }

    public function show(Request $request, $id)
    {
        return $this->notificationService->getById($request, $id);
    }

    public function update(Request $request, $id)
    {
        return $this->notificationService->update($request, $id);
    }

    public function patch(Request $request, $id)
    {
        return $this->notificationService->patch($request, $id);
    }

    public function destroy(Request $request, $id)
    {
        return $this->notificationService->delete($request, $id);
    }
}
