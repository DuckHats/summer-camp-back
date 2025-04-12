<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ScheduledActivity extends Model
{
    use HasFactory;

    protected $fillable = [
        'activity_id',
        'group_id',
        'initial_date',
        'final_date',
        'initial_hour',
        'final_hour',
        'location',
    ];

    public function activity()
    {
        return $this->belongsTo(Activity::class);
    }

    public function group()
    {
        return $this->belongsTo(Group::class);
    }
}
