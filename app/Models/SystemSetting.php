<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SystemSetting extends Model
{
    protected $table = 'system_settings';
    protected $guarded = [];
    protected $casts = [
        'free_credits_on_signup' => 'integer',
        'free_post_views' => 'integer',
        'default_post_credit_cost' => 'decimal:2',
        'credit_price' => 'decimal:2',
    ];
}
