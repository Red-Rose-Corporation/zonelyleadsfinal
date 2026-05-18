<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class ForceWww
{
    public function handle(Request $request, Closure $next)
    {
        if ($request->getHost() === 'zonelyleads.com') {
            return redirect('https://www.zonelyleads.com' . $request->getRequestUri(), 301);
        }
        return $next($request);
    }
}
