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

    /** Best-effort removal of the stored file. Never throws: a stuck file must not block a delete. */
    public function deleteStoredFile(): void
    {
        $path = (string) $this->path;
        if ($path === '') {
            return;
        }

        try {
            if (str_starts_with($path, 'http')) {
                $key = ltrim((string) parse_url($path, PHP_URL_PATH), '/');
                if ($key !== '') {
                    Storage::disk('r2')->delete($key);
                }
            } else {
                delete_file($path);
            }
        } catch (\Throwable $e) {
            // ignore
        }
    }
}
