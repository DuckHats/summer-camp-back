<?php

namespace App\Services;

use App\Http\Resources\NotificationResource;
use App\Models\Notification;

class NotificationService extends BaseService
{
    public function __construct()
    {
        $this->model = new Notification;
    }

    protected function getRelations(): array
    {
        return [];
    }

    protected function resourceClass()
    {
        return NotificationResource::class;
    }

    protected function getSyncableRelations(): array
    {
        return [];
    }
}
