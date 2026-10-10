<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class Blog extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'slug', 'image_path', 'short_description', 'description', 'keyword', 'pageview', 'pageview', 'user_id'];

    /** Image types a blog feature image may be stored as on R2; anything else keeps the old behaviour. */
    private const IMAGE_EXTENSIONS = ['jpg', 'jpeg', 'png', 'webp', 'gif', 'avif'];

    /**
     * Store a blog feature image and return the path to save in image_path.
     *
     * The page builds the image URL from the R2 bucket (get_file) whenever R2 is configured, but
     * upload_file() writes to this server's local disk, so an image uploaded here used to 404 on
     * the live site. When R2 is configured the file now goes to R2 instead, under a readable
     * name (my-post-featured.jpg) and never over an object that already exists.
     *
     * Anything unexpected (R2 not configured, an unusual file type, an R2 error) falls back to
     * upload_file(), i.e. exactly the previous behaviour.
     */
    private static function storeImage(UploadedFile $file): string
    {
        if (!config('filesystems.disks.r2.key')) {
            return upload_file($file);
        }

        try {
            $ext = strtolower(preg_replace('/[^a-z0-9]/i', '', (string) $file->getClientOriginalExtension()));
            if (!in_array($ext, self::IMAGE_EXTENSIONS, true)) {
                return upload_file($file);
            }

            $base = trim(Str::limit(Str::slug(pathinfo((string) $file->getClientOriginalName(), PATHINFO_FILENAME)), 80, ''), '-');
            $base = $base !== '' ? $base : 'image';

            $disk = Storage::disk('r2');
            $name = $base . '.' . $ext;
            if ($disk->exists('uploads/' . $name)) {
                $name = $base . '-' . strtolower(Str::random(6)) . '.' . $ext;
            }

            if ($disk->putFileAs('uploads', $file, $name, 'public') !== false) {
                return 'uploads/' . $name;
            }
        } catch (\Throwable $e) {
            report($e);
        }

        return upload_file($file);
    }

    public static function createStore($request): bool
    {
        if ($request->hasFile('image_path')) {
            $image = self::storeImage($request->file('image_path'));
        } else {
            $image = '';
        }
        $slug = $request->slug != null ? make_slug($request->slug) : make_slug($request->name);
        while (self::where('slug', $slug)->exists()) {
            $slug = set_increment_slug(Blog::class, $slug);
        }
        $newEntry = self::create([
            'user_id' => auth()->user()->id,
            'name' => $request->name,
            'image_path' => $image,
            'short_description' => $request->short_description,
            'description' => $request->description,
            'keyword' => $request->keyword,
            'slug' => $slug,
        ]);
        return $newEntry instanceof self;
    }

    public static function updateStore($request, $id): bool
    {
        $blog = Blog::find($id);
        if (!$blog) {
            return false;
        }
        $image = $blog->image_path;
        if ($request->hasFile('image_path') && $request->file('image_path')->isValid()) {
            if ($blog->image_path != null) {
                delete_file($blog->image_path);
            }
            $image = self::storeImage($request->file('image_path'));
        }
        $slug = $request->slug ? make_slug($request->slug) : ($blog->slug ?: make_slug($request->name));
        if ($slug != $blog->slug) {
            while (self::where('slug', $slug)->exists()) {
                $slug = set_increment_slug(Blog::class, $slug);
            }
        }
        $updateEntry = $blog->update([
            'name' => $request->name,
            'image_path' => $image,
            'short_description' => $request->short_description,
            'description' => $request->description,
            'keyword' => $request->keyword,
            'slug' => $slug,
        ]);
        return $updateEntry;
    }

}
