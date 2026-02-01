<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'phone_number',
        'user_role',
        'password',
        'code',
        'is_active',
        'closing_date',
        'is_profile_completed',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $appends = ['role_name'];

    public function getRoleNameAttribute()
    {
        $roles = config('user_roles.roles');
        return $roles[$this->user_role] ?? 'Unknown';
    }

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_active' => 'boolean',
            'is_profile_completed' => 'boolean',
            'closing_date' => 'date',
        ];
    }
    public function userProfile()
    {
        return $this->hasOne(UserProfile::class);
    }
}
