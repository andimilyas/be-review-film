<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Profiles extends Model
{
    use HasFactory, HasUuids;

    protected $table = "profiles";
    protected $fillable = [
        'age',
        'biodata',
        'address',
        'user_id'
    ];

    //untuk relasi gunakan belongsTo di tabel child (yang tidak ada FK nya)
    public function user()
    {
        return $this->belongsTo(Users::class, 'user_id');
    }
}
