<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/**
 * Temporary diagnostic. Does nothing unless the request carries an `X-Timing` header, so visitors,
 * search engines and every normal page are untouched. With the header it adds a `Server-Timing`
 * response header splitting the time into: boot (PHP/framework start), connect (opening the database
 * connection), db (all queries, with the count) and total. It reports only numbers, never SQL.
 */
class ServerTiming
{
    public function handle(Request $request, Closure $next)
    {
        if (!$request->hasHeader('X-Timing')) {
            return $next($request);
        }

        $start = microtime(true);
        $boot  = defined('LARAVEL_START') ? ($start - LARAVEL_START) * 1000 : 0;

        $connect = 0;
        try {
            $t = microtime(true);
            DB::connection()->getPdo();
            $connect = (microtime(true) - $t) * 1000;
        } catch (\Throwable $e) {
            // reported as connect;dur=0 plus the failure on the page itself
        }

        $count = 0;
        $dbMs  = 0.0;
        DB::listen(function ($query) use (&$count, &$dbMs) {
            $count++;
            $dbMs += $query->time;
        });

        $response = $next($request);

        $total = (microtime(true) - $start) * 1000;
        $response->headers->set('Server-Timing', sprintf(
            'boot;dur=%.0f, connect;dur=%.0f, db;dur=%.0f;desc="%d queries", total;dur=%.0f',
            $boot, $connect, $dbMs, $count, $total
        ));

        // Railway sets these inside every container; shows which replica/region answered.
        $replica = trim((getenv('RAILWAY_REPLICA_REGION') ?: '-') . ' ' . substr((string) (getenv('RAILWAY_REPLICA_ID') ?: '-'), 0, 8));
        $response->headers->set('X-Replica', $replica);

        return $response;
    }
}
