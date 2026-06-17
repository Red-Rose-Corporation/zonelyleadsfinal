<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class ImageOptimizer
{
    /**
     * Resize, convert to WebP, compress, and store a profile photo to R2.
     * Returns full public URL (https://...) to store in DB.
     */
    /**
     * Resize gallery photo: max 1200×900, WebP quality 85, stored in R2.
     * Returns full public URL or storage path.
     */
    public static function saveGalleryPhoto(UploadedFile $file): string
    {
        $filename = 'gallery/' . Str::uuid() . '.webp';

        try {
            $manager = new ImageManager(new Driver());
            $encoded = $manager->read($file->getPathname())
                ->scaleDown(width: 1600, height: 1200)
                ->toWebp(quality: 88);

            Storage::disk('r2')->put($filename, (string) $encoded, 'public');

            return Storage::disk('r2')->url($filename);
        } catch (\Throwable $e) {
            $path = $file->store('gallery', 'public');
            return asset('storage/' . $path);
        }
    }

    public static function saveProfilePhoto(UploadedFile $file, string $folder = 'profiles'): string
    {
        $filename = $folder . '/' . Str::uuid() . '.webp';

        try {
            $manager = new ImageManager(new Driver());
            $encoded = $manager->read($file->getPathname())
                ->scaleDown(width: 800)
                ->toWebp(quality: 82);

            Storage::disk('r2')->put($filename, (string) $encoded, 'public');

            return Storage::disk('r2')->url($filename);
        } catch (\Throwable $e) {
            // R2 failed — fall back to local public disk
            $path = $file->store($folder, 'public');
            return asset('storage/' . $path);
        }
    }
}
