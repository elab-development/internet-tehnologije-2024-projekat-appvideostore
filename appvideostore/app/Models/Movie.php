<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Movie extends Model
{
    protected $fillable = [
        'title', 
        'release_date',
        'runtime',
        'average_vote',
        'tmdb_id'
    ];

    protected $casts = [
        'release_date' => 'date:Y-m-d',
        'runtime' => 'integer',
        'average_vote' => 'float',
        'vote_count' => 'integer'
    ];

    public function watchlists()
    {
        return $this->hasMany(Watchlist::class);
    }

    public function watchersCount()
    {
        return $this->watchlists()->count();
    }

    public function reviews()
{
    return $this->hasMany(Review::class);
}

}
