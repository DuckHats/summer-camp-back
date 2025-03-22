<?php

namespace App\Services;

use App\Http\Resources\SonResource;
use App\Models\Son;

class SonService extends BaseService
{
    public function __construct()
    {
        $this->model = new Son;
    }

    protected function getRelations(): array
    {
        return [];
    }

    protected function resourceClass()
    {
        return SonResource::class;
    }

    protected function getSyncableRelations(): array
    {
        return [];
    }
}
