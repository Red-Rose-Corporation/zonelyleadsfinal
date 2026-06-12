<?php

namespace App\Http\Controllers;

use App\Models\Review;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    /** Public review page — no login required */
    public function show(string $token)
    {
        $review = Review::with('seller')
            ->where('review_token', $token)
            ->whereNull('token_used_at')
            ->firstOrFail();

        return view('frontend.review.show', compact('review'));
    }

    /** Submit the review — guest or logged-in buyer */
    public function store(Request $request, string $token)
    {
        $review = Review::where('review_token', $token)
            ->whereNull('token_used_at')
            ->firstOrFail();

        $data = $request->validate([
            'reviewer_name' => 'required|string|max:100',
            'reviewer_email'=> 'nullable|email|max:150',
            'rating'        => 'required|integer|min:1|max:5',
            'review'        => 'required|string|min:5|max:1000',
            'tags'          => 'nullable|string|max:255',
        ]);

        $user = Auth::user();

        $review->update([
            'reviewer_id'    => $user?->id,
            'reviewer_name'  => $data['reviewer_name'],
            'reviewer_email' => $data['reviewer_email'] ?? $review->reviewer_email,
            'rating'         => $data['rating'],
            'review'         => $data['review'],
            'tags'           => $data['tags'] ?? null,
            'token_used_at'  => now(),
            'review_token'   => null, // invalidate token after use
        ]);

        NotificationService::newReview($review->seller, $review);

        return redirect()->route('frontend.service.show', $review->seller->slug ?? $review->seller_id)
            ->with('success', 'Thank you! Your review has been submitted.');
    }
}
