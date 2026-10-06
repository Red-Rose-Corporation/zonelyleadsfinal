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

    public static function fileMessages(string $field = 'photo'): array
    {
        return [
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
                $next = ($service->photos()->max('sort_order') ?? -1) + 1;

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
        $photo->deleteStoredFile();
        $photo->delete();

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
