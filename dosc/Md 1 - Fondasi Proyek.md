# Md 1 - Fondasi Proyek (Sekali di Awal)

Dokumen ini adalah tahap nol sebelum membangun modul fitur. Tujuannya memastikan proyek Laravel bisa berjalan stabil, sehingga modul pertama dapat langsung dikembangkan tanpa error lingkungan.

## 1) Tujuan Tahap Fondasi

1. Menyiapkan dependency backend dan frontend.
2. Menyiapkan konfigurasi environment.
3. Menyiapkan koneksi database.
4. Menyiapkan perintah standar untuk menjalankan aplikasi.

Jika tahap ini belum beres, tahap modul User dan modul lain akan sering gagal di bagian yang seharusnya bukan error logika.

## 2) File Acuan Utama

1. `composer.json`
2. `package.json`
3. `.env.example`
4. `config/database.php`

## 3) Urutan Pengerjaan Fondasi

### Langkah 1 - Install dependency backend (PHP)

1. Jalankan `composer install`.
2. Pastikan folder `vendor` terisi.

Perintah:

```bash
composer install
```

Penjelasan:

1. File `composer.json` berisi daftar package PHP yang dipakai aplikasi.
2. Laravel inti (`laravel/framework`) dan package PDF (`barryvdh/laravel-dompdf`) harus terpasang dari awal karena dipakai modul pembelian.

### Langkah 2 - Install dependency frontend (JS/CSS)

1. Jalankan `npm install`.
2. Pastikan folder `node_modules` terisi.

Perintah:

```bash
npm install
```

Penjelasan:

1. File `package.json` berisi dependency frontend (Vite, Tailwind, dan lain-lain).
2. Halaman Blade menggunakan asset dari Vite, jadi tahap ini tidak boleh dilewati.

### Langkah 3 - Siapkan file environment

1. Salin `.env.example` menjadi `.env`.
2. Isi variabel dasar aplikasi.

Perintah:

```bash
copy .env.example .env
```

Jika memakai PowerShell:

```powershell
Copy-Item .env.example .env
```

Variabel awal yang wajib dicek:

1. `APP_NAME`
2. `APP_ENV`
3. `APP_DEBUG`
4. `APP_URL`
5. `DB_CONNECTION`
6. `DB_HOST`, `DB_PORT`, `DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD` (jika memakai MySQL)

### Langkah 4 - Generate app key

1. Jalankan `php artisan key:generate`.

Perintah:

```bash
php artisan key:generate
```

Penjelasan:

1. Key ini dipakai Laravel untuk enkripsi data session dan keamanan aplikasi.
2. Jika belum ada key, biasanya akan muncul error saat aplikasi dijalankan.

### Langkah 5 - Atur koneksi database

1. Pastikan nilai database di `.env` sesuai server lokal Anda.
2. Pastikan konfigurasi default di `config/database.php` terbaca dari `.env`.

Contoh alur:

1. `.env` memilih `DB_CONNECTION=mysql`.
2. Laravel mengambil setting koneksi `mysql` di `config/database.php`.
3. Semua migration dan query Eloquent akan memakai koneksi itu.

### Langkah 6 - Jalankan migration dasar

1. Jalankan `php artisan migrate`.
2. Pastikan tabel dasar berhasil dibuat.

Perintah:

```bash
php artisan migrate
```

### Langkah 7 - Jalankan aplikasi dan frontend

1. Jalankan `npm run dev`.
2. Jalankan `php artisan serve`.
3. Buka URL aplikasi dan cek halaman berhasil tampil.

Perintah:

```bash
npm run dev
php artisan serve
```

### Langkah Tambahan - Install Laravel Breeze (Jika Proyek Masih Polos)

Jika Anda membuat ulang dari Laravel kosong dan fitur login belum ada, jalankan install Breeze:

Perintah:

```bash
composer require laravel/breeze --dev
php artisan breeze:install
npm install
npm run build
php artisan migrate
```

Catatan:

1. Jika ingin stack blade secara eksplisit, gunakan `php artisan breeze:install blade`.
2. Untuk pengembangan harian, `npm run build` bisa diganti `npm run dev`.

## 4) Istilah Penting yang Harus Dipahami Saat Pengujian

1. Dependency
   Definisi: pustaka tambahan yang dipakai aplikasi.

2. Environment variable
   Definisi: variabel konfigurasi yang disimpan di `.env`.

3. Migration
   Definisi: skrip versi struktur database.

4. Artisan
   Definisi: CLI resmi Laravel untuk menjalankan perintah proyek.

5. Vite
   Definisi: tool build asset frontend yang dipakai Laravel modern.

6. Session driver
   Definisi: tempat penyimpanan data session login user.

## 5) Checklist Lulus Tahap Fondasi

1. `composer install` sukses.
2. `npm install` sukses.
3. File `.env` sudah ada dan terisi.
4. `php artisan key:generate` sukses.
5. `php artisan migrate` sukses.
6. `php artisan serve` dan `npm run dev` berjalan.
7. Halaman utama aplikasi bisa dibuka tanpa error.

## 6) Error Umum dan Penyebab

1. Error database connection failed.
   Penyebab: isi `DB_*` di `.env` salah atau DB server belum aktif.

2. Error no application encryption key.
   Penyebab: belum menjalankan `php artisan key:generate`.

3. Asset CSS/JS tidak muncul.
   Penyebab: `npm install` atau `npm run dev` belum dijalankan.

4. Class atau package tidak ditemukan.
   Penyebab: dependency composer belum ter-install.

## 7) Output Akhir Md 1

Jika Md 1 selesai, proyek sudah siap untuk masuk ke Md 2 (struktur User lengkap sampai testing).
