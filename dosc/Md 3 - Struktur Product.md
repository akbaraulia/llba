# Md 3 - Struktur Product Sampai Selesai Total

Dokumen ini menyelesaikan satu struktur penuh modul Product sebelum lanjut ke modul lain.

Urutan internal:

1. Migration product.
2. Model product.
3. Controller product.
4. Route product.
5. View product.
6. Testing upload gambar dan stok.

## 1) File Acuan

1. Migration:
    - `database/migrations/2026_04_06_070045_create_products_table.php`
2. Model:
    - `app/Models/Product.php`
3. Controller:
    - `app/Http/Controllers/ProductController.php`
4. Route:
    - `routes/web.php`
5. View:
    - `resources/views/products/index.blade.php`
    - `resources/views/products/create.blade.php`
    - `resources/views/products/edit.blade.php`

## 1.1) Perintah Terminal Tahap Product

Jika membangun ulang modul Product dari awal:

```bash
php artisan make:model Product -m
php artisan make:controller ProductController
php artisan migrate
php artisan storage:link
```

Catatan:

1. `php artisan storage:link` penting agar file gambar dari storage public bisa diakses browser.
2. Jika migration product sudah pernah dijalankan, sesuaikan strategi ulang DB saat pengujian.

## 2) Tahap Per Tahap

### Tahap A - Migration product

Kolom penting:

1. `name`
2. `price` decimal
3. `stock`
4. `image_path`

Tujuan:

1. Menyimpan katalog produk.
2. Menyimpan stok untuk validasi pembelian.

### Tahap B - Model Product

Isi penting model:

1. Fillable product.
2. Cast `price` ke decimal.
3. Relasi ke `PurchaseItem`.

Keterhubungan:

1. Dipakai pada modul Product.
2. Dipakai modul Purchase saat checkout.

### Tahap C - Controller Product

Method dan fungsi:

1. `index`: list + search produk.
2. `create`: form tambah produk.
3. `store`: simpan produk dan upload gambar.
4. `edit`: form edit produk.
5. `update`: update nama, harga, gambar.
6. `updateStock`: update stok via modal.
7. `destroy`: hapus produk + hapus file gambar.

Poin pengujian penting:

1. Harga yang diinput format rupiah harus dinormalisasi sebelum simpan.
2. Gambar lama harus dihapus saat gambar baru diunggah.

### Tahap D - Route Product

Akses route:

1. `products.index` untuk user login.
2. Create, update, stock update, delete hanya admin.

Keterhubungan:

1. Route menuju `ProductController`.
2. Route model binding `{product}`.

### Tahap E - View Product

1. `products.index`:
    - tabel produk
    - tombol edit, update stok, hapus
    - modal update stok
2. `products.create`:
    - form nama, harga, stok, gambar
3. `products.edit`:
    - edit data produk
    - preview gambar

### Tahap F - Integrasi media gambar

Alur:

1. Upload file ke disk `public` folder `products`.
2. Simpan path ke `image_path`.
3. Tampilkan gambar lewat URL publik /storage/... setelah menjalankan php artisan storage:link.

## 3) Istilah Penting Saat Pengujian Modul Product

1. File storage
   Definisi: penyimpanan file upload pada disk Laravel.

2. MIME type
   Definisi: tipe konten file saat dikirim ke browser.

3. Data normalization
   Definisi: pembersihan format input agar konsisten di DB.

4. Validation image
   Definisi: validasi bahwa file benar-benar gambar.

## 4) Skenario Uji Wajib

1. Tambah produk valid berhasil.
2. Tambah produk tanpa gambar gagal validasi.
3. Edit harga dengan format rupiah tetap tersimpan angka.
4. Update stok negatif ditolak.
5. Update stok valid berhasil.
6. Hapus produk menghapus file gambar.
7. Role non-admin tidak bisa akses route admin product.

## 5) Output Akhir Md 3

Struktur Product selesai total dan siap dipakai oleh modul Purchase.
