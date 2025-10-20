<?php
require_once __DIR__ . '/../library/Controller.php'; 
// Memanggil file Controller utama agar bisa menggunakan class Controller

class ProductController extends Controller
{
    private $model; 
    // Property untuk menyimpan instance model Product

    public function __construct()
    {
        $this->model = new ProductModel(); 
        // Membuat instance ProductModel saat controller dibuat
    }
    // GET /products (list semua produk)
    public function index()
    {
        $products = $this->model->all(); 
        // Mengambil semua data produk dari database melalui model
        $this->view('products/index', [
            'title' => 'Table Management Products - Tani Digital', 
            // Judul halaman
            'active' => 'products', 
            // Menandai menu aktif
            'products' => $products 
            // Data produk dikirim ke view
        ]);
    }

    // GET /products/create (tampilkan form create)
    public function create()
    {
        $csrf = $this->generateCSRFToken(); 
        // Membuat token CSRF untuk keamanan form
        $this->view('products/form', ['action' => 'store', 'csrf' => $csrf]); 
        // Menampilkan form tambah produk
    }

    // POST /products/store (proses simpan)
    public function store()
    {
        if (!$this->verifyCSRFToken($_POST['_csrf'] ?? '')) {
            die('CSRF token tidak valid.');
        }
        // Memeriksa token CSRF untuk mencegah serangan

        $code = trim($_POST['kode'] ?? ''); 
        $name = trim($_POST['nama'] ?? ''); 
        $price = trim($_POST['harga'] ?? '0'); 
        $unit = trim($_POST['satuan'] ?? ''); 
        $kodegudang = trim($_POST['kodegudang'] ?? null); 
        // Mengambil data dari form input dan membersihkan spasi

        $errors = []; 
        // Array untuk menyimpan error validasi

        if ($code === '')
            $errors[] = "Kode produk wajib diisi."; 
            // Validasi kode produk
        if ($name === '')
            $errors[] = "Nama produk wajib diisi."; 
            // Validasi nama produk
        if (!is_numeric($price) || $price < 0)
            $errors[] = "Harga harus angka >= 0."; 
            // Validasi harga produk

        if ($this->model->existsByCode($code))
            $errors[] = "Kode produk sudah digunakan."; 
            // Mengecek apakah kode produk sudah ada di database

        $uploadedFilename = null; 
        // Variabel untuk menyimpan nama file yang diupload
        if (!empty($_FILES['image']) && $_FILES['image']['error'] !== UPLOAD_ERR_NO_FILE) {
            $uploadResult = $this->handleUpload($_FILES['image']); 
            // Memproses upload gambar jika ada
            if ($uploadResult['success']) {
                $uploadedFilename = $uploadResult['filename']; 
                // Menyimpan nama file yang berhasil diupload
            } else {
                $errors[] = $uploadResult['error']; 
                // Menyimpan error upload jika gagal
            }
        }

        if (!empty($errors)) {
            $csrf = $this->generateCSRFToken(); 
            // Buat token CSRF baru
            $this->view('products/form', [
                'action' => 'store', 
                'errors' => $errors, 
                'old' => ['kode' => $code, 'nama' => $name, 'harga' => $price, 'satuan' => $unit], 
                // Mengirim data lama agar form tidak kosong
                'csrf' => $csrf
            ]);
            return; 
            // Jika ada error, kembalikan ke form
        }

        $data = [
            'kode' => $code,
            'nama' => $name,
            'harga' => $price,
            'image' => $uploadedFilename,
            'satuan' => $unit,
            'kodegudang' => $kodegudang
        ];
        // Menyiapkan data untuk disimpan ke database

        $id = $this->model->create($data); 
        // Memanggil method create pada model untuk menyimpan data

        $this->redirect('/'); 
        // Setelah berhasil, redirect ke halaman utama
    }

    // GET /products/edit/{id}
    public function edit($id)
    {
        $product = $this->model->find($id); 
        // Mengambil data produk berdasarkan id
        if (!$product) {
            echo "Produk tidak ditemukan."; 
            return; 
            // Jika produk tidak ada, tampilkan pesan
        }
        $csrf = $this->generateCSRFToken(); 
        // Buat token CSRF
        $this->view('products/form', ['action' => 'update', 'product' => $product, 'csrf' => $csrf]); 
        // Tampilkan form edit dengan data produk
    }

    // POST /products/update/{id}
    public function update($id)
    {
        if (!$this->verifyCSRFToken($_POST['_csrf'] ?? '')) {
            die('CSRF token tidak valid.');
        }
        // Validasi CSRF token

        $product = $this->model->find($id); 
        // Ambil data produk yang akan diupdate
        if (!$product) {
            echo "Produk tidak ditemukan.";
            return;
        }

        $code = trim($_POST['kode'] ?? '');
        $name = trim($_POST['nama'] ?? '');
        $price = trim($_POST['harga'] ?? '0');
        $unit = trim($_POST['satuan'] ?? '');
        $kodegudang = trim($_POST['kodegudang'] ?? null); 
        // Ambil data input dari form

        $errors = [];
        if ($code === '')
            $errors[] = "Kode produk wajib diisi.";
        if ($name === '')
            $errors[] = "Nama produk wajib diisi.";
        if (!is_numeric($price) || $price < 0)
            $errors[] = "Harga harus angka >= 0."; 
        // Validasi input form

        if ($this->model->existsByCode($code, $id))
            $errors[] = "Kode produk sudah digunakan oleh produk lain."; 
        // Cek duplikasi kode produk selain dirinya sendiri

        $uploadedFilename = $product['image']; 
        // Default file image tetap sama
        if (!empty($_FILES['image']) && $_FILES['image']['error'] !== UPLOAD_ERR_NO_FILE) {
            $uploadResult = $this->handleUpload($_FILES['image']); 
            // Proses upload image baru
            if ($uploadResult['success']) {
                $uploadedFilename = $uploadResult['filename']; 
                // Ganti nama file baru
                if (!empty($product['image']) && is_file(UPLOAD_DIR . $product['image'])) {
                    @unlink(UPLOAD_DIR . $product['image']); 
                    // Hapus file lama jika ada
                }
            } else {
                $errors[] = $uploadResult['error']; 
                // Simpan error jika upload gagal
            }
        }

        if (!empty($errors)) {
            $csrf = $this->generateCSRFToken();
            $this->view('products/form', [
                'action' => 'update',
                'errors' => $errors,
                'product' => ['id' => $id, 'kode' => $code, 'nama' => $name, 'harga' => $price, 'satuan' => $unit, 'image' => $uploadedFilename],
                'csrf' => $csrf
            ]);
            return; 
            // Kembalikan ke form jika ada error
        }

        $data = [
            'kode' => $code,
            'nama' => $name,
            'harga' => $price,
            'image' => $uploadedFilename,
            'satuan' => $unit,
            'kodegudang' => $kodegudang
        ];
        // Siapkan data untuk update

        $this->model->update($id, $data); 
        // Panggil method update di model

        $this->redirect('/'); 
        // Redirect ke halaman utama
    }

    // GET or POST /products/delete/{id}
    public function delete($id)
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            die('Invalid request method.');
        }
        // Hanya menerima request POST untuk keamanan
        if (!$this->verifyCSRFToken($_POST['_csrf'] ?? '')) {
            die('CSRF token tidak valid.');
        }
        // Validasi CSRF token

        $product = $this->model->find($id); 
        if (!$product) {
            echo "Produk tidak ditemukan.";
            return;
        }
        // Pastikan produk ada sebelum dihapus

        $this->model->delete($id); 
        // Hapus data produk dari database

        if (!empty($product['image']) && is_file(UPLOAD_DIR . $product['image'])) {
            @unlink(UPLOAD_DIR . $product['image']); 
            // Hapus file gambar dari server jika ada
        }

        $this->redirect('/'); 
        // Redirect ke halaman utama
    }

    // ===== helper untuk upload file image aman =====
    private function handleUpload($file)
    {
        $maxSize = 2 * 1024 * 1024; 
        // Maksimal ukuran file 2MB

        $allowedExt = ['jpg', 'jpeg', 'png', 'gif', 'webp']; 
        // Ekstensi file yang diperbolehkan

        if ($file['error'] !== UPLOAD_ERR_OK) {
            return ['success' => false, 'error' => 'Upload error kode: ' . $file['error']];
        }
        // Cek error upload

        if ($file['size'] > $maxSize) {
            return ['success' => false, 'error' => 'Ukuran file terlalu besar (max 2MB).'];
        }
        // Validasi ukuran file

        $finfo = new finfo(FILEINFO_MIME_TYPE); 
        $mime = $finfo->file($file['tmp_name']); 
        // Mengecek MIME type file
        $validMimes = ['image/jpeg' => 'jpg', 'image/png' => 'png', 'image/gif' => 'gif', 'image/webp' => 'webp'];

        if (!isset($validMimes[$mime])) {
            return ['success' => false, 'error' => 'Tipe file tidak diizinkan.'];
        }
        // Validasi tipe file

        $ext = $validMimes[$mime]; 
        // Ambil ekstensi yang sesuai MIME type

        $newName = bin2hex(random_bytes(8)) . '_' . time() . '.' . $ext; 
        // Membuat nama file unik

        if (!is_dir(UPLOAD_DIR)) {
            if (!mkdir(UPLOAD_DIR, 0755, true)) {
                return ['success' => false, 'error' => 'Gagal membuat folder upload.'];
            }
        }
        // Pastikan folder upload ada, buat jika belum

        $target = UPLOAD_DIR . $newName; 
        if (!move_uploaded_file($file['tmp_name'], $target)) {
            return ['success' => false, 'error' => 'Gagal menyimpan file.'];
        }
        // Pindahkan file ke folder upload

        @chmod($target, 0644); 
        // Set permission file agar bisa dibaca

        return ['success' => true, 'filename' => $newName]; 
        // Kembalikan status sukses dan nama file
    }
}
