<?php

namespace App\Models;

use App\Notifications\VerifyEmail;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'referred_by'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime:Y-m-d H:i:s',
        'created_at' => 'datetime:Y-m-d H:i:s',
        'updated_at' => 'datetime:Y-m-d H:i:s',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->referral_code = self::generateReferralCode();
        });
    }

    protected static function generateReferralCode()
    {
        $length = config('referral.code_length', 5);

        do {
            $referralCode = Str::random($length);
        } while (static::referralExists($referralCode));

        return $referralCode;
    }

    public function getReferralLink()
    {
        return route('register').'/?refer='.$this->referral_code;
    }

    public function sendEmailVerificationNotification()
    {
        $this->notify(new VerifyEmail);
    }

    public function updateReferralPoint()
    {
        $max_point = config('referral.point_limit');

        if($this->referral_point >= $max_point) return;

        $this->referral_point++;

        $this->save();
    }

    public static function scopeReferralExists(Builder $query, string $referralCode)
    {
        return $query->where('referral_code', $referralCode)->exists();
    }

    public function referer()
    {
        return $this->belongsTo(User::class, 'referred_by');
    }

    public function referees()
    {
        return $this->hasMany(User::class, 'referred_by');
    }

    public function referralInvites()
    {
        return $this->hasMany(UserReferralInvite::class);
    }
}
