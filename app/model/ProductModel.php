<?php
class ProductModel
{
    private $db;
    // Property untuk menyimpan koneksi database

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
        // Mengambil koneksi PDO dari singleton Database
    }

    public function all()
    {
        $stmt = $this->db->prepare("
        SELECT p.*, w.namagudang, w.golongan
        FROM produk p
        LEFT JOIN gudang w ON p.kodegudang = w.kodegudang
        ORDER BY p.id ASC
        ");
        // Query untuk mengambil semua produk beserta info gudang (LEFT JOIN)
        $stmt->execute();
        // Eksekusi query
        return $stmt->fetchAll();
        // Ambil semua hasil dalam bentuk array asosiatif
    }

    public function find($id)
    {
        $stmt = $this->db->prepare("
        SELECT p.*, w.namagudang, w.golongan
        FROM produk p
        LEFT JOIN gudang w ON p.kodegudang = w.kodegudang
        WHERE p.id = :id
        LIMIT 1
        ");
        // Query untuk mengambil 1 produk berdasarkan id
        $stmt->execute([':id' => $id]);
        // Bind parameter :id
        return $stmt->fetch();
        // Ambil hasil 1 row
    }

    // Masukkan data produk baru (return inserted id)
    public function create($data)
    {
        $sql = "INSERT INTO produk (kode, nama, harga, iamge, satuan, kodegudang) 
            VALUES (:kode, :nama, :harga, :image, :satuan, :kodegudang)";
        // Query insert produk
        $stmt = $this->db->prepare($sql);
        // Prepare query
        $stmt->execute([
            ':kode' => $data['kode'],
            ':nama' => $data['nama'],
            ':harga' => $data['harga'],
            ':image' => $data['image'],
            ':satuan' => $data['satuan'],
            ':kodegudang' => $data['kodegudang'] ?? null,
        ]);
        // Eksekusi query dengan bind parameter

        // tambahin ini biar bisa dipakai kalau perlu
        return $this->db->lastInsertId();
        // Mengembalikan ID produk yang baru dibuat
    }

    // Update produk berdasarkan id
    public function update($id, $data)
    {
        if (!$id || empty($data))
            return false;
        // Jika id atau data kosong, hentikan

        $sql = "UPDATE produk 
            SET kode = :kode, nama = :nama, harga = :harga, image = :image, satuan = :satuan, kodegudang = :kodegudang
            WHERE id = :id";
        // Query update produk
        $stmt = $this->db->prepare($sql);
        // Prepare query
        return $stmt->execute([
            ':kode' => $data['kode'],
            ':nama' => $data['nama'],
            ':harga' => $data['harga'],
            ':image' => $data['image'],
            ':satuan' => $data['satuan'],
            ':kodegudang' => $data['kodegudang'] ?? null,
            ':id' => $id,
        ]);
        // Eksekusi update dengan bind parameter
    }

    // Hapus produk berdasarkan id
    public function delete($id)
    {
        $stmt = $this->db->prepare("DELETE FROM produk WHERE id = :id");
        // Query hapus produk berdasarkan id
        return $stmt->execute([':id' => $id]);
        // Eksekusi query
    }

    // Cek apakah code produk sudah ada (untuk validasi unique)
    public function existsByCode($code, $excludeId = null)
    {
        if ($excludeId) {
            $stmt = $this->db->prepare("SELECT COUNT(*) FROM produk WHERE kode = :kode AND id != :id");
            // Query cek duplikasi code kecuali id tertentu (untuk update)
            $stmt->execute([':kode' => $code, ':id' => $excludeId]);
        } else {
            $stmt = $this->db->prepare("SELECT COUNT(*) FROM produk WHERE kode = :kode");
            // Query cek duplikasi code saat create
            $stmt->execute([':kode' => $code]);
        }
        return $stmt->fetchColumn() > 0;
        // Kembalikan true jika ada data, false jika tidak ada
    }
}
