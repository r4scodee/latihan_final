<?php
class Database
{
    private static $instance = null; 
    // Menyimpan instance tunggal Database (singleton pattern)

    private $pdo; 
    // Property untuk menyimpan object PDO

    // private constructor agar tidak bisa diinstansiasi dari luar
    private function __construct()
    {
        $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4"; 
        // Data Source Name untuk koneksi PDO, termasuk host, database, dan charset

        $options = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION, 
            // Set error mode menjadi exception agar mudah ditangani
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,       
            // Hasil fetch akan berbentuk array asosiatif
            PDO::ATTR_EMULATE_PREPARES   => false,                 
            // Nonaktifkan emulate prepares untuk keamanan SQL injection
        ];

        $this->pdo = new PDO($dsn, DB_USER, DB_PASS, $options); 
        // Membuat koneksi PDO ke database
    }

    public static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = new Database(); 
            // Jika belum ada instance, buat baru (singleton)
        }
        return self::$instance; 
        // Kembalikan instance Database
    }

    public function getConnection()
    {
        return $this->pdo; 
        // Mengembalikan object PDO untuk digunakan query
    }
}
