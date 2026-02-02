<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'image',
        'is_active',
        'is_updated',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'is_updated' => 'boolean',
    ];

    public function attributes()
    {
        return $this->hasMany(CategoryAttribute::class)->orderBy('display_order', 'asc');
    }

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($category) {
            if (empty($category->slug)) {
                $category->slug = Str::slug($category->name);
            }
        });
    }
}
