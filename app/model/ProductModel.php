<?php
class ProductModel
{
    private $db; 

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }

    public function all()
    {
        $stmt = $this->db->prepare("SELECT * FROM products ORDER BY id ASC");
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function find($id)
    {
        $stmt = $this->db->prepare("SELECT * FROM products WHERE id = :id LIMIT 1");
        $stmt->execute([':id' => $id]);
        return $stmt->fetch();
    }

    // Masukkan data produk baru (return inserted id)
    public function create($data)
    {
        $sql = "INSERT INTO products (code, name, price, image, unit) 
                VALUES (:code, :name, :price, :image, :unit)";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            ':code' => $data['code'],
            ':name' => $data['name'],
            ':price' => $data['price'],
            ':image' => $data['image'],
            ':unit' => $data['unit'],
        ]);
        return $this->db->lastInsertId();
    }

    // Update produk berdasarkan id
    public function update($id, $data)
    {
        $sql = "UPDATE products SET code = :code, name = :name, price = :price, image = :image, unit = :unit WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':code' => $data['code'],
            ':name' => $data['name'],
            ':price' => $data['price'],
            ':image' => $data['image'],
            ':unit' => $data['unit'],
            ':id' => $id,
        ]);
    }

    // Hapus produk berdasarkan id
    public function delete($id)
    {
        $stmt = $this->db->prepare("DELETE FROM products WHERE id = :id");
        return $stmt->execute([':id' => $id]);
    }

    // Cek apakah code produk sudah ada (untuk validasi unique)
    public function existsByCode($code, $excludeId = null)
    {
        if ($excludeId) {
            $stmt = $this->db->prepare("SELECT COUNT(*) FROM products WHERE code = :code AND id != :id");
            $stmt->execute([':code' => $code, ':id' => $excludeId]);
        } else {
            $stmt = $this->db->prepare("SELECT COUNT(*) FROM products WHERE code = :code");
            $stmt->execute([':code' => $code]);
        }
        return $stmt->fetchColumn() > 0;
    }
}
