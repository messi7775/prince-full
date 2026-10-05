<?php
declare(strict_types=1);

/**
 * Controller — base class for all controllers.
 *
 * Handles view rendering, auth gating, and CSRF verification.
 */
abstract class Controller
{
    /** Renders a view file inside the application layout. */
    protected function view(string $view, array $data = [], ?string $layout = 'app'): void
    {
        // Extract data into the view's local scope.
        extract($data, EXTR_SKIP);

        $viewFile = __DIR__ . '/../Views/' . $view . '.php';
        if (!is_file($viewFile)) {
            http_response_code(500);
            exit('العرض المطلوب غير موجود: ' . htmlspecialchars($view));
        }

        // Standalone layout (e.g. login) renders only the view file.
        if ($layout === null) {
            require $viewFile;
            return;
        }

        // Capture the view into $content for the layout.
        ob_start();
        require $viewFile;
        $content = ob_get_clean();

        $layoutFile = __DIR__ . '/../Views/layouts/' . $layout . '.php';
        if (!is_file($layoutFile)) {
            http_response_code(500);
            exit('القالب المطلوب غير موجود: ' . htmlspecialchars($layout));
        }
        require $layoutFile;
    }

    /** Redirect helper. */
    protected function redirect(string $path): void
    {
        Response::redirect($path);
    }

    /** Abort with 404. */
    protected function notFound(): void
    {
        http_response_code(404);
        exit('الصفحة المطلوبة غير موجودة.');
    }

    /** Ensure an admin is logged in; otherwise redirect to login. */
    protected function requireAuth(): void
    {
        if (!Session::isAuthenticated()) {
            $this->redirect('/login');
        }
    }

    /** Verify the CSRF token on POST requests. */
    protected function verifyCsrf(): void
    {
        if (!Session::isAuthenticated()) {
            return;
        }
        $token = $_POST['_csrf'] ?? '';
        if (!is_string($token) || !hash_equals(Session::get('_csrf', ''), $token)) {
            http_response_code(419);
            exit('انتهت صلاحية الجلسة، أعد المحاولة.');
        }
    }
}
