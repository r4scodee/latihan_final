<?php
// Konfigurasi database
define('DB_HOST', '103.163.138.166');      // Host database, di sini menggunakan localhost
define('DB_NAME', 'smkinfor_lombafitcom2025');  // Nama database yang akan dipakai
define('DB_USER', 'smkinfor_root_lombafitcom2025');           // Username untuk akses database
define('DB_PASS', 'RDzQdsPF9NwV9wd');               // Password untuk akses database, kosong berarti tanpa password

// BASE_URL
$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
// Mengecek apakah website menggunakan HTTPS atau HTTP
define('BASE_URL', $protocol . '://' . $_SERVER['HTTP_HOST'] . rtrim(dirname($_SERVER['SCRIPT_NAME']), '/\\') . '/');
// Membuat BASE_URL otomatis sesuai lokasi file agar lebih fleksibel, digunakan untuk link, redirect, dll

// Uploads untuk gambar
define('UPLOAD_DIR', __DIR__ . '/../../uploads/');
// Path di server untuk menyimpan file/gambar yang diupload
define('UPLOAD_URL', BASE_URL . 'uploads/');
// URL untuk mengakses file/gambar dari browser
