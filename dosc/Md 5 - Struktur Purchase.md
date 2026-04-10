# Md 5 - Struktur Purchase Sampai Selesai Total

Dokumen ini adalah modul transaksi inti, karena melibatkan stok produk, data member, perhitungan poin, struk PDF, dan export rekap.

Urutan internal:

1. Migration purchases dan purchase_items.
2. Model Purchase dan PurchaseItem.
3. Controller Purchase.
4. Route Purchase.
5. View Purchase.
6. Testing transaksi lengkap.

## 1) File Acuan

1. Migration:
    - `database/migrations/2026_04_06_070102_create_purchases_table.php`
    - `database/migrations/2026_04_06_070114_create_purchase_items_table.php`
2. Model:
    - `app/Models/Purchase.php`
    - `app/Models/PurchaseItem.php`
3. Controller:
    - `app/Http/Controllers/PurchaseController.php`
4. Route:
    - `routes/web.php`
5. View:
    - `resources/views/purchases/create.blade.php`
    - `resources/views/purchases/member.blade.php`
    - `resources/views/purchases/receipt.blade.php`
    - `resources/views/purchases/index.blade.php`
    - `resources/views/purchases/receipt-struk-pdf.blade.php`

## 1.1) Perintah Terminal Tahap Purchase

Jika membangun ulang modul Purchase dari awal:

```bash
composer require barryvdh/laravel-dompdf
php artisan make:model Purchase -m
php artisan make:model PurchaseItem -m
php artisan make:controller PurchaseController
php artisan migrate
```

Catatan:

1. Package DomPDF dibutuhkan agar fitur struk PDF dapat berjalan.
2. Karena modul ini menyentuh stok dan poin, pengujian sangat disarankan dilakukan dengan data awal yang bersih.

## 2) Tahap Per Tahap

### Tahap A - Migration purchases

Kolom kunci:

1. `invoice_number`
2. `user_id`
3. `member_id`
4. `purchase_date`
5. `total_before_discount`
6. `total_after_discount`
7. `points_used`
8. `points_earned`
9. `cash_paid`
10. `change_amount`

Fungsi:

1. Menyimpan header transaksi.
2. Menyimpan ringkasan perhitungan diskon poin.

### Tahap B - Migration purchase_items

Kolom kunci:

1. `purchase_id`
2. `product_id`
3. `product_name`
4. `price`
5. `quantity`
6. `subtotal`

Fungsi:

1. Menyimpan detail item per transaksi.

### Tahap C - Model Purchase dan PurchaseItem

Relasi inti:

1. Purchase hasMany PurchaseItem.
2. Purchase belongsTo User.
3. Purchase belongsTo Member.
4. PurchaseItem belongsTo Purchase.
5. PurchaseItem belongsTo Product.

Cast penting:

1. Field uang pakai decimal.
2. `purchase_date` sebagai datetime.
3. `is_member` sebagai boolean.

### Tahap D - Controller Purchase

Alur method utama:

1. `create`:
    - menampilkan daftar produk untuk dipilih.
2. `prepare`:
    - validasi quantity.
    - cek stok tidak boleh kurang.
    - simpan checkout sementara ke session.
3. `memberForm`:
    - tampilkan ringkasan belanja.
    - proses alur member atau non-member.
4. `process`:
    - validasi data pembeli dan pembayaran.
    - hitung diskon poin.
    - hitung total akhir, kembalian, poin didapat.
    - jalankan transaksi database.
    - kurangi stok produk.
    - update poin member.
5. `receipt`:
    - menampilkan halaman struk.
6. `receiptPdf`:
    - membuat preview dan download PDF struk.
7. `export`:
    - export data pembelian ke CSV.

### Tahap E - Route Purchase

Route yang dipakai:

1. `purchases.index`
2. `purchases.create`
3. `purchases.prepare`
4. `purchases.member`
5. `purchases.process`
6. `purchases.receipt`
7. `purchases.receipt.pdf`
8. `purchases.export`

Akses:

1. Admin melihat semua transaksi.
2. User non-admin melihat transaksi miliknya.

### Tahap F - View Purchase

1. `create`:
    - pilih produk dan qty plus minus.
    - subtotal dan grand total real-time.
2. `member`:
    - alur non-member dan member.
    - kalkulasi redeem poin dan estimasi kembalian.
3. `receipt`:
    - preview PDF dalam iframe.
    - tombol download dan print.
4. `index`:
    - list transaksi.
    - detail item via modal.
5. `receipt-struk-pdf`:
    - template struk untuk generator PDF.

## 3) Istilah Penting Saat Pengujian Modul Purchase

1. DB transaction
   Definisi: kumpulan query yang sukses atau gagal bersama.

2. Lock for update
   Definisi: penguncian row saat transaksi berjalan agar stok/poin tidak bentrok.

3. Session checkout
   Definisi: penyimpanan sementara item belanja sebelum pembayaran diproses.

4. Authorization access
   Definisi: pembatasan akses data transaksi berdasarkan user login.

5. CSV export
   Definisi: unduh data rekap dalam format spreadsheet.

## 4) Skenario Uji Wajib

1. Qty lebih dari stok harus ditolak di tahap prepare.
2. Checkout kosong harus ditolak.
3. Non-member bisa transaksi tanpa poin.
4. Member valid bisa pakai poin sesuai batas.
5. Member tidak ditemukan harus muncul error.
6. Uang bayar kurang dari total harus ditolak.
7. Setelah transaksi sukses, stok produk berkurang sesuai qty.
8. Poin member berkurang dan bertambah sesuai aturan.
9. Struk PDF bisa preview, print, dan download.
10. Export CSV bisa diunduh.

## 5) Output Akhir Md 5

Struktur Purchase selesai total, termasuk alur transaksi end-to-end dan dokumen struk.
