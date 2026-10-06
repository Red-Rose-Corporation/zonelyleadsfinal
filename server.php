<?php

/**
 * Router for PHP's built-in web server (how the site runs on Railway).
 *
 * Without a router the built-in server answers 404 on its own for any missing URL that ends in a file
 * extension, so /sitemap.xml never reached Laravel. With this router a real file in public/ (css, js,
 * images, robots.txt ...) is still served directly, and every other URL goes to Laravel, exactly like
 * Apache's mod_rewrite or `php artisan serve` do.
 */
$public = __DIR__ . '/public';
$uri    = urldecode(parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/');
$file   = realpath($public . $uri);

if ($uri !== '/' && $file !== false && is_file($file) && str_starts_with($file, realpath($public) . DIRECTORY_SEPARATOR)) {
    return false; // a real static file inside public/: let the server send it
}

require_once $public . '/index.php';
