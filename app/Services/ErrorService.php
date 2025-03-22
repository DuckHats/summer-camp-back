<?php

namespace App\Services;

use App\Http\Resources\ErrorResource;
use App\Models\Error;

class ErrorService extends BaseService
{
    public function __construct()
    {
        $this->model = new Error;
    }

    protected function getRelations(): array
    {
        return [];
    }

    protected function resourceClass()
    {
        return ErrorResource::class;
    }

    protected function getSyncableRelations(): array
    {
        return [];
    }
}
