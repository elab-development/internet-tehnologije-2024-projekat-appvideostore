<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Watchlist extends Model
{
    protected $table = 'watchlists';

    protected $fillable = [
        'user_id',
        'movie_id',
        'status',
        'rating',
        'note',
        'added_at'
    ];

    protected $casts = [
        'status' => 'string',
        'rating' => 'integer',
        'added_at' => 'datetime'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function movie()
    {
        return $this->belongsTo(Movie::class);
    }

    public function isWatched()
    {
        return $this->status === 'watched';
    }
}
