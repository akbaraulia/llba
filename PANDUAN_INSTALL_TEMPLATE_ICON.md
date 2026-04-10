# Panduan Install + Template UI (Tanpa Logic) + Kode Icon

Dokumen ini berisi 3 hal sesuai permintaan:

1. Langkah install project dari awal.
2. Seluruh template Tailwind/Bootstrap yang dipakai, ditulis ulang tanpa logic.
3. Seluruh kode icon yang dipakai di project ini (tanpa logic).

---

## 1) Cara Install Dari Awal

### Kebutuhan minimum

- PHP 8.2+
- Composer
- Node.js 18+ dan npm
- MySQL / MariaDB

### Step 1 - Create Laravel

```bash
composer create-project laravel/laravel final-kasir
cd final-kasir
```

### Step 2 - Install package PHP yang dipakai project

```bash
composer require barryvdh/laravel-dompdf
composer require --dev laravel/breeze
```

### Step 3 - Install auth scaffolding (Blade Breeze)

```bash
php artisan breeze:install blade
```

### Step 4 - Install dependency frontend

```bash
npm install
```

### Step 5 - Setup environment

```bash
copy .env.example .env
php artisan key:generate
```

Lalu edit `.env` bagian database:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=nama_database_kamu
DB_USERNAME=root
DB_PASSWORD=
```

### Step 6 - Migrasi dan seed data

```bash
php artisan migrate
php artisan db:seed
```

### Step 7 - Jalankan project

Terminal 1:

```bash
php artisan serve
```

Terminal 2:

```bash
npm run dev
```

Akses aplikasi di:

- http://127.0.0.1:8000

### Akun login demo (hasil seeder)

- Admin: `admin.test@gmail.com` / `password`
- Petugas: `petugas.test@gmail.com` / `password`

### Alternatif cepat (script bawaan composer)

```bash
composer run setup
composer run dev
```

---

## 2) Seluruh Template Tailwind/Bootstrap (Tanpa Logic)

Catatan penting:

- Semua snippet di bawah ini sudah ditulis sebagai template statis.
- Tidak ada Blade condition/loop, tidak ada variable dinamis, tidak ada JS logic bisnis.
- Ini bisa dipakai sebagai referensi UI murni.

### 2.1 Entry Tailwind + CSS utama

```css
@tailwind base;
@tailwind components;
@tailwind utilities;

:root {
    --panel-border: rgba(31, 27, 22, 0.12);
    --panel-shadow: 0 12px 30px rgba(219, 95, 34, 0.08);
    --muted-text: rgba(31, 27, 22, 0.62);
}

body {
    font-family: "Figtree", sans-serif;
    color: #1f1b16;
}

.panel-card {
    background: rgba(255, 255, 255, 0.94);
    border: 1px solid var(--panel-border);
    border-radius: 18px;
    box-shadow: var(--panel-shadow);
}

.muted {
    color: var(--muted-text);
}

.table > :not(caption) > * > * {
    border-color: rgba(31, 27, 22, 0.08);
}
```

### 2.2 Layout App (Tailwind + Bootstrap CDN)

```html
<!DOCTYPE html>
<html lang="id">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1" />

        <link rel="preconnect" href="https://fonts.bunny.net" />
        <link
            href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap"
            rel="stylesheet"
        />

        <link
            href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
            rel="stylesheet"
        />
        <link rel="stylesheet" href="/build/assets/app.css" />
    </head>
    <body class="min-h-screen bg-[#F6F0E8] antialiased">
        <div class="min-h-screen lg:flex">
            <!-- sidebar -->

            <main class="min-w-0 flex-1 p-4 md:p-5 lg:p-5">
                <section
                    class="rounded-[24px] border border-[#1F1B16]/10 bg-white/90 px-5 py-5 shadow-[0_15px_30px_rgba(219,95,34,0.08)] md:px-6"
                >
                    <div
                        class="flex flex-wrap items-start justify-between gap-4"
                    >
                        <div>
                            <h1
                                class="text-[20px] font-semibold md:text-[24px]"
                            >
                                Dashboard
                            </h1>
                            <p
                                class="mt-1 text-[13px] text-[#1F1B16]/60 md:text-[14px]"
                            >
                                Ringkasan aktivitas aplikasi.
                            </p>
                        </div>

                        <div class="flex items-center gap-2 md:gap-3">
                            <span
                                class="inline-flex rounded-full bg-[#F8F2E8] px-3 py-1 text-[12px] font-bold uppercase text-[#B45A23]"
                            >
                                ADMIN
                            </span>

                            <button
                                type="button"
                                class="inline-flex rounded-lg border border-[#1F1B16]/45 px-3 py-1.5 text-[13px] font-medium text-[#1F1B16] transition hover:bg-[#1F1B16] hover:text-white md:px-4 md:py-2"
                            >
                                Logout
                            </button>
                        </div>
                    </div>
                </section>

                <div class="mt-5">
                    <!-- page content -->
                </div>
            </main>
        </div>

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    </body>
</html>
```

### 2.3 Sidebar Navigation

```html
<aside
    class="panel-sidebar w-full bg-gradient-to-b from-[#1F1B16] to-[#2B1F18] px-6 py-6 text-white lg:min-h-screen lg:w-[290px] lg:shrink-0"
>
    <div class="lg:sticky lg:top-6">
        <h2 class="text-[24px] font-bold tracking-tight">Alfa Beta</h2>
        <p class="mt-1 text-[13px] text-white/65">Flow Notion Mode</p>

        <nav class="mt-6 space-y-2 text-[15px] font-semibold leading-tight">
            <a
                href="#"
                class="block rounded-2xl px-4 py-2 transition bg-gradient-to-r from-[#DB5F22] to-[#F09A63] text-white shadow-[0_10px_24px_rgba(219,95,34,0.3)]"
                >Dashboard</a
            >
            <a
                href="#"
                class="block rounded-2xl px-4 py-2 transition text-white/95 hover:bg-white/10"
                >Produk</a
            >
            <a
                href="#"
                class="block rounded-2xl px-4 py-2 transition text-white/95 hover:bg-white/10"
                >Pembelian</a
            >
            <a
                href="#"
                class="block rounded-2xl px-4 py-2 transition text-white/95 hover:bg-white/10"
                >Tambah Pembelian</a
            >
            <a
                href="#"
                class="block rounded-2xl px-4 py-2 transition text-white/95 hover:bg-white/10"
                >Data Pengguna</a
            >
            <a
                href="#"
                class="block rounded-2xl px-4 py-2 transition text-white/95 hover:bg-white/10"
                >System Settings</a
            >
        </nav>
    </div>
</aside>
```

### 2.4 Layout Guest (Auth)

```html
<!DOCTYPE html>
<html lang="id">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1" />
        <link rel="preconnect" href="https://fonts.bunny.net" />
        <link
            href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap"
            rel="stylesheet"
        />
        <link rel="stylesheet" href="/build/assets/app.css" />
    </head>
    <body class="font-sans text-gray-900 antialiased">
        <div
            class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gray-100"
        >
            <div
                class="w-full sm:max-w-md mt-6 px-6 py-4 bg-white shadow-md overflow-hidden sm:rounded-lg"
            >
                <!-- auth content -->
            </div>
        </div>
    </body>
</html>
```

### 2.5 Landing Page (Welcome)

```html
<body
    class="min-h-screen bg-[#F8F2E8] text-[#1F1B16] [font-family:'Plus_Jakarta_Sans',sans-serif]"
>
    <header class="px-3 pb-2 pt-4">
        <div
            class="mx-auto w-full max-w-[1240px] rounded-[26px] border border-[#1F1B16]/15 bg-white px-4 py-3 md:px-6"
        >
            <div class="flex items-center justify-between gap-3">
                <a
                    href="#"
                    class="flex items-center gap-3"
                    aria-label="Alfa Beta"
                >
                    <span
                        class="grid h-9 w-9 place-items-center rounded-[10px] bg-gradient-to-br from-[#e7743c] to-[#DB5F22] shadow-[0_8px_18px_rgba(219,95,34,0.3)]"
                    >
                        <span class="h-1 w-4 rounded-full bg-white"></span>
                        <span
                            class="-mt-1 h-1 w-3 rounded-full bg-white"
                        ></span>
                    </span>
                    <span
                        class="text-[24px] font-bold tracking-[-0.04em] md:text-[30px] [font-family:'Sora',sans-serif]"
                        >Alfa Beta</span
                    >
                </a>

                <nav
                    class="hidden flex-1 items-center justify-center gap-10 lg:flex"
                    aria-label="Menu utama"
                >
                    <a
                        href="#"
                        class="text-[17px] font-bold tracking-[-0.02em] text-[#1F1B16]/70 transition hover:text-[#1F1B16] xl:text-[22px]"
                        >Fitur</a
                    >
                    <a
                        href="#"
                        class="text-[17px] font-bold tracking-[-0.02em] text-[#1F1B16]/70 transition hover:text-[#1F1B16] xl:text-[22px]"
                        >Harga</a
                    >
                    <a
                        href="#"
                        class="text-[17px] font-bold tracking-[-0.02em] text-[#1F1B16]/70 transition hover:text-[#1F1B16] xl:text-[22px]"
                        >Testimoni</a
                    >
                </nav>

                <a
                    href="#"
                    class="inline-flex h-11 items-center justify-center rounded-full border border-transparent bg-gradient-to-br from-[#DB5F22] to-[#eb915b] px-4 text-sm font-extrabold text-white shadow-[0_12px_20px_rgba(219,95,34,0.28)] transition hover:-translate-y-0.5 md:h-12 md:px-6 md:text-lg"
                    >Coba Gratis</a
                >
            </div>
        </div>
    </header>

    <main class="mx-auto w-full max-w-[1240px] px-3 pb-3 pt-2">
        <section
            class="rounded-[28px] border border-[#1F1B16]/15 bg-gradient-to-br from-[#FFFDFC] to-[#FCF9F4] p-5 shadow-[0_28px_48px_rgba(31,27,22,0.07)] md:p-8 xl:p-10"
        >
            <div class="mx-auto max-w-[900px] text-center">
                <span
                    class="mx-auto inline-flex w-fit items-center rounded-full border border-[#DB5F22]/30 bg-[#DB5F22]/10 px-3 py-2 text-xs font-bold text-[#985026] md:px-4 md:text-sm"
                    >Aplikasi kasir modern</span
                >
                <h1
                    class="mt-4 text-[40px] font-extrabold leading-[0.98] tracking-[-0.05em] text-[#1F1B16] md:text-[58px] xl:text-[70px] [font-family:'Sora',sans-serif]"
                >
                    Kelola Toko Jadi
                    <span class="text-[#DB5F22]">Lebih Mudah</span> &amp; Cepat
                </h1>
                <p
                    class="mx-auto mt-5 max-w-[860px] text-[15px] leading-[1.5] text-[#6f6458] md:text-[18px] xl:text-[20px]"
                >
                    Alfa Beta membantu bisnis mengatur transaksi, stok, member,
                    dan laporan dalam satu alur kerja.
                </p>
            </div>
        </section>
    </main>
</body>
```

### 2.6 Dashboard

```html
<div class="row g-3 mb-4">
    <div class="col-md-6 col-xl-3">
        <div class="panel-card p-3">
            <p class="muted mb-1">Total Produk</p>
            <h3 class="mb-0">120</h3>
        </div>
    </div>
    <div class="col-md-6 col-xl-3">
        <div class="panel-card p-3">
            <p class="muted mb-1">Stok Menipis (&lt;5)</p>
            <h3 class="mb-0">7</h3>
        </div>
    </div>
    <div class="col-md-6 col-xl-3">
        <div class="panel-card p-3">
            <p class="muted mb-1">Transaksi Hari Ini</p>
            <h3 class="mb-0">35</h3>
        </div>
    </div>
    <div class="col-md-6 col-xl-3">
        <div class="panel-card p-3">
            <p class="muted mb-1">Omzet Hari Ini</p>
            <h3 class="mb-0">Rp 2.350.000</h3>
        </div>
    </div>
</div>

<div class="panel-card p-3 p-lg-4 mb-4">
    <h5 class="mb-3">Transaksi Terbaru</h5>
    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th>No Invoice</th>
                    <th>Tanggal</th>
                    <th>Kasir</th>
                    <th>Member</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>INV-001</td>
                    <td>09/04/2026 10:30</td>
                    <td>Admin Demo</td>
                    <td>Bukan Member</td>
                    <td>Rp 150.000</td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
```

### 2.7 Produk - Index + Modal

```html
<div class="panel-card p-3 p-lg-4">
    <div
        class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-3"
    >
        <form class="d-flex gap-2" method="GET" action="#">
            <input
                class="form-control"
                type="text"
                name="q"
                placeholder="Cari nama produk..."
            />
            <button class="btn btn-outline-dark">Cari</button>
        </form>

        <a href="#" class="btn btn-dark">Tambah Produk</a>
    </div>

    <div class="table-responsive">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>Gambar</th>
                    <th>Nama</th>
                    <th>Harga</th>
                    <th>Stok</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>
                        <div
                            class="card"
                            style="width: 120px; border-radius: 12px; overflow:hidden;"
                        >
                            <img
                                src="/storage/example.jpg"
                                alt="Produk"
                                style="height: 80px; object-fit: cover;"
                            />
                        </div>
                    </td>
                    <td>Teh Botol</td>
                    <td>Rp 5.000</td>
                    <td>100</td>
                    <td>
                        <div class="d-flex flex-wrap gap-1">
                            <a href="#" class="btn btn-sm btn-outline-primary"
                                >Edit</a
                            >
                            <button
                                class="btn btn-sm btn-outline-warning"
                                data-bs-toggle="modal"
                                data-bs-target="#stockModal1"
                            >
                                Update Stok
                            </button>
                            <button
                                class="btn btn-sm btn-outline-danger"
                                data-bs-toggle="modal"
                                data-bs-target="#deleteModal1"
                            >
                                Hapus
                            </button>
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

<div class="modal fade" id="stockModal1" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Update Stok Teh Botol</h5>
                <button
                    type="button"
                    class="btn-close"
                    data-bs-dismiss="modal"
                ></button>
            </div>
            <div class="modal-body">
                <label class="form-label">Stok Baru</label>
                <input type="number" min="0" class="form-control" value="100" />
            </div>
            <div class="modal-footer">
                <button
                    type="button"
                    class="btn btn-secondary"
                    data-bs-dismiss="modal"
                >
                    Batal
                </button>
                <button class="btn btn-warning">Simpan</button>
            </div>
        </div>
    </div>
</div>
```

### 2.8 Produk - Form Create/Edit

```html
<div class="panel-card p-3 p-lg-4">
    <form class="row g-3 needs-validation" novalidate>
        <div class="col-md-6">
            <label class="form-label"
                >Nama Produk <span class="text-danger">*</span></label
            >
            <input name="name" class="form-control" required />
            <div class="invalid-feedback">Nama produk harus diisi!</div>
        </div>

        <div class="col-md-6">
            <label class="form-label"
                >Harga <span class="text-danger">*</span></label
            >
            <input name="price" class="form-control" required />
            <div class="invalid-feedback">Harga harus diisi!</div>
            <small class="text-muted">Format otomatis rupiah.</small>
        </div>

        <div class="col-md-6">
            <label class="form-label"
                >Stok <span class="text-danger">*</span></label
            >
            <input
                type="number"
                min="0"
                name="stock"
                class="form-control"
                required
            />
            <div class="invalid-feedback">Stok harus diisi!</div>
        </div>

        <div class="col-md-6">
            <label class="form-label">Gambar Produk</label>
            <input
                type="file"
                name="image"
                class="form-control"
                accept="image/*"
            />
        </div>

        <div class="col-12 d-flex gap-2">
            <a class="btn btn-outline-dark" href="#">Kembali</a>
            <button class="btn btn-dark">Simpan</button>
        </div>
    </form>
</div>
```

### 2.9 Pengguna - Index + Form

```html
<div class="panel-card p-3 p-lg-4">
    <div
        class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-3"
    >
        <form class="d-flex gap-2" method="GET" action="#">
            <input
                class="form-control"
                type="text"
                name="q"
                placeholder="Cari nama/email/telepon..."
            />
            <button class="btn btn-outline-dark">Cari</button>
        </form>

        <a href="#" class="btn btn-dark">Tambah Pengguna</a>
    </div>

    <div class="table-responsive">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>Nama</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Telepon</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Admin Demo</td>
                    <td>admin.test@gmail.com</td>
                    <td><span class="badge text-bg-secondary">ADMIN</span></td>
                    <td>081100000001</td>
                    <td>
                        <div class="d-flex flex-wrap gap-1">
                            <a href="#" class="btn btn-sm btn-outline-primary"
                                >Edit</a
                            >
                            <button
                                class="btn btn-sm btn-outline-danger"
                                data-bs-toggle="modal"
                                data-bs-target="#deleteUserModal1"
                            >
                                Hapus
                            </button>
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
```

```html
<div class="panel-card p-3 p-lg-4">
    <form class="row g-3 needs-validation" novalidate>
        <div class="col-md-6">
            <label class="form-label"
                >Nama <span class="text-danger">*</span></label
            >
            <input name="name" class="form-control" required />
        </div>

        <div class="col-md-6">
            <label class="form-label"
                >Email <span class="text-danger">*</span></label
            >
            <input type="email" name="email" class="form-control" required />
        </div>

        <div class="col-md-6">
            <label class="form-label">Nomor Telepon</label>
            <input
                name="phone"
                class="form-control"
                placeholder="08xxxxxxxxxx"
            />
        </div>

        <div class="col-md-6">
            <label class="form-label"
                >Role <span class="text-danger">*</span></label
            >
            <select name="role" class="form-select" required>
                <option value="admin">Admin</option>
                <option value="petugas">Petugas</option>
                <option value="member">Member</option>
            </select>
        </div>

        <div class="col-md-6">
            <label class="form-label"
                >Password <span class="text-danger">*</span></label
            >
            <input
                type="password"
                name="password"
                class="form-control"
                required
            />
        </div>

        <div class="col-12 d-flex gap-2">
            <a href="#" class="btn btn-outline-dark">Kembali</a>
            <button class="btn btn-dark">Simpan</button>
        </div>
    </form>
</div>
```

### 2.10 Pembelian - Index + Detail Modal

```html
<div class="panel-card p-3 p-lg-4">
    <div
        class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-3"
    >
        <div class="d-flex gap-2">
            <a href="#" class="btn btn-dark">Tambah Pembelian</a>
            <a href="#" class="btn btn-outline-success">Export Rekap</a>
        </div>
    </div>

    <div class="table-responsive">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>Invoice</th>
                    <th>Tanggal</th>
                    <th>Kasir</th>
                    <th>Member</th>
                    <th>Total</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>INV-001</td>
                    <td>09/04/2026 10:30</td>
                    <td>Petugas Demo</td>
                    <td>Bukan Member</td>
                    <td>Rp 120.000</td>
                    <td>
                        <div class="d-flex gap-1">
                            <button
                                class="btn btn-sm btn-outline-primary"
                                data-bs-toggle="modal"
                                data-bs-target="#detailModal1"
                            >
                                Lihat
                            </button>
                            <a href="#" class="btn btn-sm btn-outline-dark"
                                >Struk</a
                            >
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
```

```html
<div class="modal fade" id="detailModal1" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Detail INV-001</h5>
                <button
                    type="button"
                    class="btn-close"
                    data-bs-dismiss="modal"
                ></button>
            </div>
            <div class="modal-body">
                <div class="table-responsive">
                    <table class="table table-sm">
                        <thead>
                            <tr>
                                <th>Produk</th>
                                <th>Qty</th>
                                <th>Harga</th>
                                <th>Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Teh Botol</td>
                                <td>2</td>
                                <td>Rp 5.000</td>
                                <td>Rp 10.000</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="row mt-2">
                    <div class="col-md-6">
                        <p class="mb-1">
                            Status: <strong>Bukan Member</strong>
                        </p>
                        <p class="mb-1">Poin Digunakan: <strong>0</strong></p>
                        <p class="mb-1">Poin Didapat: <strong>0</strong></p>
                    </div>
                    <div class="col-md-6 text-md-end">
                        <p class="mb-1">
                            Total Awal: <strong>Rp 120.000</strong>
                        </p>
                        <p class="mb-1">
                            Total Bayar: <strong>Rp 120.000</strong>
                        </p>
                        <p class="mb-1">Dibayar: <strong>Rp 150.000</strong></p>
                        <p class="mb-0">
                            Kembalian: <strong>Rp 30.000</strong>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
```

### 2.11 Pembelian - Pilih Produk (Create)

```html
<form class="panel-card p-3 p-lg-4">
    <p class="muted">Pilih produk dan atur jumlah dengan tombol plus/minus.</p>

    <div class="row g-3">
        <div class="col-md-6 col-xl-4">
            <div
                class="card h-100"
                style="border-radius:14px; border:1px solid #e7d8c8;"
            >
                <img
                    src="/storage/example.jpg"
                    class="card-img-top"
                    alt="Produk"
                    style="height: 170px; object-fit: cover;"
                />
                <div class="card-body">
                    <h5 class="card-title mb-1">Teh Botol</h5>
                    <div class="muted small mb-2">
                        Stok tersedia: <strong>100</strong>
                    </div>
                    <div class="fw-bold mb-3">Rp 5.000</div>

                    <div class="input-group mb-2">
                        <button type="button" class="btn btn-outline-secondary">
                            -
                        </button>
                        <input
                            type="number"
                            min="0"
                            max="100"
                            class="form-control"
                            value="0"
                        />
                        <button type="button" class="btn btn-outline-secondary">
                            +
                        </button>
                    </div>

                    <div class="small">Subtotal: <span>Rp 0</span></div>
                </div>
            </div>
        </div>
    </div>

    <div
        class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3 mt-4"
    >
        <h4 class="mb-0">Total: <span>Rp 0</span></h4>
        <div class="d-flex gap-2">
            <a href="#" class="btn btn-outline-dark">Kembali</a>
            <button class="btn btn-dark">Selanjutnya</button>
        </div>
    </div>
</form>
```

### 2.12 Pembelian - Data Pembeli (Member / Non Member)

```html
<div class="row g-3">
    <div class="col-lg-7">
        <div class="panel-card p-3 p-lg-4">
            <h5 class="mb-3">Ringkasan Belanja</h5>
            <div class="table-responsive mb-3">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Produk</th>
                            <th>Qty</th>
                            <th>Harga</th>
                            <th>Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Teh Botol</td>
                            <td>2</td>
                            <td>Rp 5.000</td>
                            <td>Rp 10.000</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="alert alert-secondary mb-0">
                Total Awal: <strong>Rp 10.000</strong>
            </div>
        </div>
    </div>

    <div class="col-lg-5">
        <form class="panel-card p-3 p-lg-4 needs-validation" novalidate>
            <h5 class="mb-3">Data Pembeli</h5>

            <div class="mb-3">
                <label class="form-label d-block">Status Member</label>
                <div class="form-check form-check-inline">
                    <input
                        class="form-check-input"
                        type="radio"
                        name="member_status"
                        id="nonMember"
                        value="non_member"
                        checked
                    />
                    <label class="form-check-label" for="nonMember"
                        >Bukan Member</label
                    >
                </div>
                <div class="form-check form-check-inline">
                    <input
                        class="form-check-input"
                        type="radio"
                        name="member_status"
                        id="member"
                        value="member"
                    />
                    <label class="form-check-label" for="member">Member</label>
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label"
                    >Uang Dibayar <span class="text-danger">*</span></label
                >
                <input
                    type="text"
                    class="form-control"
                    placeholder="Contoh: Rp 100.000"
                />
                <div class="small mt-2">
                    Estimasi Kembalian: <strong>Rp 0</strong>
                </div>
            </div>

            <div class="d-flex gap-2">
                <a href="#" class="btn btn-outline-dark">Kembali</a>
                <button class="btn btn-dark">Beli</button>
            </div>
        </form>
    </div>
</div>
```

### 2.13 Struk Pembelian (Preview)

```html
<div class="row justify-content-center">
    <div class="col-xl-10">
        <div class="panel-card p-3 p-lg-4">
            <div class="d-flex justify-content-between align-items-start mb-3">
                <div>
                    <h4 class="mb-1">INV-001</h4>
                    <div class="muted">09/04/2026 10:30</div>
                    <div class="muted">Kasir: Admin Demo</div>
                </div>
                <div class="d-flex flex-wrap gap-2">
                    <a href="#" class="btn btn-outline-dark btn-sm">Kembali</a>
                    <a href="#" class="btn btn-outline-primary btn-sm"
                        >Download PDF</a
                    >
                    <button type="button" class="btn btn-dark btn-sm">
                        Print
                    </button>
                </div>
            </div>

            <div
                class="mb-3"
                style="border:1px solid #e6d8c9; border-radius:12px; overflow:hidden;"
            >
                <iframe
                    title="Preview Receipt PDF"
                    style="width:100%; height:75vh; border:0;"
                ></iframe>
            </div>
        </div>
    </div>
</div>
```

### 2.14 Struk PDF (Template cetak)

```html
<!doctype html>
<html lang="id">
    <head>
        <meta charset="utf-8" />
        <title>Struk INV-001</title>
        <style>
            body {
                font-family:
                    DejaVu Sans,
                    sans-serif;
                font-size: 10px;
                color: #111;
                margin: 8px;
            }
            .center {
                text-align: center;
            }
            .bold {
                font-weight: bold;
            }
            .line {
                border-top: 1px dashed #333;
                margin: 6px 0;
            }
            table {
                width: 100%;
                border-collapse: collapse;
            }
            th,
            td {
                padding: 2px 0;
                vertical-align: top;
            }
            .right {
                text-align: right;
            }
            .small {
                font-size: 9px;
            }
        </style>
    </head>
    <body>
        <div class="center">
            <div class="bold">Alfa Beta</div>
            <div class="small">Bukti Pembayaran</div>
        </div>

        <div class="line"></div>

        <table>
            <tr>
                <td>No. Invoice</td>
                <td class="right">INV-001</td>
            </tr>
            <tr>
                <td>Tanggal</td>
                <td class="right">09/04/2026 10:30</td>
            </tr>
            <tr>
                <td>Kasir</td>
                <td class="right">Admin Demo</td>
            </tr>
            <tr>
                <td>Status</td>
                <td class="right">Non-Member</td>
            </tr>
        </table>

        <div class="line"></div>

        <div>Teh Botol</div>
        <table>
            <tr>
                <td>2 x Rp 5.000</td>
                <td class="right">Rp 10.000</td>
            </tr>
        </table>

        <div class="line"></div>

        <table>
            <tr>
                <td>Total Awal</td>
                <td class="right">Rp 10.000</td>
            </tr>
            <tr>
                <td>Diskon Poin</td>
                <td class="right">Rp 0</td>
            </tr>
            <tr>
                <td class="bold">Total Bayar</td>
                <td class="right bold">Rp 10.000</td>
            </tr>
            <tr>
                <td>Uang Dibayar</td>
                <td class="right">Rp 20.000</td>
            </tr>
            <tr>
                <td>Kembalian</td>
                <td class="right">Rp 10.000</td>
            </tr>
        </table>

        <div class="line"></div>
        <div class="center small">Terima kasih atas kunjungan Anda.</div>
        <div class="center small">
            Simpan struk ini sebagai bukti transaksi.
        </div>
    </body>
</html>
```

### 2.15 System Settings

```html
<div class="panel-card p-3 p-lg-4">
    <form class="row g-3 needs-validation" novalidate>
        <div class="col-md-4">
            <label class="form-label"
                >1 Poin = Berapa Rupiah
                <span class="text-danger">*</span></label
            >
            <input
                type="number"
                min="0.01"
                step="0.01"
                name="point_redeem_value"
                class="form-control"
                required
            />
            <small class="text-muted"
                >Contoh 1 berarti 1 poin bernilai Rp 1.</small
            >
        </div>

        <div class="col-md-4">
            <label class="form-label"
                >1 Poin Didapat per Rupiah
                <span class="text-danger">*</span></label
            >
            <input
                type="number"
                min="0.01"
                step="0.01"
                name="point_earn_spend"
                class="form-control"
                required
            />
            <small class="text-muted"
                >Contoh 10000 berarti 1 poin per belanja Rp 10.000.</small
            >
        </div>

        <div class="col-md-4">
            <label class="form-label"
                >Default Maks Pemakaian Poin (%)
                <span class="text-danger">*</span></label
            >
            <input
                type="number"
                min="0"
                max="100"
                step="0.01"
                name="default_max_redeem_percentage"
                class="form-control"
                required
            />
            <small class="text-muted"
                >Default harus 10%, bisa diubah saat dibutuhkan.</small
            >
        </div>

        <div class="col-12 d-flex gap-2">
            <button class="btn btn-dark">Simpan Settings</button>
        </div>
    </form>
</div>
```

### 2.16 Auth Pages (Login/Register/Forgot/Reset/Verify/Confirm)

```html
<!-- Login -->
<div
    class="mb-5 rounded-xl border border-orange-200 bg-orange-50 p-4 text-sm text-gray-800"
>
    <p class="font-semibold text-orange-700">Akun Login Demo</p>
    <div class="mt-2 space-y-1">
        <p>
            <span class="font-medium">Admin:</span> admin.test@gmail.com /
            password
        </p>
        <p>
            <span class="font-medium">Petugas:</span> petugas.test@gmail.com /
            password
        </p>
    </div>
</div>
<form>
    <div>
        <label class="block font-medium text-sm text-gray-700">Email</label>
        <input
            class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
            type="email"
            required
        />
    </div>
    <div class="mt-4">
        <label class="block font-medium text-sm text-gray-700">Password</label>
        <input
            class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
            type="password"
            required
        />
    </div>
    <div class="block mt-4">
        <label class="inline-flex items-center">
            <input
                type="checkbox"
                class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500"
            />
            <span class="ms-2 text-sm text-gray-600">Remember me</span>
        </label>
    </div>
    <div class="flex items-center justify-end mt-4 gap-3">
        <a class="underline text-sm text-gray-600 hover:text-gray-900" href="#"
            >Forgot your password?</a
        >
        <button
            class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700"
        >
            Log in
        </button>
    </div>
</form>
```

```html
<!-- Register / Forgot / Reset / Verify / Confirm (struktur Tailwind) -->
<form class="space-y-4">
    <div>
        <label class="block font-medium text-sm text-gray-700">Name</label>
        <input
            class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
            type="text"
        />
    </div>
    <div>
        <label class="block font-medium text-sm text-gray-700">Email</label>
        <input
            class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
            type="email"
        />
    </div>
    <div>
        <label class="block font-medium text-sm text-gray-700">Password</label>
        <input
            class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
            type="password"
        />
    </div>
    <div>
        <label class="block font-medium text-sm text-gray-700"
            >Confirm Password</label
        >
        <input
            class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
            type="password"
        />
    </div>

    <div class="flex items-center justify-end mt-4 gap-3">
        <button
            class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700"
        >
            Submit
        </button>
    </div>
</form>
```

### 2.17 Profile Page + Partials

```html
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
        <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
            <div class="max-w-xl">
                <h2 class="text-lg font-medium text-gray-900">
                    Profile Information
                </h2>
                <p class="mt-1 text-sm text-gray-600">
                    Update your account profile information.
                </p>
            </div>
        </div>

        <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
            <div class="max-w-xl">
                <h2 class="text-lg font-medium text-gray-900">
                    Update Password
                </h2>
                <p class="mt-1 text-sm text-gray-600">
                    Ensure your account is using a secure password.
                </p>
            </div>
        </div>

        <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
            <div class="max-w-xl">
                <h2 class="text-lg font-medium text-gray-900">
                    Delete Account
                </h2>
                <p class="mt-1 text-sm text-gray-600">
                    Delete account permanently.
                </p>
            </div>
        </div>
    </div>
</div>
```

### 2.18 Komponen Reusable Tailwind (Tanpa Logic)

```html
<!-- Primary Button -->
<button
    type="submit"
    class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150"
>
    Button
</button>

<!-- Secondary Button -->
<button
    type="button"
    class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150"
>
    Button
</button>

<!-- Danger Button -->
<button
    type="submit"
    class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-500 focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition ease-in-out duration-150"
>
    Delete
</button>

<!-- Text Input -->
<input
    class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
/>

<!-- Input Label -->
<label class="block font-medium text-sm text-gray-700">Label</label>

<!-- Input Error -->
<ul class="text-sm text-red-600 space-y-1">
    <li>Pesan error</li>
</ul>

<!-- Auth Session Status -->
<div class="font-medium text-sm text-green-600">Status berhasil</div>

<!-- Dropdown Link -->
<a
    class="block w-full px-4 py-2 text-start text-sm leading-5 text-gray-700 hover:bg-gray-100 transition duration-150 ease-in-out"
    >Menu Item</a
>

<!-- Nav Link -->
<a
    class="inline-flex items-center px-1 pt-1 border-b-2 border-indigo-400 text-sm font-medium leading-5 text-gray-900"
    >Menu</a
>

<!-- Responsive Nav Link -->
<a
    class="block w-full ps-3 pe-4 py-2 border-l-4 border-indigo-400 text-start text-base font-medium text-indigo-700 bg-indigo-50"
    >Menu Mobile</a
>

<!-- Modal Skeleton -->
<div class="fixed inset-0 overflow-y-auto px-4 py-6 sm:px-0 z-50">
    <div class="fixed inset-0">
        <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
    </div>
    <div
        class="mb-6 bg-white rounded-lg overflow-hidden shadow-xl sm:w-full sm:max-w-2xl sm:mx-auto"
    >
        <div class="p-6">Konten modal</div>
    </div>
</div>

<!-- Dropdown Skeleton -->
<div class="relative">
    <button type="button">Trigger</button>
    <div class="absolute z-50 mt-2 w-48 rounded-md shadow-lg end-0">
        <div class="rounded-md ring-1 ring-black ring-opacity-5 py-1 bg-white">
            <a
                class="block w-full px-4 py-2 text-start text-sm leading-5 text-gray-700 hover:bg-gray-100"
                >Item</a
            >
        </div>
    </div>
</div>
```

---

## 3) Kode Icon yang Dipakai di Project (Tanpa Logic)

Catatan:

- Project ini tidak memakai library icon eksternal seperti Font Awesome atau Bootstrap Icons.
- Icon utama dipakai sebagai inline SVG.

### 3.1 Icon Petir (welcome)

```html
<svg viewBox="0 0 24 24" class="h-5 w-5 fill-[#DB5F22]" aria-hidden="true">
    <path d="M13 2L5 14h6l-1 8 9-13h-6l1-7z" />
</svg>
```

### 3.2 Icon Grid/Kasir (welcome)

```html
<svg viewBox="0 0 24 24" class="h-5 w-5 fill-[#DB5F22]" aria-hidden="true">
    <path d="M4 5h16v14H4V5zm3 3v2h10V8H7zm0 4v4h3v-4H7zm5 0v4h5v-4h-5z" />
</svg>
```

### 3.3 Icon Dokumen/Laporan (welcome)

```html
<svg viewBox="0 0 24 24" class="h-5 w-5 fill-[#DB5F22]" aria-hidden="true">
    <path
        d="M6 3h10l4 4v14H6V3zm9 1.5V8h3.5L15 4.5zM8 11h10v2H8v-2zm0 4h10v2H8v-2z"
    />
</svg>
```

### 3.4 Logo SVG Aplikasi (application-logo)

```html
<svg viewBox="0 0 316 316" xmlns="http://www.w3.org/2000/svg">
    <path
        d="M305.8 81.125C305.77 80.995 305.69 80.885 305.65 80.755C305.56 80.525 305.49 80.285 305.37 80.075C305.29 79.935 305.17 79.815 305.07 79.685C304.94 79.515 304.83 79.325 304.68 79.175C304.55 79.045 304.39 78.955 304.25 78.845C304.09 78.715 303.95 78.575 303.77 78.475L251.32 48.275C249.97 47.495 248.31 47.495 246.96 48.275L194.51 78.475C194.33 78.575 194.19 78.725 194.03 78.845C193.89 78.955 193.73 79.045 193.6 79.175C193.45 79.325 193.34 79.515 193.21 79.685C193.11 79.815 192.99 79.935 192.91 80.075C192.79 80.285 192.71 80.525 192.63 80.755C192.58 80.875 192.51 80.995 192.48 81.125C192.38 81.495 192.33 81.875 192.33 82.265V139.625L148.62 164.795V52.575C148.62 52.185 148.57 51.805 148.47 51.435C148.44 51.305 148.36 51.195 148.32 51.065C148.23 50.835 148.16 50.595 148.04 50.385C147.96 50.245 147.84 50.125 147.74 49.995C147.61 49.825 147.5 49.635 147.35 49.485C147.22 49.355 147.06 49.265 146.92 49.155C146.76 49.025 146.62 48.885 146.44 48.785L93.99 18.585C92.64 17.805 90.98 17.805 89.63 18.585L37.18 48.785C37 48.885 36.86 49.035 36.7 49.155C36.56 49.265 36.4 49.355 36.27 49.485C36.12 49.635 36.01 49.825 35.88 49.995C35.78 50.125 35.66 50.245 35.58 50.385C35.46 50.595 35.38 50.835 35.3 51.065C35.25 51.185 35.18 51.305 35.15 51.435C35.05 51.805 35 52.185 35 52.575V232.235C35 233.795 35.84 235.245 37.19 236.025L142.1 296.425C142.33 296.555 142.58 296.635 142.82 296.725C142.93 296.765 143.04 296.835 143.16 296.865C143.53 296.965 143.9 297.015 144.28 297.015C144.66 297.015 145.03 296.965 145.4 296.865C145.5 296.835 145.59 296.775 145.69 296.745C145.95 296.655 146.21 296.565 146.45 296.435L251.36 236.035C252.72 235.255 253.55 233.815 253.55 232.245V174.885L303.81 145.945C305.17 145.165 306 143.725 306 142.155V82.265C305.95 81.875 305.89 81.495 305.8 81.125ZM144.2 227.205L100.57 202.515L146.39 176.135L196.66 147.195L240.33 172.335L208.29 190.625L144.2 227.205ZM244.75 114.995V164.795L226.39 154.225L201.03 139.625V89.825L219.39 100.395L244.75 114.995ZM249.12 57.105L292.81 82.265L249.12 107.425L205.43 82.265L249.12 57.105ZM114.49 184.425L96.13 194.995V85.305L121.49 70.705L139.85 60.135V169.815L114.49 184.425ZM91.76 27.425L135.45 52.585L91.76 77.745L48.07 52.585L91.76 27.425ZM43.67 60.135L62.03 70.705L87.39 85.305V202.545V202.555V202.565C87.39 202.735 87.44 202.895 87.46 203.055C87.49 203.265 87.49 203.485 87.55 203.695V203.705C87.6 203.875 87.69 204.035 87.76 204.195C87.84 204.375 87.89 204.575 87.99 204.745C87.99 204.745 87.99 204.755 88 204.755C88.09 204.905 88.22 205.035 88.33 205.175C88.45 205.335 88.55 205.495 88.69 205.635L88.7 205.645C88.82 205.765 88.98 205.855 89.12 205.965C89.28 206.085 89.42 206.225 89.59 206.325C89.6 206.325 89.6 206.325 89.61 206.335C89.62 206.335 89.62 206.345 89.63 206.345L139.87 234.775V285.065L43.67 229.705V60.135ZM244.75 229.705L148.58 285.075V234.775L219.8 194.115L244.75 179.875V229.705ZM297.2 139.625L253.49 164.795V114.995L278.85 100.395L297.21 89.825V139.625H297.2Z"
    />
</svg>
```

---

## Ringkasannya

- Install dari awal sudah dijabarkan step-by-step.
- Template Tailwind + Bootstrap sudah dikumpulkan dalam versi statis (tanpa logic).
- Semua icon yang dipakai project ini sudah dicantumkan dalam bentuk kode.
