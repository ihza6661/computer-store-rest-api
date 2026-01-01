# Dokumentasi Fitur Import Produk

## 📋 Overview

Fitur import produk memungkinkan Anda untuk menambahkan atau memperbarui banyak produk sekaligus melalui file Excel (.xlsx, .xls, .csv).

## ✨ Fitur Utama

- **Preview & Validasi**: Preview data sebelum import dengan validasi real-time
- **Duplicate Detection**: Deteksi duplikat SKU dalam file dan database secara otomatis
- **Update Mode**: Opsi untuk memperbarui produk existing dengan SKU yang sama
- **Background Processing**: Import dijalankan di background menggunakan queue jobs
- **Error Reporting**: Detail error per baris dengan link ke produk existing
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
2. **(Opsional)** Centang **"Update existing products with same SKU"** jika ingin memperbarui produk existing
3. Klik **"Preview Data"** untuk validasi
4. Review hasil validasi:
    - **Action Column**: Menunjukkan apakah row akan **Create** (buat baru) atau **Update** (perbarui existing)
    - ✅ **Valid**: Baris siap diimport
    - ❌ **Error**: Baris memiliki kesalahan (lihat kolom Message)
    - 🔗 **Link**: Jika SKU sudah ada, klik "View existing product" untuk melihat produk yang akan di-update

### 5. Confirm Import

Jika semua data valid (tidak ada error):

1. Klik **"Confirm Import"**
2. Tunggu proses import selesai (berjalan di background)
3. Lihat summary hasil import

## ⚠️ Penting: Aturan Validasi

### Duplicate Detection

**Dalam File:**

- Jika SKU yang sama muncul lebih dari sekali dalam file yang sama, **seluruh import akan ditolak**
- Sistem akan menampilkan semua baris dengan SKU duplikat
- Fix: Pastikan setiap SKU unik dalam file

**Dalam Database:**

- Jika "Update existing products" **tidak dicentang**:
    - Produk dengan SKU existing akan di-skip
    - Error message: "SKU 'XXX' already exists in database"
    - Link ke produk existing ditampilkan
- Jika "Update existing products" **dicentang**:
    - Produk dengan SKU existing akan di-update
    - Hanya field tertentu yang diperbarui (lihat Update Mode)

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

- SKU **HARUS unique** dalam file yang diupload
- Jika duplikat SKU ditemukan dalam file, seluruh import ditolak
- Untuk produk existing di database:
    - Default: Skip dengan error message
    - Dengan "Update mode": Update produk existing

### Update Mode (Optional)

Ketika checkbox **"Update existing products with same SKU"** dicentang:

**Field yang diupdate:**

- `price` - Harga produk
- `stock` - Jumlah stok
- `description` - Deskripsi produk

**Field yang TIDAK diupdate (tetap seperti existing):**

- `sku` - SKU tidak bisa diubah
- `name` - Nama produk tetap
- `category_id` - Kategori tetap
- `brand` - Brand tetap
- `images` - Gambar tidak terpengaruh
- `specifications` - Spesifikasi detail tetap
- Relasi lainnya

**Use Case:**

- Update harga bulk untuk sale/promo
- Update stok setelah stock opname
- Update deskripsi produk secara massal

**Warning:**

- Update mode tidak bisa undo secara otomatis
- Backup data sebelum update massal
- Preview dulu untuk memastikan produk yang benar akan diupdate

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

### "Duplicate SKU 'XXX' found in file at rows: 2, 5, 8"

**Penyebab**: SKU yang sama muncul di beberapa baris dalam file yang diupload

**Solusi**:

- Edit file Excel dan pastikan setiap SKU unik
- Tentukan mana data yang benar jika ada duplikat
- Hapus atau ubah SKU yang duplikat
- Upload ulang file yang sudah diperbaiki

### "SKU 'XXX' already exists in database"

**Penyebab**: Produk dengan SKU tersebut sudah ada di database

**Solusi (Pilih salah satu):**

1. **Jika ingin membuat produk baru:**
    - Ubah SKU di file Excel menjadi SKU baru yang unik
    - Upload ulang

2. **Jika ingin update produk existing:**
    - Centang checkbox "Update existing products with same SKU"
    - Preview ulang untuk memastikan
    - Confirm import

3. **Jika ingin melihat produk existing:**
    - Klik link "View existing product" di error message
    - Review produk existing
    - Putuskan apakah mau update atau buat baru dengan SKU berbeda

### "Category not found"

**Penyebab**: Nama kategori tidak ada di database atau typo

**Solusi**:

- Cek spelling kategori di Excel
- Gunakan kategori dari list yang tersedia
- Buat kategori baru terlebih dahulu jika diperlukan

### "Import failed: SQLSTATE[23000]..." (Long SQL Error)

### "Import failed: SQLSTATE[23000]..." (Long SQL Error)

**Penyebab**: Database constraint violation atau error teknis

**Solusi**:

- Klik "Show technical details" untuk melihat error lengkap
- Error sekarang ditampilkan dengan format yang lebih readable
- Jika masih bingung, screenshot dan contact developer
- Kemungkinan besar terkait duplicate key atau foreign key constraint

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
4. **Gunakan Update Mode dengan Hati-hati**: Backup data dulu sebelum bulk update
5. **Check Duplicate dalam File**: Pastikan tidak ada SKU duplikat dalam file Excel Anda
6. **Backup Data**: Backup database sebelum import besar
7. **Batch Import**: Untuk data besar (>500 produk), pecah jadi beberapa file
8. **SKU Konsisten**: Gunakan format SKU yang konsisten (contoh: BRAND-MODEL-VARIANT)
9. **Link ke Existing Product**: Gunakan link "View existing product" untuk review sebelum update

## 📝 Limitasi

- Maximum file size: **10 MB**
- Format support: **.xlsx, .xls, .csv**
- Recommended batch size: **≤ 500 produk per import**
- **Tidak support image upload** (harus manual setelah import)
- **Tidak boleh ada duplikat SKU dalam file** (akan reject seluruh import)
- **Update mode hanya update field tertentu** (price, stock, description)

## 🎯 Example Use Cases

### Use Case 1: Import 10 Laptop Baru

1. Download template
2. Isi 10 baris dengan data laptop
3. Pastikan kategori = "Laptop"
4. SKU unique untuk tiap laptop
5. **Jangan centang update checkbox** (karena produk baru)
6. Upload, preview, confirm

### Use Case 2: Import dari Supplier

1. Dapatkan price list dari supplier (Excel)
2. Format ulang sesuai template kami
3. Map kategori supplier ke kategori kami
4. Generate SKU untuk tiap produk
5. **Jangan centang update checkbox**
6. Upload, preview, confirm

### Use Case 3: Bulk Price Update

1. Export produk existing (atau buat list SKU + price baru)
2. Format sesuai template dengan SKU existing
3. Update kolom `price` dan `stock` sesuai kebutuhan
4. **PENTING: Centang "Update existing products with same SKU"**
5. Upload, preview (pastikan semua menunjukkan "Update")
6. Confirm import

### Use Case 4: Stock Opname Update

1. Buat file Excel dengan SKU dan stock baru hasil stock opname
2. Isi kolom `price` dengan harga existing (atau harga baru jika ada perubahan)
3. **Centang "Update existing products with same SKU"**
4. Preview dulu untuk memastikan yang diupdate benar
5. Confirm import

### Use Case 5: Partial Import (Ada Duplikat dalam File)

**TIDAK BISA** - Jika ada duplikat SKU dalam file yang sama, seluruh import akan ditolak. Fix duplikat dulu sebelum upload ulang.

## 📧 Support

Jika mengalami masalah:

1. Check error message di preview/import result
2. Review dokumentasi ini
3. Check Laravel logs: `storage/logs/laravel.log`
4. Contact admin/developer

---

**Last Updated**: 2026-01-01
**Version**: 2.0.0

## 🆕 Changelog

### v2.0.0 (2026-01-01)

- ✅ Added duplicate detection within uploaded file
- ✅ Added bulk duplicate check against database
- ✅ Added optional update mode for existing products
- ✅ Improved error messages (user-friendly + technical details)
- ✅ Added links to existing products in error messages
- ✅ Added "Action" column (Create/Update) in preview

### v1.0.0 (Initial Release)

- Basic import functionality
- Preview validation
- Background job processing
- Template download
