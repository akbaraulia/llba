# Project Handover Documentation

## 1. Tujuan Dokumen
Dokumen ini disusun untuk kebutuhan handover proyek dari satu perusahaan ke perusahaan lain, dengan tujuan:
- Menjelaskan struktur sistem secara menyeluruh.
- Menjelaskan kenapa implementasi dibuat seperti sekarang.
- Menjelaskan sumber data, alur data, dan alur bisnis.
- Menjadi manual book operasional per role.
- Menjadi panduan teknis untuk maintenance, debugging, dan pengembangan lanjutan.

Dokumen ini disusun berdasarkan pembacaan source code aktif pada branch `main` repository saat ini.

---

## 2. Ringkasan Sistem
Aplikasi ini adalah sistem Point of Sale (POS) berbasis Laravel dengan fitur utama:
- Manajemen produk.
- Manajemen pengguna dengan role (admin, petugas, member).
- Transaksi pembelian.
- Sistem poin member (earn/redeem).
- Konfigurasi global aturan poin.
- Export rekap transaksi CSV.
- Cetak/download struk PDF.

Teknologi utama:
- Backend: Laravel 12, PHP ^8.2.
- Frontend: Blade, Bootstrap 5, TailwindCSS, AlpineJS (dependency tersedia).
- Database: Relational DB via Eloquent Migration.
- PDF: barryvdh/laravel-dompdf.
- Auth: Laravel Breeze-style auth controllers.

Kenapa arsitektur ini dipakai:
- Laravel mempercepat CRUD + auth + migration + validation.
- Blade cukup untuk dashboard internal/backoffice.
- Sistem poin diposisikan sebagai business rule transaksional dan ditangani di server-side transaction untuk menjaga konsistensi data.

---

## 3. Struktur Folder dan Tanggung Jawab

### 3.1 Core Backend
- `app/Http/Controllers`: logic endpoint web.
- `app/Models`: representasi entity database + relasi.
- `app/Http/Middleware`: middleware otorisasi role.
- `app/Http/Requests`: validasi request terstruktur (saat ini terbatas untuk login/profile).

### 3.2 Routing
- `routes/web.php`: seluruh route aplikasi internal.
- `routes/auth.php`: route autentikasi (login/register/reset/password/verify email).

### 3.3 Database
- `database/migrations`: definisi skema tabel.
- `database/seeders/DatabaseSeeder.php`: data user awal (admin + petugas demo).

### 3.4 UI
- `resources/views`: blade pages per modul.
- `resources/views/layouts`: layout utama + sidebar/nav.
- `resources/css/app.css`: gaya global panel/theme.

---

## 4. Modul dan Penjelasan Menyeluruh

## 4.1 Modul Authentication & Profile

### Komponen
- Auth controllers standar Laravel (`app/Http/Controllers/Auth/*`).
- Request validator login (`app/Http/Requests/Auth/LoginRequest.php`).
- Profile update (`app/Http/Controllers/ProfileController.php`, `app/Http/Requests/ProfileUpdateRequest.php`).

### Alur
1. User login via email + password.
2. Sistem menerapkan rate limit login (5 percobaan per throttle key).
3. Setelah login, user diarahkan ke dashboard.
4. User dapat edit profil (nama/email) dan ubah password.

### Kenapa logic begini
- Rate limiter pada login untuk mitigasi brute-force.
- Email verification route tersedia karena bawaan Breeze, namun akses dashboard diproteksi `verified`, sehingga alur aktivasi email secara desain tetap dipertahankan.

### Catatan implementasi
- Register publik memungkinkan pembuatan user baru.
- Pada registrasi publik, role menggunakan default dari migration (`petugas`) karena controller register tidak mengisi kolom `role`.

Implikasi bisnis:
- Jika register publik dibiarkan aktif di production, user baru otomatis berstatus petugas.
- Ini harus disepakati apakah memang diinginkan atau perlu ditutup.

---

## 4.2 Modul Authorization (Role Middleware)

### Komponen
- `app/Http/Middleware/RoleMiddleware.php`
- Alias middleware didaftarkan di `bootstrap/app.php` sebagai `role`.

### Role aktif
- `admin`
- `petugas`
- `member`

### Cara kerja
Middleware memeriksa:
1. User login atau belum.
2. Role user ada dalam daftar role yang diperbolehkan di route.

Jika gagal: HTTP 403.

### Kenapa dibuat sebagai middleware custom
- Supaya aturan role per route simpel: `role:admin` atau `role:admin,petugas`.
- Memisahkan concern otorisasi dari controller agar controller lebih fokus pada business logic.

---

## 4.3 Modul Dashboard

### Komponen
- Controller: `DashboardController@index`
- View: `resources/views/dashboard.blade.php`

### Data yang ditampilkan
- Total produk.
- Jumlah stok menipis (`stock < 5`).
- Jumlah transaksi hari ini.
- Omzet hari ini (`sum total_after_discount`).
- Daftar 8 transaksi terbaru.

### Sumber data
- `products` table.
- `members` table.
- `purchases` table.

### Kenapa query dibedakan admin vs non-admin
- Jika bukan admin, query pembelian difilter `user_id = user login`.
- Tujuannya membatasi visibilitas data kasir per akun.

---

## 4.4 Modul Produk

### Komponen
- Controller: `ProductController`
- Model: `Product`
- View: `products/index/create/edit`
- Table: `products`

### Fungsi utama
- List + search produk.
- Tambah produk.
- Edit nama/harga/gambar.
- Update stok (modal terpisah).
- Hapus produk.

### Struktur data produk
- `name`
- `price` decimal(15,2)
- `stock` unsigned int
- `image_path` nullable

### Alur harga dan normalisasi nominal
- Di form, harga diformat Rupiah di frontend JS.
- Di backend, `moneyToNumber()` menghapus karakter non-digit agar tersimpan numerik.

Kenapa begini:
- UX user kasir lebih mudah dengan format Rupiah.
- DB tetap menyimpan nilai angka bersih untuk kalkulasi.

### Alur gambar
- Saat create: gambar wajib.
- Saat update: gambar opsional.
- Jika update gambar baru: file lama dihapus dari storage disk `public`.
- Akses gambar menggunakan route `/media/{path}` yang membaca file dari storage public.

Kenapa pakai route media custom:
- Kontrol MIME type dan cache header manual.
- Tidak bergantung langsung pada URL filesystem mentah.

---

## 4.5 Modul Pengguna (User Management)

### Komponen
- Controller: `UserController`
- Model: `User`
- Model pendukung: `Member`
- Views: `users/index/create/edit`
- Table: `users`, `members`

### Fungsi utama
- List dan search pengguna.
- Tambah pengguna baru.
- Edit pengguna (admin only untuk edit/hapus).
- Hapus pengguna (kecuali akun sendiri).

### Rule role saat create
- Jika actor admin: boleh membuat admin/petugas/member.
- Jika actor non-admin (petugas): hanya boleh membuat member.

Kenapa begini:
- Mencegah privilege escalation dari petugas.
- Delegasi operasional pendaftaran member ke petugas.

### Integrasi user-member
Saat user role menjadi `member`, sistem otomatis membuat `members` profile (firstOrCreate) dengan poin awal 0.

Kenapa profile member dipisah tabel:
- Data user (auth identity) terpisah dari data loyalty (points, max redeem).
- Memudahkan perluasan loyalty tanpa mengotori tabel users.

### Catatan penting
- Tabel members masih punya kolom `name` dan `phone`, namun flow aktif memakai relasi `member->user` untuk data nama/telepon.
- Artinya kolom `members.name/phone` bersifat legacy/opsional dan bukan sumber utama.

---

## 4.6 Modul System Settings (Aturan Poin Global)

### Komponen
- Controller: `SystemSettingController`
- Model: `SystemSetting`
- View: `system-settings/edit`
- Table: `system_settings` (singleton pattern)

### Parameter
1. `point_redeem_value`
   - 1 poin setara berapa Rupiah.
2. `point_earn_spend`
   - Untuk mendapat 1 poin, harus belanja berapa Rupiah.
3. `default_max_redeem_percentage`
   - Maksimum persen total transaksi yang boleh ditutup poin.

### Kenapa singleton
- Aturan ini global untuk seluruh sistem.
- Model `SystemSetting::current()` otomatis membuat record default jika belum ada.

---

## 4.7 Modul Pembelian (Core Transaksi)

Ini modul paling kritikal karena menyentuh stok, uang, dan poin member.

### Komponen
- Controller: `PurchaseController`
- Model: `Purchase`, `PurchaseItem`
- Views: `purchases/create/member/index/receipt/receipt-struk-pdf`
- Tables: `purchases`, `purchase_items`, plus `products`, `members`, `users`.

### Tujuan desain
- Proses checkout aman terhadap race condition.
- Historis transaksi immutable walau data master berubah.
- Perhitungan diskon poin konsisten dan dapat diaudit.

### Alur proses end-to-end

#### Step A. Pilih produk
- Endpoint: `GET /purchases/create`.
- User pilih qty per produk (plus/minus/input).
- Frontend hitung subtotal dan total sementara.

#### Step B. Validasi dan simpan ke session
- Endpoint: `POST /purchases/prepare`.
- Backend validasi qty array.
- Validasi stok cukup.
- Bentuk snapshot item (`product_id`, `product_name`, `price`, `qty`, `subtotal`).
- Simpan ke `session('checkout')`.

Kenapa disimpan di session:
- Flow checkout multi-step tanpa membuat draft order di DB.
- Lebih ringan untuk use case kasir internal.

#### Step C. Input status member, pembayaran, poin
- Endpoint: `GET /purchases/member`.
- Menampilkan ringkasan belanja dari session.
- Menyediakan member directory (phone, name, points) ke frontend JS untuk lookup cepat.
- User pilih non-member/member dan nominal bayar.

#### Step D. Process transaksi final
- Endpoint: `POST /purchases/process`.

Validasi inti:
- `member_status` wajib.
- `cash_paid` wajib dan numerik.
- Jika member: phone wajib + `use_points` wajib.

Perhitungan inti:
1. `totalBeforeDiscount` dari session.
2. Cari aturan poin global dari system settings.
3. Jika member:
   - Cari user role member berdasarkan phone.
   - Ambil `memberProfile`.
   - Tentukan `maxRedeemPercentage` (custom member jika ada, fallback global).
   - Hitung batas diskon poin berdasar persen.
   - Hitung batas poin yang bisa dipakai.
   - `pointsUsed` dikunci ke batas aman.
4. `totalAfterDiscount = totalBeforeDiscount - pointsDiscountAmount`.
5. Validasi `cash_paid >= totalAfterDiscount`.
6. Hitung `pointsEarned = floor(totalAfterDiscount / point_earn_spend)`.
7. Hitung kembalian.

### Kenapa pakai DB transaction + lockForUpdate
Pada blok `DB::transaction()`:
- Member di-lock (`lockForUpdate`) sebelum potong/tambah poin.
- Produk di-lock (`lockForUpdate`) sebelum pengurangan stok.

Tujuannya:
- Mencegah stok minus karena transaksi paralel.
- Mencegah poin member dipakai dobel di transaksi simultan.
- Menjamin atomicity: semua sukses atau rollback semua.

### Snapshot historis pada purchase
Saat create `purchases` disimpan juga:
- `point_redeem_value`
- `point_earn_spend`
- `max_redeem_percentage`
- `points_discount_amount`

Kenapa:
- Supaya histori tetap valid walau system settings berubah di masa depan.
- Audit transaksi lama tetap merefleksikan rule saat transaksi terjadi.

### Snapshot historis pada purchase_items
Disimpan `product_name` dan `price` saat itu.

Kenapa:
- Jika nama/harga produk master berubah, nota lama tidak ikut berubah.

### Output transaksi
- Halaman struk.
- Preview PDF via iframe.
- Download/print PDF.

### Export
- Endpoint `GET /purchases/export` menghasilkan CSV rekap transaksi.
- Untuk non-admin, data diexport hanya milik user sendiri.

---

## 4.8 Modul Receipt / PDF

### Komponen
- View HTML PDF: `purchases/receipt-struk-pdf.blade.php`
- PDF generator: DomPDF facade di `PurchaseController@receiptPdf`

### Karakteristik
- Paper custom size untuk format struk thermal.
- Menampilkan item, total, poin, pembayaran, kembalian.

Kenapa PDF terpisah dari halaman biasa:
- Format cetak butuh HTML + CSS khusus yang konsisten lintas browser.
- DomPDF memudahkan output stream/download.

---

## 5. Data Model dan Sumber Data

## 5.1 users
Sumber utama identitas login.
- Primary source untuk nama/email/phone/role.
- Relasi ke pembelian sebagai kasir.

## 5.2 members
Sumber utama data loyalty.
- Menyimpan poin dan max redeem khusus member.
- Relasi 1-1 ke users.

## 5.3 products
Master katalog barang.
- Dipakai saat checkout.
- Stok berkurang saat transaksi sukses.

## 5.4 purchases
Header transaksi.
- Menyimpan identitas transaksi, pembayaran, snapshot rule poin.

## 5.5 purchase_items
Detail item transaksi.
- Menyimpan snapshot nama/harga/qty/subtotal per item.

## 5.6 system_settings
Konfigurasi global aturan poin.
- Dibaca setiap transaksi untuk perhitungan default.

---

## 6. Matriks Hak Akses (Actual Behavior)

| Fitur | Admin | Petugas | Member |
|---|---|---|---|
| Lihat dashboard | Ya | Ya | Ya |
| Lihat produk | Ya | Ya | Ya |
| Tambah/Edit/Hapus produk | Ya | Tidak | Tidak |
| Update stok produk | Ya | Tidak | Tidak |
| Lihat pembelian | Ya (semua) | Ya (milik sendiri) | Ya (milik sendiri) |
| Export pembelian | Ya (semua) | Ya (milik sendiri) | Ya (milik sendiri) |
| Buat pembelian | Ya | Ya | Ya |
| Lihat/cetak struk | Ya (sesuai akses data) | Ya (milik sendiri) | Ya (milik sendiri) |
| Lihat daftar user | Ya | Ya | Tidak |
| Tambah user | Ya | Ya (member only) | Tidak |
| Edit/Hapus user | Ya | Tidak | Tidak |
| Ubah system settings | Ya | Tidak | Tidak |
| Edit profil sendiri | Ya | Ya | Ya |

Catatan penting:
- Route pembelian create/process saat ini berada pada middleware `auth` (semua user login), sehingga member juga bisa membuat transaksi.
- Di UI sidebar, tombol “Tambah Pembelian” hanya muncul untuk petugas, tapi akses route tetap terbuka jika member tahu URL.

---

## 7. Manual Book Operasional per Role

## 7.1 Manual Admin

### Aktivitas harian
1. Login.
2. Cek dashboard (stok menipis, omzet harian).
3. Cek data pembelian (audit transaksi).
4. Export rekap jika dibutuhkan.
5. Kelola produk (CRUD + update stok).
6. Kelola user (buat/edit/hapus).
7. Atur parameter poin di System Settings.

### SOP perubahan aturan poin
1. Buka System Settings.
2. Ubah nilai poin redemption / earning / max redeem.
3. Simpan.
4. Informasikan ke tim kasir karena transaksi berikutnya langsung menggunakan rule baru.

## 7.2 Manual Petugas

### Aktivitas kasir
1. Login.
2. Buka “Tambah Pembelian”.
3. Pilih produk + qty.
4. Lanjut ke data pembeli.
5. Jika member: input telepon member, pilih pakai poin atau tidak.
6. Input uang dibayar.
7. Submit transaksi.
8. Tampilkan struk, print/download jika diminta pelanggan.

### Aktivitas admin support terbatas
- Buat akun member baru dari menu Data Pengguna.
- Tidak bisa edit/hapus user existing.

## 7.3 Manual Member (berdasarkan implementasi sekarang)

### Aktivitas yang tersedia
1. Login.
2. Lihat dashboard personal (berbasis transaksi milik akun sendiri).
3. Lihat daftar pembelian milik sendiri.
4. Export data pembelian milik sendiri.
5. Edit profil.

### Catatan
- Secara route, member juga punya akses ke flow create pembelian, namun ini tergantung policy bisnis apakah memang diizinkan.

---

## 8. Penjelasan Business Rules Utama

## 8.1 Rule poin digunakan
- Maksimal redeem dipengaruhi dua hal:
  - Saldo poin member.
  - Batas persen dari total transaksi.
- Poin yang benar-benar dipakai = minimum dari input user dan batas aman.

Rumus:
- `maxDiscountAmount = totalBeforeDiscount * (maxRedeemPercentage / 100)`
- `maxPointsByPercent = floor(maxDiscountAmount / pointRedeemValue)`
- `maxPointsUsable = min(memberPoints, maxPointsByPercent)`
- `pointsUsed = min(requestedPoints, maxPointsUsable)`
- `pointsDiscountAmount = pointsUsed * pointRedeemValue`

## 8.2 Rule poin didapat
- Hanya member yang mendapat poin.
- `pointsEarned = floor(totalAfterDiscount / pointEarnSpend)`

Kenapa pakai floor:
- Menghindari pemberian poin pecahan.
- Menjaga konsistensi pembulatan ke bawah untuk fairness konservatif.

## 8.3 Rule stok
- Qty tidak boleh melebihi stok.
- Validasi dilakukan 2x:
  - Saat prepare (sebelum masuk member form).
  - Saat process final di dalam transaction + lock.

Kenapa validasi ganda:
- Menghadapi kondisi stok berubah di antara dua step checkout.

---

## 9. API/Route Map Singkat

### Public
- `GET /` welcome page.
- `GET /media/{path}` akses file gambar produk.

### Authenticated
- Profile edit/update/delete.
- Produk index.
- Pembelian index/export/receipt/pdf.
- Pembelian create/prepare/member/process.

### Role admin only
- Produk create/store/edit/update/stock/delete.
- System settings edit/update.
- User edit/update/delete.

### Role admin,petugas
- User index/create/store.

---

## 10. Operational Runbook (Teknis)

## 10.1 Setup awal environment
1. Install dependency PHP via composer.
2. Copy `.env` dari `.env.example`.
3. Generate app key.
4. Setup DB connection.
5. Jalankan migration.
6. Jalankan seeder demo user.
7. Install node modules.
8. Jalankan Vite build/dev.

Referensi script tersedia di `composer.json`:
- `composer run setup`
- `composer run dev`
- `composer run test`

## 10.2 Account seed default
Seeder membuat:
- admin: `admin.test@gmail.com` / `password`
- petugas: `petugas.test@gmail.com` / `password`

Gunakan hanya untuk environment dev/UAT.

## 10.3 Backup yang wajib sebelum handover production
- Dump database penuh.
- Backup folder storage (khususnya image produk).
- Backup file `.env` (rahasia tetap di secure vault).
- Dokumentasi versi commit yang dihandover.

---

## 11. Testing Checklist UAT

## 11.1 Auth
- Login berhasil dengan akun valid.
- Login gagal dengan password salah.
- Rate limit login bekerja setelah percobaan berulang.

## 11.2 Produk
- Tambah produk dengan gambar berhasil.
- Edit produk dengan/ tanpa ganti gambar berhasil.
- Update stok via modal berhasil.
- Hapus produk menghapus file gambar terkait.

## 11.3 User
- Admin bisa create role apa pun.
- Petugas hanya bisa create member.
- User member auto-terbentuk member profile.
- Self-delete user ditolak.

## 11.4 Pembelian non-member
- Pilih produk, bayar cukup, transaksi sukses.
- Stok terpotong benar.
- CSV dan struk sesuai.

## 11.5 Pembelian member + poin
- Phone member valid dikenali.
- Batas poin berdasarkan persen dan saldo bekerja.
- Potong poin + tambah poin berhasil.
- Kembalian benar.

## 11.6 Hak akses
- Admin bisa lihat semua transaksi.
- Non-admin hanya transaksi milik sendiri.
- Endpoint admin ditolak untuk role non-admin.

---

## 12. Known Gaps / Risiko yang Perlu Diketahui Saat Handover

1. Register publik masih aktif.
- Dampak: akun baru default role `petugas` (dari default migration users.role).
- Risiko: privilege tidak sesuai kebijakan jika tidak dikunci.

2. Akses create purchase untuk semua user login.
- Secara route, member juga bisa akses flow pembelian.
- Jika policy mengharuskan hanya petugas/admin, route perlu dibatasi role.

3. Search query users memakai kombinasi where + orWhere dalam satu closure.
- Perlu perhatian jika nanti ditambah filter lain agar tidak meluas tidak sengaja.

4. Kolom `members.name` dan `members.phone` tidak dipakai konsisten dalam flow utama.
- Perlu diputuskan: dipertahankan sebagai legacy atau dibersihkan lewat refactor migration.

5. Belum terlihat test suite domain-specific untuk logic pembelian/poin.
- Sangat disarankan menambah test feature/integration untuk area transaksi.

---

## 13. Rekomendasi Pasca-Handover

Prioritas tinggi:
1. Kunci register publik jika sistem bukan self-signup.
2. Review dan tegaskan role policy untuk route pembelian create/process.
3. Tambahkan test otomatis untuk perhitungan poin dan race-condition stok.
4. Tambah audit log perubahan system settings.

Prioritas menengah:
1. Rapikan schema member (kolom denormalized).
2. Tambah filter tanggal pada halaman pembelian/export.
3. Tambah nomor referensi terminal/kasir shift untuk audit kasir.

---

## 14. Quick Troubleshooting Guide

### Kasus: transaksi gagal dengan pesan stok tidak cukup
- Penyebab kemungkinan: stok berubah saat checkout sedang berlangsung.
- Aksi: ulangi dari halaman pilih produk, cek stok terbaru.

### Kasus: member tidak ditemukan saat input telepon
- Cek user dengan role member dan phone terisi.
- Cek member profile sudah terbentuk.

### Kasus: angka uang tidak terbaca benar
- Sistem menormalisasi input dengan menghapus karakter non-digit.
- Pastikan input hanya format nominal standar.

### Kasus: gambar produk tidak tampil
- Cek file ada di storage disk `public` pada path `products/*`.
- Cek route media dan permission storage.

---

## 15. Lampiran: Ringkasan Entity Relationship

- User hasMany Purchase.
- User hasOne Member (untuk role member).
- Member belongsTo User.
- Member hasMany Purchase.
- Purchase belongsTo User.
- Purchase belongsTo Member (nullable).
- Purchase hasMany PurchaseItem.
- PurchaseItem belongsTo Purchase.
- PurchaseItem belongsTo Product.
- Product hasMany PurchaseItem.
- SystemSetting singleton global.

---

## 16. Definisi Siap Handover

Handover dinyatakan siap jika:
1. Tim penerima berhasil setup aplikasi dari nol.
2. Semua role dapat login dan menjalankan SOP masing-masing.
3. UAT checklist lulus.
4. Risiko pada bagian known gaps sudah dipahami dan disetujui.
5. Akses server, DB, storage, dan credential transfer dilakukan sesuai SOP keamanan perusahaan.

---

## 17. Catatan Penutup
Dokumentasi ini dibuat untuk memberikan sudut pandang lengkap: teknis, bisnis, operasional, dan risiko implementasi. Jika sistem akan dikembangkan lebih lanjut, gunakan dokumen ini sebagai baseline arsitektur dan baseline policy sebelum refactor.
