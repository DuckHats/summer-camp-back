<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Photo extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'group_id',
        'image_url',
    ];

    public function group()
    {
        return $this->belongsTo(Group::class);
    }
}
