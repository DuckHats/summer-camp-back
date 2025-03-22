<?php

namespace App\Services;

use App\Http\Resources\PolicyResource;
use App\Models\Policy;

class PolicyService extends BaseService
{
    public function __construct()
    {
        $this->model = new Policy;
    }

    protected function getRelations(): array
    {
        return [];
    }

    protected function resourceClass()
    {
        return PolicyResource::class;
    }

    protected function getSyncableRelations(): array
    {
        return [];
    }
}
