<?php
declare(strict_types=1);

/**
 * Router — maps request paths to controller@action pairs.
 *
 * Routes are static (no URL parameters yet); the app's URLs are simple
 * section links (/dashboard, /packages, /login, ...). The router looks
 * up the path in a table and dispatches to the controller, passing it
 * the Request. Unknown paths fall back to a 404.
 */
final class Router
{
    /** @var array<string, array{controller: class-string, action: string}> */
    private array $routes = [];

    public function get(string $path, string $controller, string $action): void
    {
        $this->routes[$path] = ['controller' => $controller, 'action' => $action];
    }

    public function dispatch(Request $request): void
    {
        $path = $request->path;

        // Normalize a trailing slash (except root).
        if ($path !== '/' && str_ends_with($path, '/')) {
            $path = rtrim($path, '/');
            if (!isset($this->routes[$path])) {
                $this->notFound();
            }
        }

        if (!isset($this->routes[$path])) {
            $this->notFound();
        }

        $route  = $this->routes[$path];
        $class  = $route['controller'];
        $action = $route['action'];

        if (!class_exists($class) || !method_exists($class, $action)) {
            http_response_code(500);
            exit('المسار غير مهيأ بشكل صحيح.');
        }

        /** @var Controller $controller */
        $controller = new $class();
        $controller->{$action}($request);
    }

    private function notFound(): void
    {
        http_response_code(404);
        exit('الصفحة المطلوبة غير موجوبة.');
    }
}
