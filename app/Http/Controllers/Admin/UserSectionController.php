<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Faq;
use App\Models\Service;
use App\Models\User;
use App\Services\ImageOptimizer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

/**
 * Lets an admin manage a seller's public-profile sections (Services & Pricing,
 * FAQs, profile photo) without logging in as that seller. Reuses the existing
 * models/tables and the same validation rules as the seller's own dashboard.
 * Every write is scoped to the target user's own rows.
 */
class UserSectionController extends Controller
{
    private const PRICING_TYPES = ['starting_at', 'per_month', 'per_hour', 'flat_rate', 'free', 'contact'];

    /** Same access rule as the Edit User page, and only sellers have public sections. */
    private function target($id): User
    {
        $user = User::findOrFail($id);
        $actorType = auth()->user()?->type;
        if (in_array($actorType, ['manager', 'coo']) && in_array($user->type, ['admin', 'coo', 'manager'])) {
            abort(403, 'Access denied.');
        }
        abort_unless($user->type === 'seller', 404, 'Profile sections are only available for seller accounts.');
        return $user;
    }

    private function back(User $user, string $anchor)
    {
        return redirect()->to(route('admin.profiles.sections', $user->id) . '#' . $anchor);
    }

    private function log(string $action, User $user, $recordId = null): void
    {
        Log::info('admin profile section edit', [
            'action'   => $action,
            'admin_id' => auth()->id(),
            'user_id'  => $user->id,
            'record'   => $recordId,
        ]);
    }

    public function edit($id)
    {
        $user     = $this->target($id);
        $services = Service::where('user_id', $user->id)->orderBy('id')->get();
        $faqs     = Faq::where('user_id', $user->id)->orderBy('sort_order')->orderBy('id')->get();

        return view('admin.profiles2.sections', compact('user', 'services', 'faqs'));
    }

    /* ---------------------------------------------------------------- Services */

    private function serviceRules(): array
    {
        return [
            'title'        => 'required|string|max:255',
            'description'  => 'nullable|string|max:2000',
            'price'        => 'nullable|numeric|min:0|max:9999999',
            'pricing_type' => 'required|in:' . implode(',', self::PRICING_TYPES),
            'features'     => 'nullable|string|max:3000',
        ];
    }

    public function storeService(Request $request, $id)
    {
        $user = $this->target($id);

        // services.category_id is NOT NULL in the database, so a category is required first.
        if (!$user->category_id) {
            return $this->back($user, 'services')->withInput()
                ->with('error', 'Set a Category for this seller first (Edit User → Business / Seller Info), then add services.');
        }

        $data = $request->validate($this->serviceRules());
        $data['is_active']   = $request->boolean('is_active');
        $data['user_id']     = $user->id;
        $data['category_id'] = $user->category_id;

        $service = Service::create($data);
        $this->log('service.created', $user, $service->id);

        return $this->back($user, 'services')->with('success', 'Service added.');
    }

    public function updateService(Request $request, $id, $sid)
    {
        $user    = $this->target($id);
        $service = Service::where('user_id', $user->id)->findOrFail($sid);

        $data = $request->validate($this->serviceRules());
        $data['is_active'] = $request->boolean('is_active');

        $service->update($data);
        $this->log('service.updated', $user, $service->id);

        return $this->back($user, 'services')->with('success', 'Service updated.');
    }

    public function toggleService($id, $sid)
    {
        $user    = $this->target($id);
        $service = Service::where('user_id', $user->id)->findOrFail($sid);

        $service->update(['is_active' => !$service->is_active]);
        $this->log('service.toggled', $user, $service->id);

        return $this->back($user, 'services')
            ->with('success', '"' . $service->title . '" is now ' . ($service->is_active ? 'visible' : 'hidden') . ' on the public profile.');
    }

    public function destroyService($id, $sid)
    {
        $user    = $this->target($id);
        $service = Service::where('user_id', $user->id)->findOrFail($sid);

        $title = $service->title;
        $service->delete();
        $this->log('service.deleted', $user, $sid);

        return $this->back($user, 'services')->with('success', 'Service "' . $title . '" deleted.');
    }

    /* -------------------------------------------------------------------- FAQs */

    private function faqRules(): array
    {
        return [
            'question'   => 'required|string|max:500',
            'answer'     => 'required|string|max:2000',
            'sort_order' => 'nullable|integer|min:0|max:100000',
        ];
    }

    public function storeFaq(Request $request, $id)
    {
        $user = $this->target($id);

        $data = $request->validate($this->faqRules());
        $data['sort_order'] = $data['sort_order'] ?? 0;
        $data['user_id']    = $user->id;

        $faq = Faq::create($data);
        $this->log('faq.created', $user, $faq->id);

        return $this->back($user, 'faqs')->with('success', 'Question added.');
    }

    public function updateFaq(Request $request, $id, $fid)
    {
        $user = $this->target($id);
        $faq  = Faq::where('user_id', $user->id)->findOrFail($fid);

        $data = $request->validate($this->faqRules());
        $data['sort_order'] = $data['sort_order'] ?? 0;

        $faq->update($data);
        $this->log('faq.updated', $user, $faq->id);

        return $this->back($user, 'faqs')->with('success', 'Question updated.');
    }

    public function destroyFaq($id, $fid)
    {
        $user = $this->target($id);
        $faq  = Faq::where('user_id', $user->id)->findOrFail($fid);

        $faq->delete();
        $this->log('faq.deleted', $user, $fid);

        return $this->back($user, 'faqs')->with('success', 'Question deleted.');
    }

    /* ------------------------------------------------------------ Profile photo */

    public function updatePhoto(Request $request, $id)
    {
        $user = $this->target($id);

        $request->validate([
            'profile_photo' => 'required|image|mimes:jpg,jpeg,png,webp,gif|max:10240',
        ]);

        try {
            $path = ImageOptimizer::saveProfilePhoto($request->file('profile_photo'));
        } catch (\Throwable $e) {
            Log::warning('admin profile photo upload failed: ' . $e->getMessage());
            return $this->back($user, 'photo')->with('error', 'Could not save the photo. Please try a different image.');
        }

        $user->update(['profile_photo' => $path]);
        $this->log('photo.updated', $user);

        return $this->back($user, 'photo')->with('success', 'Profile photo updated.');
    }

    public function destroyPhoto($id)
    {
        $user = $this->target($id);

        $user->update(['profile_photo' => null]);
        $this->log('photo.removed', $user);

        return $this->back($user, 'photo')->with('success', 'Profile photo removed.');
    }
}
