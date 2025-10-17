// Chart Daftar Produk Mingguan
document.addEventListener("DOMContentLoaded", function () {
  const growthEl = document.getElementById("growthChart");
  if (growthEl) {
    const growthCtx = growthEl.getContext("2d");
    new Chart(growthCtx, {
      type: "line",
      data: {
        labels: ["Mon", "Tue", "Wed", "Thu", "Fri", "Sat", "Sun"],
        datasets: [
          {
            label: "Products Sold",
            data: [1, 4, 0, 3, 0, 2, 0], 
            borderColor: "#4a7c59",
            backgroundColor: "rgba(74, 124, 89, 0.1)",
            borderWidth: 3,
            fill: true,
            tension: 0.4,
          },
        ],
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
          legend: { display: true },
        },
        scales: {
          y: {
            beginAtZero: true,
            grid: { color: "rgba(0,0,0,0.05)" },
          },
          x: {
            grid: { display: false },
          },
        },
      },
    });
  }
});


// Weather Chart
const weatherEl = document.getElementById("weatherChart");
if (weatherEl) {
  const weatherCtx = weatherEl.getContext("2d");
  new Chart(weatherCtx, {
    type: "doughnut",
    data: {
      labels: ["Sunny", "Cloudy", "Rainy"],
      datasets: [
        {
          data: [60, 30, 10],
          backgroundColor: ["#d4af37", "#6c757d", "#2196f3"],
          borderWidth: 0,
        },
      ],
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      plugins: {
        legend: {
          position: "bottom",
          labels: { padding: 20, usePointStyle: true },
        },
      },
    },
  });
}

// Sidebar toggle
$(document).ready(function () {
  $("#sidebarToggle").on("click", function () {
    $("#sidebarbtn").toggleClass("show");
  });

  // Klik di luar sidebar buat close
  $(document).on("click", function (e) {
    if (
      $(window).width() < 768 &&
      !$(e.target).closest("#sidebarbtn, #sidebarToggle").length
    ) {
      $("#sidebarbtn").removeClass("show");
    }
  });
});

document
  .getElementById("sidebarToggle")
  ?.addEventListener("click", function (e) {
    const btn = e.currentTarget;
    btn.classList.toggle("active");
    const expanded = btn.getAttribute("aria-expanded") === "true";
    btn.setAttribute("aria-expanded", String(!expanded));
  });

// Total Produk
function updateProductCount() {
  const tbody = document.getElementById("productTableBody");
  const visibleRows = tbody.querySelectorAll(
    "tr:not([style*='display: none'])"
  );
  document.getElementById("totalProducts").textContent = visibleRows.length;
}

document.addEventListener("DOMContentLoaded", updateProductCount);

// Filer dan Search
function filterTable() {
  var searchVal = $("#searchProduct").val().toLowerCase().trim();
  var unitVal = $("#filterUnit").val().toLowerCase().trim();

  $("#productTable tbody tr").each(function () {
    var rowText = $(this).text().toLowerCase();
    var unit = $(this).find("td.unit-col").text().toLowerCase().trim();

    var matchSearch = rowText.indexOf(searchVal) > -1;
    var matchUnit = !unitVal || unit === unitVal;

    if (matchSearch && matchUnit) {
      $(this).show();
    } else {
      $(this).hide();
    }
  });
}

$(document).ready(function () {
  $("#searchProduct").on("keyup", filterTable);
  $("#filterUnit").on("change", filterTable);
});

// Fungsi format ke Rupiah
function formatRupiah(angka, prefix = "Rp ") {
  let number_string = angka.replace(/[^,\d]/g, ""),
    split = number_string.split(","),
    sisa = split[0].length % 3,
    rupiah = split[0].substr(0, sisa),
    ribuan = split[0].substr(sisa).match(/\d{3}/gi);

  if (ribuan) {
    let separator = sisa ? "." : "";
    rupiah += separator + ribuan.join(".");
  }

  rupiah = split[1] !== undefined ? rupiah + "," + split[1] : rupiah;
  return prefix + rupiah;
}

$(document).ready(function () {
  let initialPrice = $("#price").val();
  if (initialPrice) {
    initialPrice = parseFloat(initialPrice).toString();
    $("#price").val(formatRupiah(initialPrice));
  }

  $("#price").on("input", function () {
    let nilai = $(this).val();
    $(this).val(formatRupiah(nilai));
  });

  $("form").on("submit", function () {
    let harga = $("#price").val().replace(/[^0-9]/g, "");
    $("#price").val(harga);
  });
});

// Preview Gambar Upload
document.getElementById("image").addEventListener("change", function () {
  const file = this.files[0];
  if (file) {
    const reader = new FileReader();
    reader.onload = function (e) {
      const preview = document.getElementById("preview");
      preview.src = e.target.result;
      preview.style.display = "block";
      preview.style.maxWidth = "110px";
    };
    reader.readAsDataURL(file);
  }
});

// Icon Siang dan Malam Otomatis
document.addEventListener("DOMContentLoaded", () => {
  const icon = document.getElementById("weather-icon");
  const hour = new Date().getHours();
  if (hour >= 6 && hour < 18) {
    icon.innerHTML = '<i class="fa-solid fa-sun"></i>';
  } else {
    icon.innerHTML = '<i class="fa-solid fa-moon"></i>';
  }
});

updateWeatherIcon();
setInterval(updateWeatherIcon, 60 * 60 * 1000);
