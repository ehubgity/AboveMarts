<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'userId',
        'firstName',
        'lastName',
        'username',
        'email',
        'phoneNumber',
        'country',
        'mySponsorId',
        'sponsor',
        'status',
        'emailVerified',
        'password',
        'photo',
        'rank',
        'package',
        'point',
        'totalEarning',
        'accountNumber',
        'expectedEarning',
        'uplineOne',
        'uplineTwo',
        'uplineThree',
        'uplineFour',
        'uplineFive',
        'uplineSix',
        'uplineSeven',
        'downlineOne',
        'downlineTwo',
        'downlineThree',
        'downlineFour',
        'downlineFive',
        'downlineSix',
        'downlineSeven',

    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
    public function accountManagers()
    {
        return $this->belongsToMany(AccountManager::class, 'account_manager_user');
    }

    public function accountManager()
    {
        return $this->belongsTo(AccountManager::class, 'manager');
    }
}
