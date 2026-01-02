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

**Basic Information:**

- `brand`: Brand/merek produk
- `description`: Deskripsi produk
- `product_number`: Nomor produk/part number (contoh: 4Y7J1PA)

**Core Hardware Specifications:**

- `processor`: Spesifikasi processor
- `chipset`: Chipset (contoh: AMD Integrated SoC, Intel B760)
- `gpu`: Spesifikasi GPU/kartu grafis
- `ram`: Spesifikasi RAM
- `storage`: Spesifikasi penyimpanan

**Display & Input:**

- `display`: Spesifikasi layar
- `keyboard`: Spesifikasi keyboard

**Connectivity & Expansion:**

- `optical_drive`: Optical drive (contoh: DVD±RW, None)
- `wireless_connectivity`: Konektivitas wireless (contoh: Wi-Fi 6, Bluetooth 5.2)
- `expansion_slots`: Slot ekspansi (contoh: 1x M.2, 2x PCIe)
- `external_ports`: Port eksternal (contoh: 2x USB-C, 3x USB-A, HDMI)

**Physical Specifications:**

- `dimensions_width`: Lebar dalam CM (contoh: 30.66)
- `dimensions_depth`: Kedalaman dalam CM (contoh: 19.46)
- `dimensions_height`: Tinggi dalam CM (contoh: 1.65)
- `weight`: Berat (contoh: 1.3kg, 250g)

**Power:**

- `battery`: Spesifikasi baterai (contoh: Up to 13 hours)
- `power_supply_type`: Tipe power supply (contoh: 65W AC Adapter)

**Multimedia:**

- `webcam`: Spesifikasi webcam (contoh: 720p HD)
- `audio`: Spesifikasi audio (contoh: Bang & Olufsen, Dual Speakers)

**Software:**

- `operating_system`: Sistem operasi (contoh: Windows 11 Home)
- `software_included`: Software yang disertakan

**Commercial Information:**

- `warranty`: Garansi
- `condition`: Kondisi barang (new, excellent, good, fair, used-excellent, used-very-good, used-good)
- `extras`: Aksesoris tambahan
- `original_price`: Harga asli (untuk menampilkan diskon)
- `features`: Fitur-fitur produk

#### Contoh Data

**Contoh 1: Gaming Laptop (Lengkap dengan spesifikasi baru)**

```
name: ASUS TUF Gaming A15 Ryzen 7 RTX 4060
category: Laptop
brand: ASUS
price: 16500000
sku: DB-TUF-A15-R7-4060
stock: 3
processor: AMD Ryzen™ 7 7735HS (8-core, 16-thread, up to 4.75 GHz)
chipset: AMD Integrated SoC
gpu: NVIDIA® GeForce RTX™ 4060 8GB GDDR6
ram: 16 GB DDR5-4800 MHz
storage: 512 GB PCIe® 4.0 NVMe™ M.2 SSD
display: 15.6" FHD (1920x1080) 144Hz IPS
dimensions_width: 35.4
dimensions_depth: 25.1
dimensions_height: 2.24
weight: 2.2kg
wireless_connectivity: Wi-Fi 6 (802.11ax), Bluetooth® 5.3
external_ports: 1x USB-C 3.2, 3x USB-A 3.2, 1x HDMI 2.1, 1x Audio Jack, 1x RJ45
operating_system: Windows 11 Home
condition: new
```

**Contoh 2: Smartphone (Field relevan untuk smartphone)**

```
name: iPhone 15 Pro Max 256GB
category: Smartphone
brand: Apple
price: 21999000
sku: DB-IP15PM-256-NT
stock: 5
processor: Apple A17 Pro chip (6-core CPU, 6-core GPU)
ram: 8 GB
storage: 256 GB
display: 6.7" Super Retina XDR OLED, 120Hz ProMotion
dimensions_width: 7.65
dimensions_depth: 0.82
dimensions_height: 15.99
weight: 221g
wireless_connectivity: 5G, Wi-Fi 6E, Bluetooth 5.3, NFC
operating_system: iOS 17
condition: new
```

**Contoh 3: Accessories (Field minimal)**

```
name: TP-Link USB 3.0 Type-C Card Reader UA430C
category: Aksesori Gaming
brand: TP-Link
price: 350000
sku: DB-TPLINK-UA430C
stock: 10
product_number: UA430C
dimensions_width: 8
dimensions_depth: 3
dimensions_height: 1.2
weight: 45g
condition: new
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
10. **Field Relevance by Product Type**:
    - **Laptops**: Isi semua hardware fields (processor, chipset, gpu, ram, storage, display, keyboard)
    - **Smartphones**: Skip chipset (processor sudah cukup), fokus ke connectivity & multimedia
    - **Accessories**: Field minimal (dimensions, weight, product_number biasanya cukup)
    - **Custom Fields**: Untuk accessories yang unik, field specifications bisa berisi data custom
11. **Dimensions Format**:
    - Selalu gunakan **CM** untuk dimensions (width, depth, height)
    - Contoh: `30.66` bukan `30.66cm` atau `12.07 inch`
12. **Weight Format**:
    - Gunakan **kg** untuk laptop/desktop (contoh: `2.2kg`)
    - Gunakan **g** untuk smartphone/accessories (contoh: `221g`)
13. **Condition Values**:
    - Gunakan lowercase: `new`, `used-excellent`, `used-very-good`, `used-good`
    - **Jangan** gunakan: `New`, `Used-Excellent`, atau format lain

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

**Last Updated**: 2026-01-02
**Version**: 2.1.0

## 🆕 Changelog

### v2.1.0 (2026-01-02)

- ✅ Added 13 new specification fields for comprehensive product data
- ✅ Added support for `product_number` field
- ✅ Added hardware fields: `chipset`, `optical_drive`, `wireless_connectivity`
- ✅ Added connectivity fields: `expansion_slots`, `external_ports`
- ✅ Added physical dimension fields: `dimensions_width`, `dimensions_depth`, `dimensions_height`, `weight`
- ✅ Added power field: `power_supply_type`
- ✅ Added multimedia fields: `webcam`, `audio`
- ✅ Added software fields: `operating_system`, `software_included`
- ✅ Updated documentation with comprehensive examples for different product types
- ✅ Updated ProductsImport class validation rules

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
