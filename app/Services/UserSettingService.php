<?php

namespace App\Services;

use App\Http\Resources\UserSettingResource;
use App\Models\UserSetting;

class UserSettingService extends BaseService
{
    public function __construct()
    {
        $this->model = new UserSetting;
    }

    protected function getRelations(): array
    {
        return [];
    }

    protected function resourceClass()
    {
        return UserSettingResource::class;
    }

    protected function getSyncableRelations(): array
    {
        return [];
    }
}
