# Sistem Informasi Administrasi Desa Adat Jimbaran

Ini adalah sistem informasi berbasis web yang dibangun dengan Laravel untuk mengelola data kependudukan dan surat-menyurat di Desa Adat Jimbaran.

## Prasyarat

- PHP (versi 8.1 atau lebih tinggi)
- Composer
- Node.js & NPM
- MySQL

## Panduan Instalasi

1.  **Clone Repository**
    ```bash
    git clone [https://github.com/gmsaw/BindesJimbaran.git](https://github.com/gmsaw/BindesJimbaran.git)
    cd BindesJimbaran
    ```

2.  **Instal Dependensi**
    ```bash
    composer install
    npm install
    ```

3.  **Konfigurasi Lingkungan**
  - Salin file `.env.example` menjadi `.env`.
    ```bash
    cp .env.example .env
    ```
  - Buka file `.env` dan sesuaikan konfigurasi database (`DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD`, dll.) agar cocok dengan pengaturan MySQL lokal Anda.

4.  **Setup Database**
  - Di MySQL, buat dua database kosong: `db_laravel` dan `db_kependudukan`.
  - Cari Folder root->database->#PURE
  - Jalankan atau import STRUCTURE file terlebih dahulu pada MySQL
  - Kemudin lanjutkan dengan menjalanan atau import DATA file pada MySQL
  - Jalankan perintah untuk membuat token:
    ```bash
    php artisan key:generate
    ```

5.  **Langkah Final**
  - Buat symbolic link untuk storage:
    ```bash
    php artisan storage:link
    ```
  - Compile aset front-end:
    ```bash
    npm run dev
    ```
  - Jalankan server pengembangan:
    ```bash
    php artisan serve
    ```

6.  Aplikasi sekarang bisa diakses di `http://127.0.0.1:8000`
