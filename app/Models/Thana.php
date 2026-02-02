<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Thana extends Model
{
    protected $guarded = [];
    protected $table = 'thanas';

    public function district()
    {
        return $this->belongsTo(District::class);
    }
}
