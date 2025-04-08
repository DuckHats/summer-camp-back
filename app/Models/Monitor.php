<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Monitor extends Model
{
    use HasFactory;

    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'phone',
        'extra_info',
        'profile_picture',
    ];

    public function groups()
    {
        return $this->hasMany(Group::class);
    }
}
