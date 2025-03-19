<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'profile_picture'];

    public function sons()
    {
        return $this->hasMany(Son::class);
    }

    public function activities()
    {
        return $this->hasMany(Activity::class);
    }
}
