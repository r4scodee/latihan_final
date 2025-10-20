<?php ob_start(); ?>
<!-- Memulai output buffering -->

<style>
  .gradient-bg {
    background: linear-gradient(135deg, #22c55e, #16a34a, #14b8a6, #3b82f6);
    background-size: 400% 400%;
    animation: gradientShift 10s ease infinite;
    color: #fff;
  }

  @keyframes gradientShift {
    0% {
      background-position: 0% 50%;
    }

    50% {
      background-position: 100% 50%;
    }

    100% {
      background-position: 0% 50%;
    }
  }

  .form-control,
  .form-select {
    height: 46px;
    font-size: 0.95rem;
    transition: all 0.25s ease;
  }

  .form-control:focus,
  .form-select:focus {
    box-shadow: 0 0 0 0.25rem rgba(255, 255, 255, 0.4);
    outline: none;
  }

  .btn-light {
    transition: all 0.3s ease;
  }

  .btn-light:hover {
    background-color: #ffffff;
    transform: translateY(-2px);
    box-shadow: 0 4px 10px rgba(255, 255, 255, 0.4);
  }


  @keyframes fadeIn {
    from {
      opacity: 0;
      transform: translateY(10px);
    }

    to {
      opacity: 1;
      transform: translateY(0);
    }
  }

  @media (max-width: 768px) {
    .gradient-bg {
      text-align: center;
      padding: 2rem 1rem;
    }

    .btn-light {
      width: 100%;
      justify-content: center;
    }
  }
</style>
<div class="top-header border-bottom bg-white shadow-sm">
  <div class="container-fluid">
    <div class="d-flex align-items-center justify-content-between py-2 flex-nowrap gap-2">

      <div class="ms-2 order-3 order-md-1 page-title">
        <h3 class="mb-0 fw-bold text-success d-flex align-items-center">
          <i class="fas fa-seedling me-2"></i> Manajemen Produk
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
  <div class="dashboard-header p-4 mb-4 position-relative gradient-bg rounded-4 shadow-sm">
    <div class="row align-items-center position-relative g-3" style="z-index: 1;">
      <div class="row g-3 align-items-end">

        <!-- Search -->
        <div class="col-md-3">
          <div class="position-relative">
            <input type="text" id="searchProduct" class="form-control ps-5 rounded-5 border-0 shadow-sm"
              placeholder="Cari Produk..." />
            <span class="position-absolute top-50 start-0 translate-middle-y ms-3">
              <i class="fas fa-search text-muted"></i>
            </span>
          </div>
        </div>

        <!-- Filter -->
        <div class="col-md-3">
          <div class="position-relative">
            <select id="filterUnit" class="form-select bg-white ps-5 rounded-5 border-0 shadow-sm">
              <option value="">Semua Satuan</option>
              <?php
              // Tambahkan default satuan agar selalu muncul
              $default_units = ['pcs', 'g', 'kg', 'ton']; // Satuan dasar bawaan
              
              // Ambil semua satuan dari produk dan gabungkan dengan default, lalu buang yang duplikat
              $unit = array_unique(array_merge($default_units, array_column($products, 'unit')));

              foreach ($unit as $s):
                $s = strtolower(trim($s)); // Normalisasi satuan: lowercase + trim spasi
                ?>
                <option value="<?= $s ?>">
                  <?= htmlspecialchars($s) ?> <!-- Tampilkan satuan ke dropdown dengan aman -->
                </option>
              <?php endforeach; ?>
            </select>
            <!-- Dropdown filter satuan produk -->

            <span class="position-absolute top-50 start-0 translate-middle-y ms-3">
              <i class="fas fa-balance-scale text-muted"></i>
            </span>
            <!-- Icon timbangan di dalam input filter -->
          </div>
        </div>


        <!-- Info + Button -->
        <div class="d-flex flex-column flex-md-row align-items-center justify-content-between gap-3 mt-3">
          <div class="d-flex flex-wrap gap-4 justify-content-center justify-content-md-start text-white fw-semibold">
            <div class="d-flex align-items-center">
              <i class="fas fa-box me-2 text-light"></i>
              <span>Total Produk: <span class="fw-bold"><?= count($products) ?></span></span>
              <!-- Menampilkan jumlah total produk yang ada di tabel -->
            </div>

            <div class="d-flex align-items-center text-light" id="weatherBox">
              <i class="fas fa-cloud-sun me-2"></i>
              <span id="weatherText">Memuat cuaca...</span>
              <!-- Placeholder teks cuaca yang akan diisi oleh JavaScript -->
            </div>
          </div>

          <div class="text-center text-md-end">
            <a href="<?= BASE_URL ?>product/create"
              class="btn btn-light text-success px-4 py-2 rounded-5 fw-semibold shadow-sm d-inline-flex align-items-center gap-2 border-0">
              <i class="fas fa-plus-circle"></i>
              Tambah Produk
            </a>
          </div>
        </div>


      </div>
    </div>
  </div>


  <div class="card fade-in shadow-sm border-0">
    <div class="card-body pt-0 pb-4">
      <?php if (empty($products)): ?> <!-- Mengecek apakah array $products kosong -->
        <div class="alert alert-info">Belum ada produk.</div> <!-- Jika kosong, tampilkan pesan -->
      <?php else: ?> <!-- Jika ada produk, lanjutkan ke tabel -->
        <div class="table-responsive">
          <table class="table table-hover mb-0 rounded-3 align-middle" id="productTable">
            <thead>
              <tr>
                <th class="px-4 py-3 fs-lg">Aksi</th>
                <th class="px-4 py-3 fs-lg">Gambar</th>
                <th class="px-4 py-3 fs-lg">Kode</th>
                <th class="px-4 py-3 fs-lg">Nama</th>
                <th class="px-4 py-3 fs-lg">Gudang</th>
                <th class="px-4 py-3 fs-lg">Golongan</th>
                <th class="px-4 py-3 fs-lg">Satuan</th>
                <th class="px-4 py-3 fs-lg">Harga</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($products as $p): ?> <!-- Looping semua produk dari array $products -->
                <tr class="align-middle">
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
                      <!-- Tombol hapus dengan modal konfirmasi, menyimpan ID produk ke atribut data-id -->
                    </div>
                  </td>
                  <td class="px-4 py-3 fw-medium">
                    <?php if (!empty($p['image'])): ?> <!-- Jika gambar produk ada -->
                      <img src="<?= UPLOAD_URL . htmlspecialchars($p['image']) ?>" alt="img" class="thumb img-thumbnail"
                        width="60" />
                    <?php else: ?> <!-- Jika tidak ada gambar -->
                      <span class="text-muted">-</span>
                    <?php endif; ?>
                  </td>
                  <td class="px-4 py-3 fw-medium">
                    <span class="badge bg-primary badge-custom">
                      <?= $this->e($p['code']) ?> <!-- Menampilkan kode produk, dengan sanitasi output -->
                    </span>
                  </td>
                  <td class="px-4 py-3 fw-medium"><?= $this->e($p['name']) ?></td> <!-- Menampilkan nama produk -->
                  <td class="px-4 py-3 fw-medium">
                    <?= $p['namagudang'] ? htmlspecialchars($p['namagudang']) : '<span class="text-muted">-</span>' ?>
                    <!-- Jika nama gudang ada, tampilkan; jika tidak, tampilkan strip -->
                  </td>
                  <td class="px-4 py-3 fw-medium">
                    <?= $p['golongan'] ? htmlspecialchars($p['golongan']) : '<span class="text-muted">-</span>' ?>
                    <!-- Jika golongan ada, tampilkan; jika tidak, tampilkan strip -->
                  <td class="px-4 py-3 fw-medium unit-col">
                    <span class="badge bg-success badge-custom">
                      <?= $this->e($p['unit']) ?> <!-- Menampilkan satuan produk -->
                    </span>
                  </td>
                  <td class="px-4 py-3 fw-medium">
                    Rp
                    <?= number_format($p['price'], 0, ',', '.') ?> <!-- Format harga jadi format rupiah -->
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
                  <h5 class="modal-title text-white">Konfirmasi Hapus</h5>
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
                    <!-- Mengisi token CSRF untuk keamanan form -->
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

      <!-- Tampil jika hasil filter/search tidak menemukan produk -->
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

  const latitude = -7.2575;
  const longitude = 112.7521;

  async function getWeather() {
    try {
      const url = `https://api.open-meteo.com/v1/forecast?latitude=${latitude}&longitude=${longitude}&current_weather=true`;
      const res = await fetch(url);
      const data = await res.json();

      if (data && data.current_weather) {
        const kondisiKode = data.current_weather.weathercode;
        const suhu = Math.round(data.current_weather.temperature);

        let kondisi = "Cerahkan langit";
        if (kondisiKode === 0) kondisi = "Cerah";
        else if (kondisiKode === 1) kondisi = "Cerah berawan";
        else if (kondisiKode >= 2 && kondisiKode <= 3) kondisi = "Berawan";
        else if (kondisiKode >= 45 && kondisiKode <= 48) kondisi = "Berkabut";
        else if (kondisiKode >= 51 && kondisiKode <= 57) kondisi = "Hujan ringan";
        else if (kondisiKode >= 61 && kondisiKode <= 69) kondisi = "Hujan";
        else if (kondisiKode >= 80 && kondisiKode <= 82) kondisi = "Hujan lebat";

        document.getElementById("weatherBox").innerHTML = `
          <i class="fas fa-cloud-sun me-2"></i>
          <span>Cuaca hari ini: ${kondisi}, ${suhu}°C</span>
        `;
      } else {
        document.getElementById("weatherText").textContent = "Gagal memuat cuaca 😢";
      }
    } catch (err) {
      document.getElementById("weatherText").textContent = "Error koneksi cuaca ❌";
      console.error("Error cuaca:", err);
    }
  }

  getWeather();
</script>