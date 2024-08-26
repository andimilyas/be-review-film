<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reviews extends Model
{
    use HasFactory, HasUuids;

    protected $table = "reviews";
    protected $fillable = [
        'critic',
        'rating',
        'user_id',
        'movie_id'
    ];

    public function user()
    {
        return $this;
    }
    // public function reviewMovies()
    // {
    //     return $this->hasMany(Movies::class, 'movie_id');
    // }
}
