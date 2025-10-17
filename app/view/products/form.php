<?php
// Safety defaults biar tidak muncul warning
$product = $product ?? [];
$old = $old ?? [];
$errors = $errors ?? [];
$csrf = $csrf ?? '';

// Detect apakah edit atau create
$isEdit = ($action === 'update');
$formAction = $isEdit
  ? BASE_URL . 'product/update/' . ($product['id'] ?? '')
  : BASE_URL . 'product/store';

// Helper untuk ambil value lama
$val = function ($key, $default = '') use ($product, $old, $isEdit) {
  if (!empty($old) && isset($old[$key]))
    return $old[$key];
  if ($isEdit && !empty($product) && isset($product[$key]))
    return $product[$key];
  return $default;
};

$isEdit = ($action === 'update');
$formAction = $isEdit ? BASE_URL . 'product/update/' . ($product['id'] ?? '') : BASE_URL . 'product/store';
$val = function ($key, $default = '') use ($product, $old, $isEdit) {
  if (!empty($old) && isset($old[$key]))
    return $old[$key];
  if ($isEdit && !empty($product) && isset($product[$key]))
    return $product[$key];
  return $default;
};
?>
<main class="container-fluid px-4 py-4">
  <div class="card-header bg-white border-0 pb-3">
    <h4 class="fw-bold text-success d-flex align-items-center mb-0">
      <i class="fas <?= $isEdit ? 'fa-edit' : 'fa-plus-circle' ?> me-2"></i>
      <?= $isEdit ? 'Edit Produk' : 'Tambah Produk' ?>
    </h4>
    <div class="border-bottom mt-4"></div>
  </div>

  <div class="card-body">
    <?php if (!empty($errors)): ?>
      <div class="alert alert-danger rounded-3">
        <ul class="mb-0">
          <?php foreach ($errors as $err): ?>
            <li><?= $this->e($err) ?></li>
          <?php endforeach; ?>
        </ul>
      </div>
    <?php endif; ?>

    <form action="<?= $formAction ?>" method="post" enctype="multipart/form-data" novalidate class="row g-3">
      <input type="hidden" name="_csrf" value="<?= $this->e($csrf) ?>">

      <div class="col-md-6">
        <label class="form-label fw-semibold">Kode Produk <span class="text-danger">*</span></label>
        <input type="text" name="code" class="form-control" required value="<?= $this->e($val('code')) ?>">
        <div class="form-text">Gunakan kode unik, misal <b>PRD-001</b></div>
      </div>

      <div class="col-md-6">
        <label class="form-label fw-semibold">Nama Produk <span class="text-danger">*</span></label>
        <input type="text" name="name" class="form-control" required value="<?= $this->e($val('name')) ?>">
      </div>

      <div class="col-md-6">
        <label class="form-label fw-semibold">Harga</label>
        <input type="text" name="price" id="price" class="form-control" placeholder="0"
          value="<?= $this->e($val('price')) ?>">
      </div>

      <div class="col-md-6">
        <label class="form-label fw-semibold">Satuan</label>
        <select name="unit" class="form-select">
          <option value="pcs" <?= $val('unit') == 'pcs' ? 'selected' : '' ?>>pcs</option>
          <option value="g" <?= $val('unit') == 'g' ? 'selected' : '' ?>>g</option>
          <option value="kg" <?= $val('unit') == 'kg' ? 'selected' : '' ?>>kg</option>
          <option value="ton" <?= $val('unit') == 'ton' ? 'selected' : '' ?>>ton</option>
        </select>
      </div>

      <div class="col-12">
        <label class="form-label fw-semibold">Gambar Produk (jpg/png/webp, max 2MB)</label>
        <input type="file" name="image" id="image" accept="image/*" class="form-control">
        <div class="mt-0">
          <?php $img = $val('image'); ?>
          <img id="preview" src="<?= $img ? UPLOAD_URL . $this->e($img) : '#' ?>" alt="preview"
            class="img-thumbnail shadow-sm" style="<?= $img ? 'max-width:220px;' : 'display:none; max-width:220px;' ?>">
        </div>
      </div>

      <div class="col-12 d-flex gap-2 justify-content-end mt-4">
        <a href="<?= BASE_URL ?>product" class="btn btn-secondary px-4 rounded-5">Batal</a>
        <button class="btn btn-farm px-4 rounded-5"><?= $isEdit ? 'Ubah' : 'Simpan' ?></button>
      </div>
    </form>
  </div>
  </div>
</main>