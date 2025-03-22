<?php

namespace App\Services;

use App\Http\Resources\MonitorResource;
use App\Models\Monitor;

class MonitorService extends BaseService
{
    public function __construct()
    {
        $this->model = new Monitor();
    }

    protected function getRelations(): array
    {
        return [];
    }

    protected function resourceClass()
    {
        return MonitorResource::class;
    }

    protected function getSyncableRelations(): array
    {
        return [];
    }

}
