<?php

namespace App\Services;

use App\Http\Resources\ScheduledActivityResource;
use App\Models\ScheduledActivity;

class ScheduledActivityService extends BaseService
{
    public function __construct()
    {
        $this->model = new ScheduledActivity;
    }

    protected function getRelations(): array
    {
        return ['activity'];
    }

    protected function resourceClass()
    {
        return ScheduledActivityResource::class;
    }

    protected function getSyncableRelations(): array
    {
        return ['activity'];
    }
}
