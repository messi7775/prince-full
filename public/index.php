<?php
declare(strict_types=1);

/**
 * Front Controller — single entry point for every HTTP request.
 *
 * All traffic is rewritten here by public/.htaccess. It bootstraps the
 * autoloader, starts the session, builds a Request, registers routes,
 * and dispatches.
 */

define('APP_ROOT', dirname(__DIR__));

require APP_ROOT . '/vendor/Core/autoload.php';
require APP_ROOT . '/config/database.php';

Session::start();

// Generate a CSRF token for the session if none exists.
if (!Session::has('_csrf')) {
    Session::set('_csrf', bin2hex(random_bytes(32)));
}

$routes = require APP_ROOT . '/config/routes.php';
$request = new Request();
$router  = new Router();

foreach ($routes as $method => $map) {
    foreach ($map as $path => $target) {
        [$controller, $action] = $target;
        if ($method === 'GET') {
            $router->get($path, $controller, $action);
        }
    }
}

$router->dispatch($request);
