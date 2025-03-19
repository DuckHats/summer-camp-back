<?php

namespace App\Providers;

// use Illuminate\Support\Facades\Gate;

use App\Models\Activity;
use App\Models\Group;
use App\Models\Monitor;
use App\Models\Photo;
use App\Models\Post;
use App\Models\Son;
use App\Models\User;
use App\Models\UserSetting;
use App\Policies\ActivityPolicy;
use App\Policies\GroupPolicy;
use App\Policies\MonitorPolicy;
use App\Policies\PhotoPolicy;
use App\Policies\PostPolicy;
use App\Policies\SonPolicy;
use App\Policies\UserPolicy;
use App\Policies\UserSettingPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        Post::class => PostPolicy::class,
        User::class => UserPolicy::class,
        UserSetting::class => UserSettingPolicy::class,
        Son::class => SonPolicy::class,
        Group::class => GroupPolicy::class,
        Activity::class => ActivityPolicy::class,
        Monitor::class => MonitorPolicy::class,
        Photo::class => PhotoPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        $this->registerPolicies();

    }
}
