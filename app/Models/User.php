<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = ['name', 'type', 'email', 'title', 'phone', 'designation', 'whatsapp', 'show_phone', 'bio', 'work_address', 'status', 'password', 'about', 'remark', 'country', 'state', 'city', 'zip_code', 'additional_details', 'tags', 'slug', 'business_name', 'seller_service_type', 'experience', 'category_id', 'profile_photo', 'referred_by', 'schedule', 'twilio_enabled', 'agreed_terms_at', 'points',
        'notify_new_lead', 'notify_payment', 'notify_review', 'notify_booking'];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'agreed_terms_at'  => 'datetime',
        'password' => 'hashed',
        'schedule' => 'array',
    ];

    public function gallery()
    {
        return $this->hasMany(SellerGallery::class)->orderBy('sort_order');
    }

    public function services()
    {
        return $this->hasMany(Service::class);
    }
    public function educations()
    {
        return $this->hasMany(Education::class);
    }
    public function contacts()
    {
        return $this->hasMany(Contact::class);
    }
    public function languages()
    {
        return $this->hasMany(Language::class);
    }
    public function memberships()
    {
        return $this->hasMany(Membership::class);
    }

    public function experiences()
    {
        return $this->hasMany(Experience::class)->orderByDesc('is_current')->orderByDesc('start_date');
    }

    public function certifications()
    {
        return $this->hasMany(Certification::class);
    }

    public function faqs()
    {
        return $this->hasMany(\App\Models\Faq::class)->orderBy('sort_order')->orderBy('id');
    }

    public function leads()
    {
        return $this->hasMany(Lead::class, 'seller_id');
    }

    public function unpaidLeadsCount(): int
    {
        return $this->leads()->whereNull('paid_at')->count();
    }

    public function leadThreshold(): int
    {
        $stateId  = \App\Models\State::where('title', $this->state)->value('id');
        $cityId   = \App\Models\City::where('title', $this->city)->value('id');
        return (int) \App\Models\PlatformCharge::resolve('lead_threshold', $this->category_id, $stateId, $cityId);
    }

    public function isOverdue(): bool
    {
        return $this->unpaidLeadsCount() >= $this->leadThreshold();
    }

    public function reviews()
    {
        return $this->hasMany(Review::class, 'seller_id');
    }

    public function reviewsGiven()
    {
        return $this->hasMany(Review::class, 'reviewer_id');
    }

    public function referredBy()
    {
        return $this->belongsTo(User::class, 'referred_by');
    }

    public function referrals()
    {
        return $this->hasMany(User::class, 'referred_by');
    }

    public function commissionsEarned()
    {
        return $this->hasMany(AffiliateCommission::class, 'referrer_id');
    }

    public function pointsLog()
    {
        return $this->hasMany(\App\Models\UserPointsLog::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function twilioNumber()
    {
        return $this->hasOne(\App\Models\TwilioNumber::class, 'seller_id');
    }

    public function callLogs()
    {
        return $this->hasMany(\App\Models\CallLog::class, 'seller_id');
    }

    public function scopeActiveSellers($query)
    {
        return $query->where('type', 'seller')->where('status', true);
    }

    public function staffProfile()
    {
        return $this->hasOne(StaffProfile::class);
    }

    public function managerProfile()
    {
        return $this->hasOne(\App\Models\ManagerProfile::class);
    }
}
