<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'monitor_id', 'profile_picture'];

    public function childs()
    {
        return $this->hasMany(Child::class);
    }

    public function scheduledActivities()
    {
        return $this->hasMany(ScheduledActivity::class);
    }

    public function monitor()
    {
        return $this->belongsTo(Monitor::class);
    }

    public function photos()
    {
        return $this->hasMany(Photo::class);
    }
}
