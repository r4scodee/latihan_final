<?php
session_start();

// Autoload sederhana
spl_autoload_register(function ($class) {
    $paths = [
        __DIR__ . '/app/library/',
        __DIR__ . '/app/controller/',
        __DIR__ . '/app/model/',
    ];
    foreach ($paths as $p) {
        $file = $p . $class . '.php';
        if (is_file($file)) {
            require_once $file;
            return;
        }
    }
});

// Load config
require_once __DIR__ . '/app/config/config.php';

// Router sederhana
$url = $_GET['url'] ?? 'product/index'; // Default ke ProductController@index
$segments = explode('/', trim($url, '/'));

// Controller, method, parameter
$controllerName = ucfirst($segments[0]) . 'Controller';
$method = $segments[1] ?? 'index';
$params = array_slice($segments, 2);

// Cek apakah file controller ada
$controllerFile = __DIR__ . '/app/controller/' . $controllerName . '.php';
if (file_exists($controllerFile)) {
    require_once $controllerFile;

    if (class_exists($controllerName)) {
        $controller = new $controllerName();

        if (method_exists($controller, $method)) {
            call_user_func_array([$controller, $method], $params);
            exit;
        }
    }
}

// Kalau gak ada controller/method valid → tampilkan pesan sederhana
http_response_code(404);
echo "<h1>404 - Halaman tidak ditemukan</h1>";
echo "<p>Controller atau method yang kamu akses tidak ada.</p>";
echo "<a href='" . BASE_URL . "'>Kembali ke halaman utama</a>";