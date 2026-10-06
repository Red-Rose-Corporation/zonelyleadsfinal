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
        return self::withImageMemory($path, function () use ($path) {
            // One decode only. Cloning the full-size image doubled the memory and exhausted the
            // 128 MB default limit on ordinary 8-12 megapixel phone photos. Shrink to at most 1600px
            // first (a small image), keep that as the uncropped copy, and crop the 4:5 version from it.
            $image = (new ImageManager(new Driver()))->read($path);
            $image->scaleDown(width: 1600, height: 1600);
            $full    = (string) $image->toWebp(quality: 85);
            $cropped = (string) $image->coverDown(800, 1000, 'top')->toWebp(quality: 85);

            return [$cropped, $full];
        });
    }

    /** Largest photo we will decode, in pixels (about 8400 x 8400). */
    private const MAX_PIXELS = 70000000;

    /**
     * GD holds the whole decoded photo in memory (about 6 bytes per pixel at peak). PHP's default
     * 128 MB limit is enough for roughly 16 megapixels; a 48 MP phone photo ends the request with a
     * fatal error that cannot be caught. Raise the limit just for this job when the photo needs it,
     * refuse absurdly large ones with a message, and put the limit back afterwards.
     *
     * @throws \DomainException if the photo is too large to process safely
     */
    private static function withImageMemory(string $path, callable $job)
    {
        $info = @getimagesize($path);
        $old  = ini_get('memory_limit');

        if ($info) {
            $pixels = (int) $info[0] * (int) $info[1];
            if ($pixels > self::MAX_PIXELS) {
                throw new \DomainException('That photo is extremely large. Please use a smaller one (for example a lower camera resolution).');
            }

            $needMb  = (int) ceil($pixels * 6 / 1048576) + 64;
            $limitMb = self::memoryLimitMb($old);
            if ($limitMb !== -1 && $limitMb < $needMb) {
                @ini_set('memory_limit', $needMb . 'M');
            }
        }

        try {
            return $job();
        } finally {
            if ($old !== false && ini_get('memory_limit') !== $old) {
                @ini_set('memory_limit', $old);
            }
        }
    }

    /** PHP memory_limit ("128M", "1G", "-1", or plain bytes) as megabytes; -1 means unlimited. */
    private static function memoryLimitMb($value): int
    {
        $value = trim((string) $value);
        if ($value === '' || $value === '-1') {
            return -1;
        }
        $n = (float) $value;
        switch (strtoupper(substr($value, -1))) {
            case 'G': return (int) ($n * 1024);
            case 'M': return (int) $n;
            case 'K': return (int) ($n / 1024);
            default:  return (int) ($n / 1048576);
        }
    }

    /**
     * Service photo: 4:3 crop, at most 1200x900, WebP. coverDown never enlarges, so a small image
     * cannot come out blurry (callers also enforce a minimum size). Landscape and square images are
     * cropped from the centre, portrait ones keep the top so heads stay in frame.
     *
     * Throws if the photo could not be stored anywhere: the R2 disk has `throw => false`, so put()
     * returns false on failure instead of raising, and that must not be saved as a good photo.
     * Returns the public URL (R2) or a local public-disk path.
     */
    public static function saveServicePhoto(UploadedFile $file): string
    {
        $webp = self::withImageMemory($file->getPathname(), function () use ($file) {
            $image  = (new ImageManager(new Driver()))->read($file->getPathname());
            $anchor = $image->width() >= $image->height() ? 'center' : 'top';

            return (string) $image->coverDown(1200, 900, $anchor)->toWebp(quality: 85);
        });

        $filename = 'services/' . Str::uuid() . '.webp';

        try {
            if (Storage::disk('r2')->put($filename, $webp, 'public') !== false) {
                return Storage::disk('r2')->url($filename);
            }
        } catch (\Throwable $e) {
            // fall through to the local disk
        }

        if (Storage::disk('public')->put($filename, $webp) === false) {
            throw new \RuntimeException('Could not store the service photo.');
        }

        // Relative path, so ServicePhoto builds the URL and delete_file() can remove it later.
        return $filename;
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
