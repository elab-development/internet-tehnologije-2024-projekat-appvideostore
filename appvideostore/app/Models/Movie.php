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

    public function watchlists()
    {
        return $this->hasMany(Watchlist::class);
    }

    public function watchersCount()
    {
        return $this->watchlists()->count();
    }



}
