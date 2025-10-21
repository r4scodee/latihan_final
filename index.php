<?php
session_start(); // Mulai session, penting untuk manajemen user login, flash message, dll.

// Autoloader untuk class
spl_autoload_register(function ($class) {
    // Daftar folder tempat mencari class yang dipanggil
    $paths = [
        __DIR__ . '/app/library/',    // Library umum
        __DIR__ . '/app/controller/', // Controller MVC
        __DIR__ . '/app/model/',      // Model MVC
    ];
    foreach ($paths as $p) {
        $file = $p . $class . '.php'; // Nama file class sesuai nama class + .php
        if (is_file($file)) {
            require_once $file; // Jika file ditemukan, langsung di-include
            return; // Berhenti setelah ketemu
        }
    }
});

require_once __DIR__ . '/app/config/config.php'; // Load konfigurasi global (misal BASE_URL, DB, dll)

// Routing sederhana berdasarkan URL parameter 'url'
// Jika tidak ada 'url', default ke 'product/index'1
$url = $_GET['url'] ?? 'product/index';

// Pisah URL menjadi segmen, contoh: product/edit/1 jadi ['product','edit','1']
$segments = explode('/', trim($url, '/'));

// Tentukan controller dan method yang dipanggil
$controllerName = ucfirst($segments[0]) . 'Controller'; // Contoh: 'product' -> 'ProductController'
$method = $segments[1] ?? 'index'; // Method default: 'index'
$params = array_slice($segments, 2); // Parameter tambahan (misal id produk)

// Path file controller
$controllerFile = __DIR__ . '/app/controller/' . $controllerName . '.php';

// Cek apakah file controller ada
if (file_exists($controllerFile)) {
    require_once $controllerFile; // Include controller

    // Cek apakah class controller ada
    if (class_exists($controllerName)) {
        $controller = new $controllerName(); // Buat instance controller

        // Cek apakah method ada di controller
        if (method_exists($controller, $method)) {
            // Panggil method controller dengan parameter (jika ada)
            call_user_func_array([$controller, $method], $params);
            exit; // Berhenti setelah method dipanggil
        }
    }
}

http_response_code(404);
echo "<h1>404 - Halaman tidak ditemukan</h1>";
echo "<p>Controller atau method yang kamu akses tidak ada.</p>";
echo "<a href='" . BASE_URL . "'>Kembali ke halaman utama</a>";