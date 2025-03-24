<?php

namespace App\Services;

use App\Http\Resources\ChildResource;
use App\Models\Child;

class ChildService extends BaseService
{
    public function __construct()
    {
        $this->model = new Child;
    }

    protected function getRelations(): array
    {
        return [];
    }

    protected function resourceClass()
    {
        return ChildResource::class;
    }

    protected function getSyncableRelations(): array
    {
        return [];
    }
}
