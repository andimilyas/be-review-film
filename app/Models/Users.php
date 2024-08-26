<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Concerns\HasUuids; 
use Tymon\JWTAuth\Contracts\JWTSubject;
use Carbon\Carbon;

class Users extends Authenticatable implements JWTSubject
{
    public static function boot()
    {
        parent::boot();

        static::created(function($model){
            $model->generateOtpCode();
        });
    }
    use HasApiTokens, HasFactory, Notifiable, HasUuids;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'password_confirmation',
        'role_id'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'password_confirmation',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }
    public function getJWTCustomClaims()
    {
        return [];
    }

    public function generateOtpCode() 
    {
        do {
            $randomNumber = mt_rand(100000, 999999);
            $checkOtpCode = OtpCode::where('otp', $randomNumber)->first();
        } while ($checkOtpCode);

        
        $now = Carbon::now();

        $otp_code = OtpCode::updateOrCreate(
            ['user_id'=> $this->id],
            [
                'otp' => $randomNumber,
                'valid_until' => $now->addMinutes(5)
            ]   
        );

    }
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
    
    //untuk relasi gunakan hasOne (jika 1:1) di tabel parent (yang ada FK nya)
    public function profile()
    {
        return $this->hasOne(Profiles::class, 'user_id');
    }
    public function role()
    {
        return $this->belongsTo(Roles::class, 'role_id');
    }
    public function historyReview()
    {
        return $this->belongsToMany(Movies::class, 'reviews', 'user_id', 'movie_id');
    }
    public function otpcode()
    {
        return $this->hasOne(OtpCode::class, 'user_id');
    }
}
