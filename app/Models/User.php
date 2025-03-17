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

    const STATUS_PERMABAN = 2;

    const STATUS_TIMEOUT = 3;

    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'username',
        'first_name',
        'last_name',
        'status',
        'email',
        'password',
        'phone',
        'profile_picture_url',
        'profile_short_description',
        'profile_description',
        'gender',
        'location',
        'birth_date',
        'cv_path',
        'portfolio_url',
        'level',
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
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'phone_verified' => 'datetime',
        'birth_date' => 'date',
    ];

    // public function setPasswordAttribute($value)
    // {
    //     $this->attributes['password'] = bcrypt($value);
    // }

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
}
