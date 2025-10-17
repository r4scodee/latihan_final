<?php
class Controller
{
    protected function view($viewPath, $data = [], $useLayout = true)
    {
        extract($data);

        ob_start();
        $viewFile = __DIR__ . '/../view/' . $viewPath . '.php';

        if (is_file($viewFile)) {
            require $viewFile;
        } else {
            echo "<div class='alert alert-danger'>View $viewPath tidak ditemukan.</div>";
        }
        $content = ob_get_clean();

        if ($useLayout) {
            // render dengan layout
            require __DIR__ . '/../view/layouts/layout.php';
        } else {
            // render langsung tanpa layout
            echo $content;
        }
    }

    protected function redirect($path)
    {
        $url = BASE_URL . ltrim($path, '/');
        header("Location: $url");
        exit;
    }

    public function e($string)
    {
        return htmlspecialchars($string ?? '', ENT_QUOTES, 'UTF-8');
    }

    // ===== CSRF helpers =====
    protected function generateCSRFToken()
    {
        if (empty($_SESSION['_csrf_token'])) {
            $_SESSION['_csrf_token'] = bin2hex(random_bytes(32));
        }
        return $_SESSION['_csrf_token'];
    }

    protected function verifyCSRFToken($token)
    {
        if (empty($token) || empty($_SESSION['_csrf_token']))
            return false;
        return hash_equals($_SESSION['_csrf_token'], $token);
    }
}
