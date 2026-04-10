# Md 2 - Struktur User Sampai Selesai Total

Dokumen ini menyelesaikan satu struktur penuh modul User sebelum pindah ke modul lain.

Urutan internal wajib:

1. Migration users dan members.
2. Model User dan Member.
3. Middleware role dan alias.
4. Controller User.
5. Route User.
6. View User.
7. Seeder akun awal.
8. Testing User.

## 1) File Acuan

1. Migration:
    - `database/migrations/0001_01_01_000000_create_users_table.php`
    - `database/migrations/2026_04_06_070024_create_members_table.php`
2. Model:
    - `app/Models/User.php`
    - `app/Models/Member.php`
3. Middleware:
    - `app/Http/Middleware/RoleMiddleware.php`
    - `bootstrap/app.php`
4. Controller:
    - `app/Http/Controllers/UserController.php`
5. Route:
    - `routes/web.php`
6. View:
    - `resources/views/users/index.blade.php`
    - `resources/views/users/create.blade.php`
    - `resources/views/users/edit.blade.php`
7. Seeder:
    - `database/seeders/DatabaseSeeder.php`

## 1.1) Perintah Terminal Tahap User

Jika membangun ulang struktur User dari awal, gunakan perintah berikut:

```bash
php artisan make:model Member -m
php artisan make:middleware RoleMiddleware
php artisan make:controller UserController
php artisan make:seeder DatabaseSeeder
php artisan migrate
php artisan db:seed
```

Catatan:

1. Model `User` biasanya sudah ada dari template Laravel, jadi tidak perlu dibuat ulang jika sudah tersedia.
2. Jika Anda mengubah migration setelah pernah migrate, gunakan rollback atau refresh sesuai kebutuhan pengujian.

## 2) Tahap Per Tahap

### Tahap A - Migration users

Tujuan:

1. Membuat tabel user sebagai pusat autentikasi dan role.

Struktur penting:

1. `name`
2. `email` unique
3. `phone` nullable unique
4. `role` default `petugas`
5. `password`

Keterhubungan:

1. Dipakai model `User`.
2. Dipakai middleware role untuk akses halaman.

### Tahap B - Migration members

Tujuan:

1. Menyimpan data khusus member (poin, batas redeem).

Struktur penting:

1. `user_id` foreign key ke users.
2. `points`.
3. `max_redeem_percentage`.

Keterhubungan:

1. Relasi `User` hasOne `Member`.
2. Digunakan saat user role member dibuat.

### Tahap C - Model User dan Member

Model `User` menjelaskan:

1. Fillable field.
2. Cast password ke hashed.
3. Relasi ke pembelian dan member profile.
4. Helper role: `isAdmin`, `isPetugas`, `isMember`.

Model `Member` menjelaskan:

1. Relasi ke `User`.
2. Relasi ke `Purchase`.

### Tahap D - Middleware role dan alias

`RoleMiddleware` berfungsi:

1. Memastikan user sudah login.
2. Memastikan role user termasuk role yang diizinkan route.

Di `bootstrap/app.php`, middleware ini didaftarkan sebagai alias `role`.

### Tahap E - Controller User

Method inti:

1. `index` untuk list + search.
2. `create` untuk tampilkan form tambah.
3. `store` untuk simpan user baru.
4. `edit` untuk form edit.
5. `update` untuk simpan perubahan.
6. `destroy` untuk hapus user.

Aturan penting pada `store` dan `update`:

1. Admin boleh set role admin/petugas/member.
2. Petugas hanya boleh membuat member.
3. Jika role member, otomatis dibuat data `members`.

### Tahap F - Route User

Akses route:

1. `users.index`, `users.create`, `users.store` untuk admin dan petugas.
2. `users.edit`, `users.update`, `users.destroy` hanya admin.

Keterhubungan:

1. Route memanggil method `UserController`.
2. Route binding `{user}` langsung memberi object model User.

### Tahap G - View User

1. `users.index` menampilkan list user, search, tombol edit/hapus.
2. `users.create` menampilkan form tambah user.
3. `users.edit` menampilkan form edit user.

Keterhubungan:

1. Semua form memakai named route.
2. Validasi error ditampilkan lewat blade directive `@error`.

### Tahap H - Seeder akun awal

Di `DatabaseSeeder`, dibuat akun:

1. Admin demo.
2. Petugas demo.

Tujuan:

1. Memudahkan login awal.
2. Memudahkan pengujian peran akses.

## 3) Istilah Penting Saat Pengujian Modul User

1. Role-based access
   Definisi: akses halaman berdasarkan peran akun.

2. Route model binding
   Definisi: parameter URL otomatis jadi object model.

3. Validation rule
   Definisi: aturan input agar data konsisten.

4. Mass assignment
   Definisi: pengisian data model via array menggunakan fillable.

5. Seeder
   Definisi: pengisian data awal otomatis.

## 4) Skenario Uji Wajib

1. Admin bisa membuat user admin/petugas/member.
2. Petugas hanya bisa membuat user member.
3. Email duplikat harus ditolak.
4. Phone duplikat harus ditolak.
5. Edit user berhasil.
6. Ubah password opsional berjalan benar.
7. User tidak boleh menghapus akun yang sedang login.
8. Route admin tidak bisa diakses user non-admin.

## 5) Output Akhir Md 2

Struktur User sudah selesai total dan siap dipakai sebagai dasar modul lain.
