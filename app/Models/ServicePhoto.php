<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class ServicePhoto extends Model
{
    protected $fillable = ['service_id', 'path', 'sort_order'];

    public function service()
    {
        return $this->belongsTo(Service::class);
    }

    /** Public URL for the photo, whether it was stored as a full URL (R2) or a local path. */
    public function getUrlAttribute(): string
    {
        $path = (string) $this->path;

        return str_starts_with($path, 'http') ? $path : asset('storage/' . ltrim($path, '/'));
    }

    /** Small version for the closed card row. Older photos without one fall back via the page's onerror. */
    public function getThumbUrlAttribute(): string
    {
        return preg_replace('/\.webp$/', '_t.webp', $this->url);
    }

    /** Best-effort removal of the stored file. Never throws: a stuck file must not block a delete. */
    public function deleteStoredFile(): void
    {
        $path = (string) $this->path;
        if ($path === '') {
            return;
        }

        foreach ([$path, preg_replace('/\.webp$/', '_t.webp', $path)] as $one) {
            try {
                if (str_starts_with($one, 'http')) {
                    $key = ltrim((string) parse_url($one, PHP_URL_PATH), '/');
                    if ($key !== '') {
                        Storage::disk('r2')->delete($key);
                    }
                } else {
                    delete_file($one);
                }
            } catch (\Throwable $e) {
                // ignore
            }
        }
    }
}
