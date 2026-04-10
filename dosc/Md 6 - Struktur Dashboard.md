# Md 6 - Struktur Dashboard Sampai Selesai Total

Dokumen ini menyelesaikan struktur Dashboard sebagai halaman ringkasan data operasional.

Urutan internal:

1. Controller dashboard.
2. Route dashboard.
3. View dashboard.
4. Testing data ringkasan.

## 1) File Acuan

1. Controller:
    - `app/Http/Controllers/DashboardController.php`
2. Route:
    - `routes/web.php`
3. View:
    - `resources/views/dashboard.blade.php`

## 1.1) Perintah Terminal Tahap Dashboard

Jika membangun ulang modul Dashboard dari awal:

```bash
php artisan make:controller DashboardController
php artisan route:list
```

Untuk menjalankan pengecekan halaman dashboard:

```bash
npm run dev
php artisan serve
```

## 2) Tahap Per Tahap

### Tahap A - Controller Dashboard

`DashboardController@index` menghitung:

1. Total produk.
2. Stok menipis.
3. Total transaksi hari ini.
4. Omzet hari ini.
5. Daftar transaksi terbaru.

Aturan akses data:

1. Jika admin, tampilkan seluruh data.
2. Jika non-admin, tampilkan data transaksi milik user login saja.

### Tahap B - Route Dashboard

Route:

1. `GET /dashboard` bernama `dashboard`.

Middleware:

1. `auth` dan `verified`.

Makna pengujian:

1. User belum login tidak boleh masuk.
2. User login terverifikasi bisa masuk.

### Tahap C - View Dashboard

Komponen tampilan:

1. Kartu statistik.
2. Tabel transaksi terbaru.

Data yang dipakai:

1. `stats` dari controller.
2. `latestPurchases` dari controller.

## 3) Istilah Penting Saat Pengujian Modul Dashboard

1. Aggregation query
   Definisi: query ringkasan seperti count dan sum.

2. Scoped data
   Definisi: data dibatasi berdasarkan role atau user login.

3. Latest data
   Definisi: data diurutkan dari transaksi paling baru.

## 4) Skenario Uji Wajib

1. Dashboard menampilkan statistik tanpa error.
2. Nilai omzet hari ini sesuai transaksi hari ini.
3. User admin melihat data global.
4. User non-admin hanya melihat data miliknya.
5. Daftar transaksi terbaru urut dari data paling baru.

## 5) Output Akhir Md 6

Dashboard selesai total sebagai pusat monitoring cepat untuk pengguna aplikasi.
