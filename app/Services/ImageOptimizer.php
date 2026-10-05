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

    /**
     * Profile photos are stored 4:5 (max 800×1000) so every listing card, the profile
     * hero and the OG image crop them the same way. coverDown never upscales: a
     * photo smaller than 800×1000 keeps its pixels and just gets the 4:5 crop.
     * Crop is anchored to the top so heads stay in frame.
     *
     * Returns [cropped, uncropped] WebP-encoded strings from a single decode.
     * The uncropped copy (max 1600px) is kept so the crop can be redone later.
     */
    public static function encodeProfilePhoto(string $path): array
    {
        $image   = (new ImageManager(new Driver()))->read($path);
        $full    = (clone $image)->scaleDown(width: 1600, height: 1600)->toWebp(quality: 85);
        $cropped = $image->coverDown(800, 1000, 'top')->toWebp(quality: 85);

        return [(string) $cropped, (string) $full];
    }

    public static function saveProfilePhoto(UploadedFile $file, string $folder = 'profiles'): string
    {
        $uuid     = Str::uuid();
        $filename = $folder . '/' . $uuid . '.webp';

        try {
            [$cropped, $full] = self::encodeProfilePhoto($file->getPathname());

            Storage::disk('r2')->put($filename, $cropped, 'public');

            // Best-effort backup of the uncropped photo; never blocks the upload.
            try {
                Storage::disk('r2')->put($folder . '/full/' . $uuid . '.webp', $full, 'public');
            } catch (\Throwable $e) {
                // ignore
            }

            return Storage::disk('r2')->url($filename);
        } catch (\Throwable $e) {
            // R2 failed — fall back to local public disk
            $path = $file->store($folder, 'public');
            return asset('storage/' . $path);
        }
    }
}
