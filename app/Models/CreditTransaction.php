<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CreditTransaction extends Model
{
    protected $table = 'credit_transactions';

    const UPDATED_AT = null;

    protected $fillable = [
        'user_id',
        'type',
        'source',
        'reference_id',
        'credits',
        'balance_before',
        'balance_after',
        'created_at',
    ];

    protected $casts = [
        'credits' => 'integer',
        'balance_before' => 'integer',
        'balance_after' => 'integer',
        'created_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
