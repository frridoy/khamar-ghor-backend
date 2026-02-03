<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserCredit extends Model
{
    protected $table = 'user_credits';

    protected $fillable = [
        'user_id',
        'total_earned',
        'total_spent',
        'balance_credit',
    ];

    protected $casts = [
        'total_earned' => 'integer',
        'total_spent' => 'integer',
        'balance_credit' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
