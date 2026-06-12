<?php

namespace App\Services;

use App\Models\Lead;
use App\Models\User;
use App\Models\Review;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class NotificationService
{
    /**
     * Push a database notification row to a user.
     * Respects the user's notify_* preference columns.
     */
    public static function send(User $user, string $type, string $title, string $message, ?string $url = null): void
    {
        // Check preference column if it exists
        $prefCol = match($type) {
            'lead'    => 'notify_new_lead',
            'booking' => 'notify_booking',
            'payment' => 'notify_payment',
            'review'  => 'notify_review',
            default   => null,
        };

        if ($prefCol && isset($user->$prefCol) && !$user->$prefCol) {
            return;
        }

        DB::table('notifications')->insert([
            'id'              => (string) Str::uuid(),
            'type'            => 'App\\Notifications\\' . ucfirst($type),
            'notifiable_type' => 'App\\Models\\User',
            'notifiable_id'   => $user->id,
            'data'            => json_encode(array_filter([
                'type'    => $type,
                'title'   => $title,
                'message' => $message,
                'url'     => $url,
            ])),
            'read_at'    => null,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    public static function newLead(Lead $lead): void
    {
        $seller = $lead->seller ?? User::find($lead->seller_id);
        if (!$seller) return;

        $source = match($lead->source ?? 'form') {
            'phone'   => 'phone call',
            'whatsapp'=> 'WhatsApp',
            'email'   => 'email',
            'booking' => 'booking request',
            default   => 'contact form',
        };

        self::send(
            $seller,
            $lead->source === 'booking' ? 'booking' : 'lead',
            $lead->source === 'booking' ? 'New booking request' : 'New lead received',
            ($lead->name ? $lead->name : 'Someone') . ' contacted you via ' . $source
                . ($lead->fee ? ' — fee: $' . number_format($lead->fee, 2) : ''),
            '/seller/leads/' . $lead->id,
        );
    }

    public static function paymentReceived(User $seller, float $amount, int $count): void
    {
        self::send(
            $seller,
            'payment',
            'Payment confirmed',
            '$' . number_format($amount, 2) . ' payment for '
                . $count . ' lead' . ($count > 1 ? 's' : '') . ' has been processed.',
            '/seller/billing',
        );
    }

    public static function newReview(User $seller, Review $review): void
    {
        $stars = str_repeat('★', (int) $review->rating) . str_repeat('☆', 5 - (int) $review->rating);
        self::send(
            $seller,
            'review',
            'New review received',
            ($review->reviewer_name ?? 'A client') . ' left you a ' . $review->rating . '-star review ' . $stars,
            '/seller/reviews',
        );
    }
}
