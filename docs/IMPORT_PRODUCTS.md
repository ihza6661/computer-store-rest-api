# Dokumentasi Fitur Import Produk

## 📋 Overview

Fitur import produk memungkinkan Anda untuk menambahkan banyak produk sekaligus melalui file Excel (.xlsx, .xls, .csv).

## ✨ Fitur Utama

- **Preview & Validasi**: Preview data sebelum import dengan validasi real-time
- **Background Processing**: Import dijalankan di background menggunakan queue jobs
- **Error Reporting**: Detail error per baris untuk memudahkan debugging
- **Template Excel**: Download template dengan format yang benar dan contoh data
- **Progress Tracking**: Real-time progress update saat import berjalan

## 🚀 Cara Menggunakan

### 1. Persiapan

1. Pastikan kategori sudah ada di database
2. Pastikan queue worker berjalan: `php artisan queue:work`

### 2. Download Template

1. Buka halaman **Admin > Products**
2. Klik tombol **"Import Products"**
3. Klik **"Download Template"** untuk mendapatkan file Excel template

### 3. Isi Data Produk

Buka template Excel dan isi data produk dengan kolom berikut:

#### Kolom Wajib (Required)

- `name`: Nama produk
- `category`: Nama kategori (harus sudah ada di database)
- `price`: Harga produk (angka)
- `sku`: Kode SKU (harus unique)
- `stock`: Jumlah stok (angka)

#### Kolom Opsional

- `brand`: Brand/merek produk
- `description`: Deskripsi produk
- `processor`: Spesifikasi processor
- `gpu`: Spesifikasi GPU/kartu grafis
- `ram`: Spesifikasi RAM
- `storage`: Spesifikasi penyimpanan
- `display`: Spesifikasi layar
- `keyboard`: Spesifikasi keyboard
- `battery`: Spesifikasi baterai
- `warranty`: Garansi
- `condition`: Kondisi barang (new, excellent, good, fair, used-excellent, used-very-good, used-good)
- `extras`: Aksesoris tambahan
- `original_price`: Harga asli (untuk menampilkan diskon)
- `features`: Fitur-fitur produk

#### Contoh Data

```
| name                    | category | brand | price    | sku           | stock |
|------------------------|----------|-------|----------|---------------|-------|
| MacBook Pro M3 16"     | Laptop   | Apple | 35000000 | MBP-M3-16-001| 5     |
| Dell XPS 15 9530       | Laptop   | Dell  | 28000000 | DELL-XPS15-001| 3   |
```

### 4. Upload dan Preview

1. Klik **"Choose File"** dan pilih file Excel Anda
2. Klik **"Preview Data"** untuk validasi
3. Review hasil validasi:
    - ✅ **Valid**: Baris siap diimport
    - ❌ **Error**: Baris memiliki kesalahan (lihat kolom Message)

### 5. Confirm Import

Jika semua data valid (tidak ada error):

1. Klik **"Confirm Import"**
2. Tunggu proses import selesai (berjalan di background)
3. Lihat summary hasil import

## ⚠️ Penting: Aturan Validasi

### Category

- Kategori **HARUS sudah ada** di database
- Jika kategori tidak ditemukan, baris akan di-skip
- Gunakan nama kategori yang **exact match** (case-insensitive)

**Kategori yang Tersedia:**

- Smartphone
- Laptop
- Komputer Desktop
- Monitor & Display
- Komponen Komputer
- Keyboard & Mouse
- Audio & Headset
- Perangkat Jaringan
- Penyimpanan Data
- Software & Lisensi
- Aksesori Gaming

### SKU (Stock Keeping Unit)

- SKU **HARUS unique**
- Jika SKU sudah ada di database, baris akan di-skip
- Tidak ada auto-update untuk SKU existing

### Image Upload

- Import Excel **TIDAK support upload gambar**
- Semua produk yang diimport akan memiliki image placeholder kosong
- Gambar harus diupload manual setelah import melalui fitur "Edit Product"

## 🔄 Background Processing

Import dijalankan secara asynchronous menggunakan Laravel Queue:

1. File divalidasi terlebih dahulu (preview)
2. Jika valid, job dimasukkan ke queue
3. Worker memproses import di background
4. Progress ditampilkan secara real-time
5. Hasil ditampilkan setelah selesai

**Pastikan queue worker berjalan:**

```bash
php artisan queue:work
```

Atau jika menggunakan dev environment:

```bash
composer run dev
```

## 📊 Import Summary

Setelah import selesai, Anda akan melihat summary:

- **Total**: Total baris yang diproses
- **Success**: Jumlah produk berhasil diimport
- **Failed**: Jumlah baris yang gagal (dengan detail error)

## 🐛 Troubleshooting

### "Category not found"

**Penyebab**: Nama kategori tidak ada di database atau typo

**Solusi**:

- Cek spelling kategori di Excel
- Gunakan kategori dari list yang tersedia
- Buat kategori baru terlebih dahulu jika diperlukan

### "SKU already exists"

**Penyebab**: SKU sudah digunakan produk lain

**Solusi**:

- Gunakan SKU yang unique
- Atau edit/hapus produk existing dengan SKU tersebut

### "Validation failed"

**Penyebab**: Data tidak sesuai format (contoh: price bukan angka)

**Solusi**:

- Pastikan price dan stock berisi angka
- Pastikan condition valid (new, excellent, good, dll)
- Cek format data sesuai template

### Import Stuck di "Processing"

**Penyebab**: Queue worker tidak berjalan

**Solusi**:

```bash
# Check queue status
php artisan queue:work --tries=1

# Atau restart dev environment
composer run dev
```

## 💡 Tips & Best Practices

1. **Mulai dengan Sample Kecil**: Test dengan 2-3 baris dulu sebelum import ratusan produk
2. **Gunakan Template**: Selalu gunakan template yang sudah disediakan
3. **Preview Dulu**: Selalu preview sebelum confirm import
4. **Backup Data**: Backup database sebelum import besar
5. **Batch Import**: Untuk data besar (>500 produk), pecah jadi beberapa file
6. **SKU Konsisten**: Gunakan format SKU yang konsisten (contoh: BRAND-MODEL-VARIANT)

## 📝 Limitasi

- Maximum file size: **10 MB**
- Format support: **.xlsx, .xls, .csv**
- Recommended batch size: **≤ 500 produk per import**
- **Tidak support image upload** (harus manual setelah import)
- **Tidak support update existing product** (hanya insert baru)

## 🎯 Example Use Cases

### Use Case 1: Import 10 Laptop Baru

1. Download template
2. Isi 10 baris dengan data laptop
3. Pastikan kategori = "Laptop"
4. SKU unique untuk tiap laptop
5. Upload, preview, confirm

### Use Case 2: Import dari Supplier

1. Dapatkan price list dari supplier (Excel)
2. Format ulang sesuai template kami
3. Map kategori supplier ke kategori kami
4. Generate SKU untuk tiap produk
5. Upload, preview, confirm

### Use Case 3: Bulk Stock Update

**TIDAK BISA** - Import hanya untuk produk baru. Untuk update stock existing, gunakan fitur edit manual.

## 📧 Support

Jika mengalami masalah:

1. Check error message di preview/import result
2. Review dokumentasi ini
3. Check Laravel logs: `storage/logs/laravel.log`
4. Contact admin/developer

---

**Last Updated**: 2026-01-01
**Version**: 1.0.0
