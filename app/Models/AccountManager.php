<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class AccountManager extends Authenticatable
{
    use HasFactory, Notifiable;
    
    protected $guarded = [];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function users()
    {
        return $this->hasMany(User::class, 'manager');
    }

    // Optional: Keep pivot if needed, but we'll prioritize the direct 'manager' column
    public function assignedUsers()
    {
        return $this->belongsToMany(User::class, 'account_manager_user');
    }
}
