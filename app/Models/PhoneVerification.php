<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PhoneVerification extends Model
{
    use HasFactory;

    protected $fillable = [
        'phone',
        'verification_code',
        'expires_at',
    ];

    protected $casts = [
        'expires_at' => 'datetime',
    ];
}
