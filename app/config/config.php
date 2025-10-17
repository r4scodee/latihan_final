<?php
// Kondigurasi database
define('DB_HOST', '127.0.0.1');      
define('DB_NAME', 'final_test_db');       
define('DB_USER', 'root');          
define('DB_PASS', '');               

// BASE_URL
$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
define('BASE_URL', $protocol . '://' . $_SERVER['HTTP_HOST'] . rtrim(dirname($_SERVER['SCRIPT_NAME']), '/\\') . '/');

// Uploads untuk gambar
define('UPLOAD_DIR', __DIR__ . '/../../uploads/'); 
define('UPLOAD_URL', BASE_URL . 'uploads/');      
