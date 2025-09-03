<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Video extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'filename',
        'path',
        'category',
    ];

    /**
     * Get the URL for the video.
     */
    public function getUrlAttribute()
    {
        return asset('storage/' . $this->path);
    }
}
