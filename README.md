# 🌱 TaniDigital

TaniDigital adalah sebuah aplikasi berbasis web yang dibuat dengan **PHP OOP** menggunakan konsep **Model-View-Controller (MVC)** tanpa framework dan murni PHP OOP.  
Project ini dibuat untuk mempermudah manajemen data dalam dunia pertanian secara digital.

👨‍💻 **Dibuat oleh:**

- Irbadh As-iribuny
- Syafiq Bamazruk

---

## 🚀 Fitur Utama

- Struktur project berbasis **PHP OOP** dengan **MVC Pattern** tanpa framework
- CRUD (Create, Read, Update, Delete) data produk pertanian
- Autoloading class & pemisahan logic (Controller, Model, View)
- Export laporan dengan FPDF
- Terhubung dengan database MySQL
- Tampilan responsif menggunakan Bootstrap & CSS custom

---

## 📂 Struktur Project
```
webFitcom2025_SkinfaDev_smkinformatikaalirsyad/
├── app/
│   ├── config/
│   │   └── config.php
│   ├── controller/
│   │   ├── DashboardController.php
│   │   ├── ErrorController.php
│   │   ├── HomeController.php
│   │   ├── LoginController.php
│   │   ├── ProductController.php
│   │   └── ReportController.php
│   ├── library/
│   │   ├── font/
│   │   ├── Controller.php
│   │   ├── Database.php
│   │   └── fpdf.php
│   ├── model/
│   │   └── ProductModel.php
│   └── view/
│       ├── auth/
│       │   └── login.php
│       ├── dashboard/
│       │   └── index.php
│       ├── errors/
│       │   └── 404.php
│       ├── home/
│       │   └── index.php
│       ├── layouts/
│       │   └── layout.php
│       ├── products/
│       │   ├── form.php
│       │   └── index.php
│       └── report/
│           └── index.php
│
├── assets/
│   ├── css/
│   ├── fonts/
│   ├── icons/
│   ├── img/
│   └── js/
│
├── Database/
│   └── db_fitcom.sql
│
├── uploads/
│
├── .htaccess
├── index.php
├── logout.php
└── README.md

```

---

## ⚙️ Instalasi & Menjalankan Project

### 1️⃣ Clone / Extract Project

Jika project dalam bentuk `.zip`, extract ke folder:

- **Laragon:** `D:\laragon\www\webFitcom2025_SkinfaDev_smkinformatikaalirsyad`
- **XAMPP:** `C:\xampp\htdocs\webFitcom2025_SkinfaDev_smkinformatikaalirsyad`

### 2️⃣ Konfigurasi Database

- Buka file: `database/db_fitcom.sql`
- Import file db_fitcom.sql ke dalam phpMyAdmin/HeidiSQL
- Database & tabel akan otomatis terbuat sesuai struktur project

### 3️⃣ Jalankan Project

1. Pastikan Apache & MySQL aktif di Laragon/XAMPP
2. Akses project melalui browser:
`http://localhost/webFitcom2025_SkinfaDev_smkinformatikaalirsyad/`

3. Untuk masuk ke dashboard, gunakan URL:
`http://localhost/webFitcom2025_SkinfaDev_smkinformatikaalirsyad/dashboard`

### 4️⃣ Login Default

- Gunakan akun berikut untuk login ke dashboard:
 - Username: admintanidigital
 - Password: cirebon2025-admin

### Catatan

- Pastikan Apache & MySQL sudah aktif di Laragon/XAMPP
- Jika routing tidak jalan, cek file .htaccess apakah sudah aktif di root
- Semua asset (CSS, JS, fonts, icons, img, DLL) sudah diletakkan di folder assets/