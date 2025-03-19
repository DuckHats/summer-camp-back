<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Activity extends Model
{
    use HasFactory;

    protected $table = 'activities';

    protected $fillable = [
        'name',
        'initial_hour',
        'final_hour',
        'duration',
        'description',
        'cover_image',
        'location',
        'group_id',
    ];

    public function group()
    {
        return $this->belongsTo(Group::class);
    }

    public function days()
    {
        return $this->belongsToMany(Day::class)->withTimestamps();
    }
}
