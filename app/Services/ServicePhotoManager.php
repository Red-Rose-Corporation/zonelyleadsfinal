<?php

namespace App\Services;

use App\Models\Service;
use App\Models\ServicePhoto;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;

/**
 * Add, remove and reorder the optional photos of a service. Used by both the seller and the admin
 * screens so the rules live in one place. The main photo is the one with the lowest sort_order.
 */
class ServicePhotoManager
{
    /** Same minimum the upload forms validate: below this a photo looks soft once displayed. */
    public const MIN_WIDTH  = 800;
    public const MIN_HEIGHT = 600;

    /**
     * Laravel validation rule shared by every upload form.
     */
    public static function fileRule(): string
    {
        return 'required|image|mimes:jpg,jpeg,png,webp|max:10240|dimensions:min_width=' . self::MIN_WIDTH . ',min_height=' . self::MIN_HEIGHT;
    }

    /** Plain-language messages for every way the upload rule can fail. */
    public static function fileMessages(string $field = 'photo'): array
    {
        return [
            $field . '.required'   => 'Please choose a photo.',
            $field . '.uploaded'   => 'The photo could not be uploaded. It may be too large (10 MB is the limit). Please try a smaller one.',
            $field . '.image'      => 'Please choose a photo (JPG, PNG or WebP).',
            $field . '.mimes'      => 'Please use a JPG, PNG or WebP photo. If it came from an iPhone, share it as JPG.',
            $field . '.max'        => 'That photo is too large (10 MB is the limit). Please try a smaller one.',
            $field . '.dimensions' => 'That photo is too small and would look blurry. Please use one at least '
                . self::MIN_WIDTH . '×' . self::MIN_HEIGHT . ' px (1200×900 or larger is best).',
        ];
    }

    /**
     * @throws \DomainException when the service already has the maximum number of photos
     * @throws \RuntimeException when the photo could not be stored
     */
    public function add(Service $service, UploadedFile $file): ServicePhoto
    {
        if ($service->photos()->count() >= Service::MAX_PHOTOS) {
            throw new \DomainException('A service can have up to ' . Service::MAX_PHOTOS . ' photos. Remove one to add another.');
        }

        // Store first: if this fails nothing is written to the database.
        $path = ImageOptimizer::saveServicePhoto($file);

        try {
            return DB::transaction(function () use ($service, $path) {
                // Lock the service row so two uploads at the same moment cannot both pass the limit.
                Service::whereKey($service->id)->lockForUpdate()->first();

                if (ServicePhoto::where('service_id', $service->id)->count() >= Service::MAX_PHOTOS) {
                    throw new \DomainException('A service can have up to ' . Service::MAX_PHOTOS . ' photos. Remove one to add another.');
                }

                $next = (ServicePhoto::where('service_id', $service->id)->max('sort_order') ?? -1) + 1;

                return ServicePhoto::create([
                    'service_id' => $service->id,
                    'path'       => $path,
                    'sort_order' => $next,
                ]);
            });
        } catch (\Throwable $e) {
            (new ServicePhoto(['path' => $path]))->deleteStoredFile(); // do not leave an orphan file
            throw $e;
        }
    }

    public function remove(ServicePhoto $photo): void
    {
        $service = $photo->service;
        $file    = new ServicePhoto(['path' => $photo->path]);

        // Row first, file second: if the row cannot be deleted the photo stays intact and visible,
        // instead of a row pointing at a file that is already gone.
        $photo->delete();
        $file->deleteStoredFile();

        if ($service) {
            $this->resequence($service);
        }
    }

    /** Move a photo to the front so it becomes the main (thumbnail) photo. */
    public function makeMain(ServicePhoto $photo): void
    {
        $service = $photo->service;
        if (!$service) {
            return;
        }

        $ids = $service->photos()->pluck('id')->all();
        $ids = array_merge([$photo->id], array_values(array_diff($ids, [$photo->id])));
        $this->resequence($service, $ids);
    }

    /** Renumber sort_order 0..n-1 (optionally in the given id order). */
    public function resequence(Service $service, ?array $orderedIds = null): void
    {
        $orderedIds = $orderedIds ?? $service->photos()->pluck('id')->all();

        DB::transaction(function () use ($orderedIds) {
            foreach ($orderedIds as $i => $id) {
                ServicePhoto::where('id', $id)->update(['sort_order' => $i]);
            }
        });
    }
}
