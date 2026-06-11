<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SellerGallery extends Model
{
    protected $fillable = ['user_id', 'image_path', 'caption', 'sort_order'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getImageUrlAttribute(): string
    {
        return str_starts_with($this->image_path, 'http')
            ? $this->image_path
            : asset('storage/' . $this->image_path);
    }
}
