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
use App\Models\Son;
use App\Models\User;
use App\Models\UserSetting;
use App\Policies\ActivityPolicy;
use App\Policies\ErrorPolicy;
use App\Policies\GroupPolicy;
use App\Policies\MonitorPolicy;
use App\Policies\NotificationPolicy;
use App\Policies\PhotoPolicy;
use App\Policies\PolicyPolicy;
use App\Policies\PostPolicy;
use App\Policies\SonPolicy;
use App\Policies\UserPolicy;
use App\Policies\UserSettingPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

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
        Error::class => ErrorPolicy::class,
        Policy::class => PolicyPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        $this->registerPolicies();

        // Gate::define('activity.create', [ActivityPolicy::class, 'create']);
        // Gate::define('activity.update', [ActivityPolicy::class, 'update']);
        // Gate::define('activity.delete', [ActivityPolicy::class, 'delete']);

        // Gate::define('error.create', [ErrorPolicy::class, 'create']);
        // Gate::define('error.update', [ErrorPolicy::class, 'update']);
        // Gate::define('error.delete', [ErrorPolicy::class, 'delete']);

        // Gate::define('group.create', [GroupPolicy::class, 'create']);
        // Gate::define('group.update', [GroupPolicy::class, 'update']);
        // Gate::define('group.delete', [GroupPolicy::class, 'delete']);

        // Gate::define('monitor.create', [MonitorPolicy::class, 'create']);
        // Gate::define('monitor.update', [MonitorPolicy::class, 'update']);
        // Gate::define('monitor.delete', [MonitorPolicy::class, 'delete']);

        // Gate::define('notification.create', [NotificationPolicy::class, 'create']);
        // Gate::define('notification.update', [NotificationPolicy::class, 'update']);
        // Gate::define('notification.delete', [NotificationPolicy::class, 'delete']);

        // Gate::define('photo.create', [PhotoPolicy::class, 'create']);
        // Gate::define('photo.update', [PhotoPolicy::class, 'update']);
        // Gate::define('photo.delete', [PhotoPolicy::class, 'delete']);

        // Gate::define('policy.create', [PolicyPolicy::class, 'create']);
        // Gate::define('policy.update', [PolicyPolicy::class, 'update']);
        // Gate::define('policy.delete', [PolicyPolicy::class, 'delete']);

        // Gate::define('post.create', [PostPolicy::class, 'create']);
        // Gate::define('post.update', [PostPolicy::class, 'update']);
        // Gate::define('post.delete', [PostPolicy::class, 'delete']);

        // Gate::define('son.create', [SonPolicy::class, 'create']);
        // Gate::define('son.update', [SonPolicy::class, 'update']);
        // Gate::define('son.delete', [SonPolicy::class, 'delete']);

        // Gate::define('setting.create', [UserSettingPolicy::class, 'create']);
        // Gate::define('setting.update', [UserSettingPolicy::class, 'update']);
        // Gate::define('setting.delete', [UserSettingPolicy::class, 'delete']);
    }
}
