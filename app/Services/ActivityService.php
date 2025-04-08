<?php

namespace App\Services;

use App\Http\Resources\ActivityResource;
use App\Models\Activity;

class ActivityService extends BaseService
{
    public function __construct()
    {
        $this->model = new Activity;
    }

    protected function getRelations(): array
    {
        return ['scheduledActivities'];
    }

    protected function resourceClass()
    {
        return ActivityResource::class;
    }

    protected function getSyncableRelations(): array
    {
        return ['scheduledActivities'];
    }
}
