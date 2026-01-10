<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    protected $table = 'reviews';

    protected $fillable = [
        'user_id',
        'movie_id',
        'rating',          
        'title',           
        'content',         
        'is_spoiler',      
        'helpful_count',   
    ];

    protected $casts = [
        'rating'       => 'integer',
        'is_spoiler'   => 'boolean',
        'helpful_count' => 'integer',
        'created_at'   => 'datetime',
        'updated_at'   => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function movie()
    {
        return $this->belongsTo(Movie::class);
    }

    public function isHelpful()
    {
        return $this->helpful_count > 0;
    }

    public function scopeHighlyRated($query)
    {
        return $query->where('rating', '>=', 8);
    }

    public function scopeNoSpoilers($query)
    {
        return $query->where('is_spoiler', false);
    }
}
