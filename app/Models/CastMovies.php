<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids; 


class CastMovies extends Model
{
    use HasFactory, HasUuids;

    protected $table = "cast_movie";
    protected $fillable = [
        'name',
        'cast_id',
        'movie_id'
    ];

    public function movie()
    {
        return $this->hasMany(Movies::class);
    }
    public function cast()
    {
        return $this->hasMany(Casts::class);
    }
}
