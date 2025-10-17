<?php
require_once __DIR__ . '/../library/Controller.php';

class ProductController extends Controller
{
    private $model;

    public function __construct()
    {
        $this->model = new ProductModel();
    }
    // GET /products (list semua produk)
    public function index()
    {
        $products = $this->model->all();
        $this->view('products/index', [
            'title' => 'Table Management Products - Tani Digital',
            'active' => 'products',
            'products' => $products
        ]);
    }

    // GET /products/create (tampilkan form create)
    public function create()
    {
        $csrf = $this->generateCSRFToken();
        $this->view('products/form', ['action' => 'store', 'csrf' => $csrf]);
    }

    // POST /products/store (proses simpan)
    public function store()
    {
        if (!$this->verifyCSRFToken($_POST['_csrf'] ?? '')) {
            die('CSRF token tidak valid.');
        }

        $code = trim($_POST['code'] ?? '');
        $name = trim($_POST['name'] ?? '');
        $price = trim($_POST['price'] ?? '0');
        $unit = trim($_POST['unit'] ?? '');

        $errors = [];

        if ($code === '')
            $errors[] = "Kode produk wajib diisi.";
        if ($name === '')
            $errors[] = "Nama produk wajib diisi.";
        if (!is_numeric($price) || $price < 0)
            $errors[] = "Harga harus angka >= 0.";

        if ($this->model->existsByCode($code))
            $errors[] = "Kode produk sudah digunakan.";

        $uploadedFilename = null;
        if (!empty($_FILES['image']) && $_FILES['image']['error'] !== UPLOAD_ERR_NO_FILE) {
            $uploadResult = $this->handleUpload($_FILES['image']);
            if ($uploadResult['success']) {
                $uploadedFilename = $uploadResult['filename'];
            } else {
                $errors[] = $uploadResult['error'];
            }
        }

        if (!empty($errors)) {
            $csrf = $this->generateCSRFToken();
            $this->view('products/form', [
                'action' => 'store',
                'errors' => $errors,
                'old' => ['code' => $code, 'name' => $name, 'price' => $price, 'unit' => $unit],
                'csrf' => $csrf
            ]);
            return;
        }

        $data = [
            'code' => $code,
            'name' => $name,
            'price' => $price,
            'image' => $uploadedFilename,
            'unit' => $unit,
        ];

        $id = $this->model->create($data);

        $this->redirect('/');
    }

    // GET /products/edit/{id}
    public function edit($id)
    {
        $product = $this->model->find($id);
        if (!$product) {
            echo "Produk tidak ditemukan.";
            return;
        }
        $csrf = $this->generateCSRFToken();
        $this->view('products/form', ['action' => 'update', 'product' => $product, 'csrf' => $csrf]);
    }

    // POST /products/update/{id}
    public function update($id)
    {
        if (!$this->verifyCSRFToken($_POST['_csrf'] ?? '')) {
            die('CSRF token tidak valid.');
        }

        $product = $this->model->find($id);
        if (!$product) {
            echo "Produk tidak ditemukan.";
            return;
        }

        $code = trim($_POST['code'] ?? '');
        $name = trim($_POST['name'] ?? '');
        $price = trim($_POST['price'] ?? '0');
        $unit = trim($_POST['unit'] ?? '');

        $errors = [];
        if ($code === '')
            $errors[] = "Kode produk wajib diisi.";
        if ($name === '')
            $errors[] = "Nama produk wajib diisi.";
        if (!is_numeric($price) || $price < 0)
            $errors[] = "Harga harus angka >= 0.";

        if ($this->model->existsByCode($code, $id))
            $errors[] = "Kode produk sudah digunakan oleh produk lain.";

        $uploadedFilename = $product['image'];
        if (!empty($_FILES['image']) && $_FILES['image']['error'] !== UPLOAD_ERR_NO_FILE) {
            $uploadResult = $this->handleUpload($_FILES['image']);
            if ($uploadResult['success']) {
                $uploadedFilename = $uploadResult['filename'];
                if (!empty($product['image']) && is_file(UPLOAD_DIR . $product['image'])) {
                    @unlink(UPLOAD_DIR . $product['image']);
                }
            } else {
                $errors[] = $uploadResult['error'];
            }
        }

        if (!empty($errors)) {
            $csrf = $this->generateCSRFToken();
            $this->view('products/form', [
                'action' => 'update',
                'errors' => $errors,
                'product' => ['id' => $id, 'code' => $code, 'name' => $name, 'price' => $price, 'unit' => $unit, 'image' => $uploadedFilename],
                'csrf' => $csrf
            ]);
            return;
        }

        $data = [
            'code' => $code,
            'name' => $name,
            'price' => $price,
            'image' => $uploadedFilename,
            'unit' => $unit
        ];

        $this->model->update($id, $data);

        $this->redirect('/');
    }

    // GET or POST /products/delete/{id}
    public function delete($id)
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            die('Invalid request method.');
        }
        if (!$this->verifyCSRFToken($_POST['_csrf'] ?? '')) {
            die('CSRF token tidak valid.');
        }

        $product = $this->model->find($id);
        if (!$product) {
            echo "Produk tidak ditemukan.";
            return;
        }

        $this->model->delete($id);

        if (!empty($product['image']) && is_file(UPLOAD_DIR . $product['image'])) {
            @unlink(UPLOAD_DIR . $product['image']);
        }

        $this->redirect('/');
    }

    // ===== helper untuk upload file image aman =====
    private function handleUpload($file)
    {
        $maxSize = 2 * 1024 * 1024;

        $allowedExt = ['jpg', 'jpeg', 'png', 'gif', 'webp'];

        if ($file['error'] !== UPLOAD_ERR_OK) {
            return ['success' => false, 'error' => 'Upload error kode: ' . $file['error']];
        }

        if ($file['size'] > $maxSize) {
            return ['success' => false, 'error' => 'Ukuran file terlalu besar (max 2MB).'];
        }

        $finfo = new finfo(FILEINFO_MIME_TYPE);
        $mime = $finfo->file($file['tmp_name']);
        $validMimes = ['image/jpeg' => 'jpg', 'image/png' => 'png', 'image/gif' => 'gif', 'image/webp' => 'webp'];

        if (!isset($validMimes[$mime])) {
            return ['success' => false, 'error' => 'Tipe file tidak diizinkan.'];
        }

        $ext = $validMimes[$mime];

        $newName = bin2hex(random_bytes(8)) . '_' . time() . '.' . $ext;

        if (!is_dir(UPLOAD_DIR)) {
            if (!mkdir(UPLOAD_DIR, 0755, true)) {
                return ['success' => false, 'error' => 'Gagal membuat folder upload.'];
            }
        }

        $target = UPLOAD_DIR . $newName;
        if (!move_uploaded_file($file['tmp_name'], $target)) {
            return ['success' => false, 'error' => 'Gagal menyimpan file.'];
        }

        @chmod($target, 0644);

        return ['success' => true, 'filename' => $newName];
    }
}
