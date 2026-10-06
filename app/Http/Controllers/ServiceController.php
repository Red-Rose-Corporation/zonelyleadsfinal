<?php

namespace App\Http\Controllers;

use App\Models\Service;
use App\Models\ServicePhoto;
use App\Services\ServicePhotoManager;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class ServiceController extends Controller
{
    public function index()
    {
        $services = Service::where('user_id', Auth::id())->paginate(10);
        $this->loadPhotos($services->getCollection());
        return view('frontend.profile.services.index', compact('services'));
    }

    public function create()
    {
        $user = Auth::user()->load('category');
        return view('frontend.profile.services.create', compact('user'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title'        => 'required|string|max:255',
            'description'  => 'nullable|string|max:2000',
            'price'        => 'nullable|numeric|min:0',
            'pricing_type' => 'nullable|string|max:50',
            'features'     => 'nullable|string',
        ]);
        $validated['is_active']   = $request->boolean('is_active');
        $validated['user_id']     = Auth::id();
        $validated['category_id'] = Auth::user()->category_id;

        Service::create($validated);

        return redirect()->route('user.services.index')->with('success', 'Service added.');
    }

    public function edit(string $id)
    {
        $service = Service::where('user_id', Auth::id())->findOrFail($id);
        $this->loadPhotos(new \Illuminate\Database\Eloquent\Collection([$service]));
        $gallery = Auth::user()->gallery()->orderBy('sort_order')->orderBy('id')->get();
        return view('frontend.profile.services.edit', compact('service', 'gallery'));
    }

    public function update(Request $request, string $id)
    {
        $validated = $request->validate([
            'title'        => 'required|string|max:255',
            'description'  => 'nullable|string|max:2000',
            'price'        => 'nullable|numeric|min:0',
            'pricing_type' => 'nullable|string|max:50',
            'features'     => 'nullable|string',
        ]);
        $validated['is_active'] = $request->boolean('is_active');

        Service::where('user_id', Auth::id())->findOrFail($id)->update($validated);

        return redirect()->route('user.services.index')->with('success', 'Service updated.');
    }

    public function destroy(string $id)
    {
        Service::where('user_id', Auth::id())->findOrFail($id)->delete();
        return redirect()->route('user.services.index')->with('success', 'Service deleted.');
    }

    /* ------------------------------------------------------------ Service photos (optional) */

    /** Photos are optional: if they cannot be loaded the pages simply behave as they did before. */
    private function loadPhotos($services): void
    {
        try {
            $services->load('photos');
        } catch (\Throwable $e) {
            // no photos
        }
    }

    private function ownService($id): Service
    {
        return Service::where('user_id', Auth::id())->findOrFail($id);
    }

    private function photoBack($id)
    {
        return redirect()->to(route('user.services.edit', $id) . '#photos');
    }

    /** JSON for the in-page uploader, a normal redirect for the plain-form fallback. */
    private function photoReply(Request $request, $id, bool $ok, string $message, int $status = 422)
    {
        if ($request->expectsJson()) {
            return response()->json(['ok' => $ok, 'message' => $message], $ok ? 200 : $status);
        }

        return $this->photoBack($id)->with($ok ? 'photo_success' : 'photo_error', $message);
    }

    public function photoStore(Request $request, $id)
    {
        $service = $this->ownService($id);

        $validator = Validator::make(
            $request->all(),
            ['photo' => ServicePhotoManager::fileRule()],
            ServicePhotoManager::fileMessages('photo')
        );
        if ($validator->fails()) {
            return $this->photoReply($request, $id, false, $validator->errors()->first('photo'));
        }

        try {
            app(ServicePhotoManager::class)->add($service, $request->file('photo'));
        } catch (\DomainException $e) {
            return $this->photoReply($request, $id, false, $e->getMessage());
        } catch (\Throwable $e) {
            Log::warning('seller service photo upload failed: ' . $e->getMessage());
            return $this->photoReply($request, $id, false, 'Could not save the photo. Please try a different image.', 500);
        }

        return $this->photoReply($request, $id, true, 'Photo added.');
    }

    public function photoFromGallery(Request $request, $id)
    {
        $service = $this->ownService($id);
        $gallery = Auth::user()->gallery()->findOrFail($request->input('gallery_id'));

        try {
            app(ServicePhotoManager::class)->addFromGallery($service, $gallery);
        } catch (\DomainException $e) {
            return $this->photoReply($request, $id, false, $e->getMessage());
        } catch (\Throwable $e) {
            Log::warning('seller service photo from gallery failed: ' . $e->getMessage());
            return $this->photoReply($request, $id, false, 'Could not use that photo. Please upload it directly instead.', 500);
        }

        return $this->photoReply($request, $id, true, 'Photo added from your gallery.');
    }

    public function photoMain(Request $request, $id, $pid)
    {
        $service = $this->ownService($id);
        $photo   = ServicePhoto::where('service_id', $service->id)->findOrFail($pid);

        app(ServicePhotoManager::class)->makeMain($photo);

        return $this->photoReply($request, $id, true, 'Main photo changed.');
    }

    public function photoDestroy(Request $request, $id, $pid)
    {
        $service = $this->ownService($id);
        $photo   = ServicePhoto::where('service_id', $service->id)->findOrFail($pid);

        app(ServicePhotoManager::class)->remove($photo);

        return $this->photoReply($request, $id, true, 'Photo removed.');
    }
}
