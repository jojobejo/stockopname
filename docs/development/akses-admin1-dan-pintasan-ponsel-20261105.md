# Akses admin1 dan pintasan ponsel — 5 November 2026

## Perubahan aplikasi

- Sidebar `Master User` kini tampil untuk `admin` dan `admin1`. `admin1` hanya melihat tautan `User Management` dalam kelompok tersebut; tautan Jobdesk, Akses Level, dan Menu tetap khusus `admin`. Controller User Management memeriksa username yang sama pada setiap endpoint agar akses langsung ke URL tetap sesuai.
- URL dasar aplikasi dan alamat awal pintasan disetel ke `https://10.10.10.26/stockopname/`. Semua tautan yang dibuat melalui `base_url()` memakai alamat ini.
- Manifest PWA, ikon 192/512 px, dan service worker tersedia pada akar aplikasi. Mode `standalone` membuka pintasan seperti aplikasi, tanpa bilah alamat browser apabila browser mendukungnya. Service worker memakai jaringan langsung dan tidak menyimpan halaman login atau data stok untuk penggunaan offline.
- Tombol `Pasang aplikasi` tersedia pada login, navbar utama, dan header Mobile ERP. Browser yang mendukung prompt instalasi akan menampilkannya; browser lain menampilkan petunjuk menu pemasangan.

## Cara penggunaan

1. Pastikan ponsel terhubung ke jaringan yang dapat membuka `https://10.10.10.26/stockopname/` dan sertifikat HTTPS dipercaya oleh ponsel.
2. Buka alamat itu di browser ponsel. Ketuk **Pasang pintasan di ponsel** pada halaman login atau **Pasang aplikasi** setelah masuk.
3. Di Android, ikuti dialog instalasi; bila dialog tidak tersedia, buka menu browser dan pilih **Instal aplikasi** atau **Tambahkan ke layar utama**. Di iPhone, gunakan menu **Bagikan → Tambahkan ke Layar Utama**.
4. Buka ikon **Stock Opname** dari layar utama, lalu login seperti biasa. Koneksi ke server tetap diperlukan.
5. Login sebagai `admin1`, buka sidebar **Master User → User Management** untuk mengelola user.

## Verifikasi dan batasan

- PHP lint dan pemeriksaan sintaks JavaScript lulus. Manifest, service worker, dan ikon mengembalikan HTTP 200 dari alamat HTTPS yang diminta.
- Akun `admin1` ditemukan aktif di database lokal dengan jobdesk `ADMIN` dan level 1. Tabel izin `tb_menu` dan `tb_akses_menu` tidak ada di database lokal, sehingga akses modul ini dijaga dengan daftar username pada controller dan sidebar; guard izin lama tetap dipanggil.
- Instalasi langsung di Android/iPhone dan alur login `admin1` belum diuji pada perangkat fisik. Sertifikat HTTPS perlu dipercaya pada tiap perangkat agar fitur instalasi PWA tersedia.
