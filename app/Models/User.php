<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    const STATUS_INACTIVE = 0;

    const STATUS_ACTIVE = 1;

    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'username',
        'phone',
        'email',
        'status',
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'password' => 'hashed',
    ];

    public function childs()
    {
        return $this->hasMany(Child::class);
    }

    public function posts()
    {
        return $this->hasMany(Post::class);
    }

    public function settings()
    {
        return $this->hasMany(UserSetting::class);
    }

    public function policies()
    {
        return $this->hasMany(Policy::class);
    }

    public function notifications()
    {
        return $this->hasMany(Notification::class, 'user_id');
    }

    public function userLastLogin()
    {
        return $this->hasMany(UserLastLogin::class);
    }

    public function passwordResets()
    {
        return $this->hasMany(PasswordReset::class, 'email', 'email');
    }

    public function isAdmin()
    {
        return $this->settings()->where('key', 'role')->where('value', 'admin')->exists();
    }
}
