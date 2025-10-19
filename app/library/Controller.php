<?php
class Controller
{
    protected function view($viewPath, $data = [], $useLayout = true)
    {
        extract($data);
        // Mengubah elemen array $data menjadi variabel, misal $data['title'] menjadi $title

        ob_start();
        // Memulai output buffering agar konten view bisa ditangkap dulu
        $viewFile = __DIR__ . '/../view/' . $viewPath . '.php';
        // Menentukan path file view yang akan di-render

        if (is_file($viewFile)) {
            require $viewFile;
            // Memanggil file view jika ada
        } else {
            echo "<div class='alert alert-danger'>View $viewPath tidak ditemukan.</div>";
            // Tampilkan pesan error jika view tidak ada
        }
        $content = ob_get_clean();
        // Ambil semua output buffering dan simpan ke $content

        if ($useLayout) {
            // render dengan layout
            require __DIR__ . '/../view/layouts/layout.php';
            // Memanggil file layout utama, konten view akan dimasukkan ke layout
        } else {
            // render langsung tanpa layout
            echo $content;
            // Tampilkan konten view langsung tanpa layout
        }
    }

    protected function redirect($path)
    {
        $url = BASE_URL . ltrim($path, '/');
        // Buat URL lengkap untuk redirect
        header("Location: $url");
        // Lakukan redirect ke URL
        exit;
        // Hentikan eksekusi script setelah redirect
    }

    public function e($string)
    {
        return htmlspecialchars($string ?? '', ENT_QUOTES, 'UTF-8');
        // Escape string agar aman dari XSS
    }

    // ===== CSRF helpers =====
    protected function generateCSRFToken()
    {
        if (empty($_SESSION['_csrf_token'])) {
            $_SESSION['_csrf_token'] = bin2hex(random_bytes(32));
            // Membuat token CSRF acak jika belum ada
        }
        return $_SESSION['_csrf_token'];
        // Kembalikan token CSRF
    }

    protected function verifyCSRFToken($token)
    {
        if (empty($token) || empty($_SESSION['_csrf_token']))
            return false;
        // Token tidak valid jika kosong
        return hash_equals($_SESSION['_csrf_token'], $token);
        // Bandingkan token yang dikirim dengan token di session secara aman
    }
}
