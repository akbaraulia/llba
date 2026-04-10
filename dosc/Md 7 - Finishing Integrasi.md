# Md 7 - Finishing Integrasi Seluruh Modul

Dokumen ini menutup proses pengembangan dengan fokus integrasi antar modul dan pengujian end-to-end.

Urutan internal:

1. Cek layout dan navigasi.
2. Cek akses role admin, petugas, member.
3. Uji alur end-to-end.

## 1) File Acuan

1. Layout utama:
    - `resources/views/layouts/app.blade.php`
2. Navigasi sidebar:
    - `resources/views/layouts/navigation.blade.php`
3. Script interaksi drawer:
    - `resources/js/app.js`
4. Rute aplikasi:
    - `routes/web.php`

## 1.1) Perintah Terminal Finishing Integrasi

Perintah dasar untuk validasi integrasi:

```bash
php artisan optimize:clear
php artisan route:list
php artisan migrate
php artisan db:seed
```

Perintah menjalankan aplikasi saat uji end-to-end:

```bash
npm run dev
php artisan serve
```

Jika ada perubahan pada layout/sidebar/js dan ingin validasi hasil production build:

```bash
npm run build
```

Opsional untuk reset penuh data uji:

```bash
php artisan migrate:fresh --seed
```

## 2) Tahap Per Tahap

### Tahap A - Cek layout utama

Yang dicek:

1. Struktur halaman konsisten di semua modul.
2. Panel header global (judul/subtitle/badge role) memang sudah dihapus dari layout utama.
3. Logic `page_title` dan `page_subtitle` tidak lagi dipakai di view utama.
4. Slot konten tampil langsung tanpa wrapper header lama.
5. Slot konten tidak tertutup sidebar pada desktop (`sm:ml-64`).
6. Tombol logout tersedia di sidebar.
7. Asset frontend dari Vite termuat.

Tujuan:

1. Semua view modul punya tampilan dan perilaku form yang seragam.

### Tahap B - Cek navigasi

Yang dicek:

1. Menu tampil sesuai role.
2. Link aktif menandai halaman yang sedang dibuka.
3. Hover item sidebar bekerja pada background, teks, dan icon.
4. Menu modul admin tidak tampil untuk role yang tidak berhak.
5. Tombol burger tampil di mobile dan menyembunyikan sidebar di desktop.

Validasi khusus drawer mobile:

1. Klik tombol burger membuka sidebar.
2. Klik area luar sidebar menutup sidebar.
3. Tekan `Esc` menutup sidebar.
4. Saat layar `sm` ke atas, sidebar otomatis tampil tetap.

Tujuan:

1. Mencegah user tersesat dan mencegah akses tak sesuai peran.
2. Menjaga UX mobile dan desktop tetap nyaman.

### Tahap C - Cek otorisasi route

Yang dicek:

1. Route publik hanya yang memang publik.
2. Route auth hanya bisa diakses user login.
3. Route admin hanya bisa diakses admin.
4. Route admin/petugas hanya bisa diakses admin dan petugas.

Tujuan:

1. Menjaga keamanan data dan konsistensi alur bisnis.

### Tahap D - Uji alur end-to-end

Skenario minimal:

1. Login sebagai admin.
2. Kelola user.
3. Kelola produk.
4. Ubah system setting poin.
5. Login sebagai petugas.
6. Buat transaksi pembelian.
7. Cek struk PDF.
8. Cek rekap pembelian.
9. Cek dashboard.

## 3) Istilah Penting Saat Pengujian Integrasi

1. End-to-end testing
   Definisi: pengujian dari awal sampai akhir alur pengguna nyata.

2. Regression testing
   Definisi: memastikan fitur lama tidak rusak setelah perubahan baru.

3. Access control
   Definisi: pembatasan akses berdasarkan role dan status login.

4. Integration point
   Definisi: titik pertemuan antar modul, misalnya transaksi yang bergantung ke produk dan setting poin.

## 4) Checklist Akhir Lulus Proyek

1. Semua modul dapat diakses sesuai role.
2. Semua CRUD utama berjalan tanpa error.
3. Kalkulasi poin dan stok konsisten.
4. Struk PDF dan export CSV berjalan.
5. Dashboard menampilkan ringkasan akurat.
6. Sidebar drawer berjalan baik di mobile dan desktop.
7. Hover sidebar sesuai desain tema aktif saat interaksi.
8. Logic panel header lama sudah benar-benar tidak dipakai.
9. Navigasi sesuai role tidak salah arah.
10. Tidak ada error validasi yang tidak tertangani.
11. Tidak ada route sensitif yang terbuka untuk role salah.

## 5) Catatan Presentasi Saat Pengujian

Saat menjelaskan di penguji, gunakan urutan ini:

1. Tunjukkan fondasi dan login.
2. Tunjukkan manajemen user dan role.
3. Tunjukkan manajemen produk.
4. Tunjukkan setting poin.
5. Tunjukkan transaksi lengkap hingga struk.
6. Tunjukkan dashboard dan rekap.
7. Tunjukkan bahwa pembatasan akses bekerja.

## 6) Output Akhir Md 7

Jika Md 7 selesai, maka proyek siap diuji sebagai sistem utuh, bukan lagi sekadar kumpulan modul terpisah.
