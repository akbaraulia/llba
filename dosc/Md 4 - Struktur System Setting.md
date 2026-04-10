# Md 4 - Struktur System Setting Sampai Selesai Total

Dokumen ini fokus pada modul pengaturan sistem poin yang dipakai oleh transaksi pembelian.

Urutan internal:

1. Migration system settings.
2. Model system setting.
3. Controller system setting.
4. Route.
5. View.
6. Testing update konfigurasi poin.

## 1) File Acuan

1. Migration:
    - `database/migrations/2026_04_06_070146_create_system_settings_table.php`
2. Model:
    - `app/Models/SystemSetting.php`
3. Controller:
    - `app/Http/Controllers/SystemSettingController.php`
4. Route:
    - `routes/web.php`
5. View:
    - `resources/views/system-settings/edit.blade.php`

## 1.1) Perintah Terminal Tahap System Setting

Jika membangun ulang modul System Setting dari awal:

```bash
php artisan make:model SystemSetting -m
php artisan make:controller SystemSettingController
php artisan migrate
```

Catatan:

1. Migration system settings pada proyek ini sudah menyisipkan data default saat dijalankan.
2. Setelah update controller dan view, lakukan uji simpan nilai poin dari halaman settings.

## 2) Tahap Per Tahap

### Tahap A - Migration system_settings

Kolom inti:

1. `point_redeem_value`
2. `point_earn_spend`
3. `default_max_redeem_percentage`

Poin khusus:

1. Migration ini langsung insert data default awal.
2. Jadi tabel sudah memiliki nilai standar sejak awal.

### Tahap B - Model SystemSetting

Fungsi model:

1. Menentukan fillable field.
2. Menentukan cast decimal.
3. Menyediakan method `current()`.

Makna `current()`:

1. Mengambil record settings pertama.
2. Jika belum ada, otomatis membuat record default.

### Tahap C - Controller SystemSetting

Method:

1. `edit` menampilkan form pengaturan.
2. `update` menyimpan perubahan setelah validasi.

Validasi penting:

1. Semua nilai harus numeric.
2. Nilai rate harus lebih besar dari 0.
3. Persentase maksimal berada di rentang 0 sampai 100.

### Tahap D - Route System Setting

Route:

1. `GET /system-settings` untuk halaman edit.
2. `PUT /system-settings` untuk simpan update.

Akses:

1. Hanya admin.

### Tahap E - View System Setting

Isi form:

1. Nilai tukar poin ke rupiah.
2. Rate perolehan poin dari nominal belanja.
3. Batas maksimal redeem poin default.

Keterhubungan:

1. Nilai ini langsung memengaruhi perhitungan pada modul Purchase.

## 3) Istilah Penting Saat Pengujian Modul System Setting

1. Business rule
   Definisi: aturan bisnis yang mengatur kalkulasi poin.

2. Default value
   Definisi: nilai awal saat konfigurasi belum diubah admin.

3. Numeric validation
   Definisi: memastikan input bisa dihitung secara matematis.

## 4) Skenario Uji Wajib

1. Admin dapat membuka halaman settings.
2. Non-admin tidak dapat membuka halaman settings.
3. Simpan nilai valid berhasil.
4. Nilai nol atau negatif pada field tertentu ditolak.
5. Nilai di atas 100 pada persentase ditolak.
6. Setelah update settings, simulasi pembelian memakai nilai terbaru.

## 5) Output Akhir Md 4

Struktur System Setting selesai total dan siap dipakai untuk kalkulasi poin pada transaksi.
