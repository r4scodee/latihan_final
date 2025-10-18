<?php ob_start(); ?>
<div class="top-header border-bottom bg-white shadow-sm">
  <div class="container-fluid">
    <div class="d-flex align-items-center justify-content-between py-2 flex-nowrap gap-2">

      <div class="ms-2 order-3 order-md-1 page-title">
        <h3 class="mb-0 fw-bold text-success d-flex align-items-center">
          <i class="fas fa-tags me-2"></i> Products Management
        </h3>
      </div>

      <div class="d-flex align-items-center justify-content-end gap-2 flex-shrink-0 order-1 order-md-2">
          <div class="btn-light d-flex align-items-center p-2">
            <img src="<?= BASE_URL ?>assets/img/logo/logo-dashboard-img.png" alt="admin" class="profile-avatar">
            <div class="text-start d-md-block me-2">
              <div class="fw-bold" style="font-size: 16px">TANI DIGITAL</div>
              <small class="text-muted">Super Admin</small>
            </div>
          </div>
      </div>
    </div>
  </div>
</div>

<main class="container-fluid px-4 py-4">
  <div class="dashboard-header p-4 mb-4 position-relative fade-in">
    <div class="row align-items-center position-relative g-3" style="z-index: 1;">
      <div class="row g-3 align-items-end">
        <div class="col-md-3">
          <div class="position-relative">
            <input type="text" id="searchProduct" class="form-control ps-5 rounded-5 border-0"
              placeholder="Cari Produk..." />
            <span class="position-absolute top-50 start-0 translate-middle-y ms-3">
              <i class="fas fa-search text-muted"></i>
            </span>
          </div>
        </div>
        <div class="col-md-3">
          <div class="position-relative">
            <select id="filterUnit" class="form-select bg-white ps-5 rounded-5 border-0">
              <option value="">Semua Satuan</option>
              <?php
              $unit = array_unique(array_column($products, 'unit'));
              foreach ($unit as $s): ?>
                <option value="<?= strtolower($s) ?>">
                  <?= htmlspecialchars($s) ?>
                </option>
              <?php endforeach; ?>
            </select>
            <span class="position-absolute top-50 start-0 translate-middle-y ms-3">
              <i class="fas fa-balance-scale text-muted"></i>
            </span>
          </div>
        </div>


        <div class="d-flex flex-column flex-md-row align-items-center justify-content-between gap-3">
          <div class="d-flex flex-wrap gap-3 justify-content-center justify-content-md-start">
            <div class="d-flex align-items-center">
              <i class="fas fa-box text-info me-2"></i>
              <span>Total Products: <span class="fw-bold"><?= count($products) ?></span></span>
            </div>
            <div class="d-flex align-items-center">
              <i class="fas fa-calendar text-success me-2"></i>
              <span>Last Updated: Today</span>
            </div>
          </div>
          <div class="text-center text-md-end">
            <a href="<?= BASE_URL ?>product/create" class="btn rounded-5 w-100 w-md-auto btn-add-product px-3 py-2">
              <span class="fs-sm text-light">Tambah Produk</span>
            </a>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="card fade-in shadow-sm border-0">
    <div class="card-body pt-0 pb-4">
      <?php if (empty($products)): ?>
        <div class="alert alert-info">Belum ada produk.</div>
      <?php else: ?>
        <div class="table-responsive">
          <table class="table table-hover mb-0 rounded-3 align-middle" id="productTable">
            <thead>
              <tr>
                <th class="px-4 py-3 fs-lg">ID</th>
                <th class="px-4 py-3 fs-lg">Kode</th>
                <th class="px-4 py-3 fs-lg">Nama</th>
                <th class="px-4 py-3 fs-lg">Harga</th>
                <th class="px-4 py-3 fs-lg">Satuan</th>
                <th class="px-4 py-3 fs-lg">Gambar</th>
                <th class="px-4 py-3 fs-lg">Aksi</th>
              </tr>
            </thead>
            <tbody>
              <?php $no = 1; foreach ($products as $p): ?>
                <tr class="align-middle">
                  <td class="px-4 py-3 fw-medium"><?= $no++ ?></td>
                  <td class="px-4 py-3 fw-medium">
                    <span class="badge bg-primary badge-custom">
                      <?= $this->e($p['code']) ?>
                    </span>
                  </td>
                  <td class="px-4 py-3 fw-medium"><?= $this->e($p['name']) ?></td>
                  <td class="px-4 py-3 fw-medium">
                    Rp
                    <?= number_format($p['price'], 0, ',', '.') ?>
                  </td>
                  <td class="px-4 py-3 fw-medium unit-col">
                    <span class="badge bg-success badge-custom">
                      <?= $this->e($p['unit']) ?>
                    </span>
                  </td>
                  <td class="px-4 py-3 fw-medium">
                    <?php if (!empty($p['image'])): ?>
                      <img src="<?= UPLOAD_URL . htmlspecialchars($p['image']) ?>" alt="img" class="thumb img-thumbnail"
                        width="60" />
                    <?php else: ?>
                      <span class="text-muted">-</span>
                    <?php endif; ?>
                  </td>
                  <td>
                    <div class="d-flex h-100 justify-content-center align-items-center gap-2">
                      <a href="<?= BASE_URL ?>product/edit/<?= htmlspecialchars($p['id']) ?>"
                        class="btn btn-sm btn-outline-success">
                        <i class="fi fi-tr-pen-field"></i>
                      </a>
                      <button type="button" class="btn btn-sm btn-outline-danger btn-delete" data-bs-toggle="modal"
                        data-bs-target="#deleteModal" data-id="<?= htmlspecialchars($p['id']) ?>">
                        <i class="fi fi-tr-trash-xmark"></i>
                      </button>
                    </div>
                  </td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>

          <!-- Modal Konfirmasi Hapus -->
          <div class="modal fade" id="deleteModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
              <div class="modal-content border-0 shadow">
                <div class="modal-header bg-danger text-white">
                  <h5 class="modal-title text-white">
                    Konfirmasi Hapus
                  </h5>
                  <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body text-center">
                  <div class="delete-animation mb-3">
                    <i class="fi fi-tr-trash-xmark text-danger fs-1"></i>
                  </div>
                  <p class="mb-0">Apakah Anda yakin ingin menghapus produk ini?</p>
                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                  <form id="deleteForm" method="post" action="">
                    <input type="hidden" name="_csrf" value="<?= $this->generateCSRFToken() ?>" />
                    <button type="submit" class="btn btn-danger">Ya, Hapus</button>
                  </form>
                </div>
              </div>
            </div>
          </div>

          <!-- Overlay Animasi Checklist -->
          <div id="successOverlay" class="success-overlay d-none">
            <div class="checkmark-container">
              <svg class="checkmark" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 52 52">
                <circle class="checkmark-circle" cx="26" cy="26" r="25" fill="none" />
                <path class="checkmark-check" fill="none" d="M14 27l7 7 16-16" />
              </svg>
              <p class="mt-3 text-white fw-bold">Data berhasil dihapus</p>
            </div>
          </div>

        </div>
      <?php endif; ?>

      <div id="noResults" class="text-center py-5 d-none">
        <div class="display-1 text-muted mb-3">🔍</div>
        <h5 class="text-muted mb-2">No products found</h5>
        <p class="text-muted">Try adjusting your search or filter criteria</p>
      </div>
    </div>
  </div>
</main>

<footer class="bg-light text-dark py-4 mt-5 footer-products">
  <div class="container">
    <p class="mb-1">
      &copy; <?= date('Y') ?> Tani Digital. Semua Hak Dilindungi.

    </p>
  </div>
</footer>

<script>
  document.addEventListener("DOMContentLoaded", function () {
    const deleteButtons = document.querySelectorAll(".btn-delete");
    const deleteForm = document.getElementById("deleteForm");
    const successOverlay = document.getElementById("successOverlay");

    deleteButtons.forEach(btn => {
      btn.addEventListener("click", function () {
        const productId = this.getAttribute("data-id");
        deleteForm.action = `<?= BASE_URL ?>product/delete/` + productId;
      });
    });

    deleteForm.addEventListener("submit", function (e) {
      e.preventDefault();
      const form = this;

      const modalEl = document.getElementById("deleteModal");
      const modal = bootstrap.Modal.getInstance(modalEl);
      modal.hide();

      successOverlay.classList.remove("d-none");

      setTimeout(() => {
        form.submit();
      }, 1500);
    });
  });
</script>