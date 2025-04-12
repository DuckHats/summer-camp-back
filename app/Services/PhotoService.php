<?php

namespace App\Services;

use App\Http\Resources\PhotoResource;
use App\Models\Photo;

class PhotoService extends BaseService
{
    public function __construct()
    {
        $this->model = new Photo;
    }

    protected function getRelations(): array
    {
        return [];
    }

    protected function resourceClass()
    {
        return PhotoResource::class;
    }

    protected function getSyncableRelations(): array
    {
        return [];
    }
}
