<?php

namespace App\Services;

use App\Http\Resources\GroupResource;
use App\Models\Group;

class GroupService extends BaseService
{
    public function __construct()
    {
        $this->model = new Group;
    }

    protected function getRelations(): array
    {
        return ['childs', 'scheduledActivities', 'monitor', 'photos', 'scheduledActivities.activity'];
    }

    protected function resourceClass()
    {
        return GroupResource::class;
    }

    protected function getSyncableRelations(): array
    {
        return ['scheduledActivities', 'photos', 'scheduledActivities.activity'];
    }
}
