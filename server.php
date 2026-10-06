<?php

/**
 * Router for PHP's built-in web server (how the site runs on Railway).
 *
 * Without a router the built-in server answers 404 on its own for any missing URL whose last part has a
 * dot, so /sitemap.xml never reached Laravel (/sitemap did). This router changes exactly that and nothing
 * else about how URLs are answered:
 *   - a real file in public/ (css, js, images, robots.txt ...) is served directly, as before;
 *   - a missing URL ending in .xml is handed to Laravel (the sitemap);
 *   - a missing URL with any other extension (.php, .zip, .env, .png ... the usual scanner probes) still
 *     gets the server's own instant 404, so such probes never cost a Laravel boot;
 *   - every URL without an extension goes to Laravel, as before.
 */
$public = __DIR__ . '/public';
$uri    = urldecode(parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/');

if ($uri !== '/') {
    // A NUL byte can never be a real path (and makes realpath() throw on PHP 8.1+): the server's own 404.
    if (str_contains($uri, "\x00")) {
        return false;
    }

    $file = realpath($public . $uri);
    $ext  = strtolower(pathinfo($uri, PATHINFO_EXTENSION));

    $isPublicFile = $file !== false && is_file($file) && str_starts_with($file, realpath($public) . DIRECTORY_SEPARATOR);

    if ($isPublicFile || ($ext !== '' && $ext !== 'xml')) {
        return false; // the server's normal handling (serves the file, or its own 404)
    }
}

require_once $public . '/index.php';
