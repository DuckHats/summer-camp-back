<?php

namespace App\Services;

use App\Http\Resources\PostResource;
use App\Models\Post;

class PostService extends BaseService
{
    public function __construct()
    {
        $this->model = new Post();
    }

    protected function getRelations(): array
    {
        return [];
    }

    protected function resourceClass()
    {
        return PostResource::class;
    }

    protected function getSyncableRelations(): array
    {
        return [];
    }
}
