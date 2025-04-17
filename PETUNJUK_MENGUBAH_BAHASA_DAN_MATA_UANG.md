# Petunjuk Mengubah Bahasa dan Mata Uang di Aplikasi

## Langkah 1: Perubahan Bahasa ke Indonesia

File bahasa Indonesia sudah dibuat di folder `resources/lang/id/`. Untuk menerapkan bahasa Indonesia, konfigurasi di `config/app.php` sudah diperbarui dengan mengubah:

```php
'locale' => 'id',
'timezone' => 'Asia/Jakarta',
'faker_locale' => 'id_ID',
```

## Langkah 2: Menambahkan Mata Uang Rupiah

Untuk menambahkan mata uang Rupiah, ikuti langkah-langkah berikut:

1. Buka menu **Settings** -> **Currencies** di aplikasi
2. Klik tombol **Add Currency**
3. Isi dengan data berikut:
   - Currency Name: Rupiah
   - Currency Code: IDR
   - Symbol: Rp
   - Thousand Separator: .
   - Decimal Separator: ,
4. Simpan perubahan

## Langkah 3: Menerapkan Rupiah sebagai Mata Uang Default

1. Buka menu **Settings** -> **General Settings** di aplikasi
2. Di bagian **Currency Settings**, pilih **Rupiah (IDR)** sebagai mata uang default
3. Untuk posisi simbol mata uang, pilih **Prefix** (contoh: Rp 10.000)
4. Simpan perubahan

## Langkah 4: Terjemahkan Antarmuka Pengguna

Untuk menerjemahkan seluruh antarmuka aplikasi, kode-kode bahasa sudah disediakan di folder `resources/lang/id/`. Namun, beberapa teks mungkin masih dalam bahasa Inggris karena perlu diperbarui di file template.

### Catatan:

- Mungkin perlu me-restart aplikasi setelah melakukan perubahan pada konfigurasi.
- Jika ada masalah, pastikan cache sudah dihapus dengan menjalankan perintah berikut pada terminal:
  ```
  php artisan cache:clear
  php artisan config:clear
  php artisan view:clear
  ``` 