<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    use HasFactory;
    protected $fillable = ['title', 'price', 'pricing_type', 'features', 'image_one', 'image_two', 'description', 'is_active', 'user_id', 'category_id', 'slug'];

    /** Most photos a single service can have. */
    public const MAX_PHOTOS = 3;

    protected static function boot()
    {
        parent::boot();
        // Remove the service's photo rows and files with it. Wrapped so it can never block a delete
        // (for example if the photos table is missing).
        static::deleting(function ($service) {
            try {
                foreach ($service->photos as $photo) {
                    $photo->deleteStoredFile();
                }
                $service->photos()->delete();
            } catch (\Throwable $e) {
                // ignore
            }
        });
        static::creating(function ($service) {
            if (empty($service->slug)) {
                $base = \Illuminate\Support\Str::slug($service->title);
                $slug = $base;
                $i = 1;
                while (static::where('slug', $slug)->exists()) {
                    $slug = $base . '-' . $i++;
                }
                $service->slug = $slug;
            }
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    /** Optional photos, main photo first. */
    public function photos()
    {
        return $this->hasMany(ServicePhoto::class)->orderBy('sort_order')->orderBy('id');
    }
}
