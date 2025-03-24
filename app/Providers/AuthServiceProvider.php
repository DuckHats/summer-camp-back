<?php

namespace App\Providers;

// use Illuminate\Support\Facades\Gate;

use App\Models\Activity;
use App\Models\Error;
use App\Models\Group;
use App\Models\Monitor;
use App\Models\Photo;
use App\Models\Policy;
use App\Models\Post;
use App\Models\Child;
use App\Models\User;
use App\Models\UserSetting;
use App\Policies\ActivityPolicy;
use App\Policies\ErrorPolicy;
use App\Policies\GroupPolicy;
use App\Policies\MonitorPolicy;
use App\Policies\PhotoPolicy;
use App\Policies\PolicyPolicy;
use App\Policies\PostPolicy;
use App\Policies\ChildPolicy;
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
        Child::class => ChildPolicy::class,
        Group::class => GroupPolicy::class,
        Activity::class => ActivityPolicy::class,
        Monitor::class => MonitorPolicy::class,
        Photo::class => PhotoPolicy::class,
        Error::class => ErrorPolicy::class,
        Policy::class => PolicyPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        $this->registerPolicies();

    }
}
