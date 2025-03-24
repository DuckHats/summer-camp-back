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
        return ['childs', 'activities', 'monitor', 'photos', 'activities.days'];
    }

    protected function resourceClass()
    {
        return GroupResource::class;
    }

    protected function getSyncableRelations(): array
    {
        return ['activities', 'photos'];
    }
}
