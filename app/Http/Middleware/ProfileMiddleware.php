<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class ProfileMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::check()) {
            return redirect()->route('user.login');
        }

        $user = Auth::user();

        if ($user->type === 'seller') {
            if (empty($user->phone) || empty($user->work_address)) {
                if (!$request->routeIs('profile.first')) {
                    return redirect()->route('profile.first')->with('warning', 'Please complete your profile before proceeding.');
                }
            }
            return $next($request);
        }
        if ($user->type === 'admin') {
            return redirect()->route('admin.dashboard');
        }
        if ($user->type === 'user') {
            return redirect()->route('dashboard');
        }
        return redirect()->route('user.login');
    }
}
