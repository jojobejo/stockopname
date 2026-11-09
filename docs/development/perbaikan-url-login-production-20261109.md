# Perbaikan URL login production

Tanggal: 2026-11-09

## Penyebab dan perubahan

`application/config/config.php` sebelumnya selalu menambahkan `/stockopname/` ke URL dasar. Jika aplikasi production berada di root domain, form login mengirim POST ke `/stockopname/process` padahal route aplikasi yang benar adalah `/process`.

- URL dasar sekarang mengikuti direktori entry point `index.php`: instalasi root memakai `/`, instalasi subfolder tetap memakai `/stockopname/` atau nama folder aktual.
- Environment variable `APP_BASE_URL` dapat menentukan URL publik secara eksplisit, khususnya pada reverse proxy atau terminasi HTTPS.
- Form login memakai `site_url('process')` agar mengikuti konfigurasi `index_page`.
- Default controller memakai `Auth`, sesuai nama file `Auth.php` pada filesystem Linux yang case-sensitive.
- Route `process` tetap menuju `Auth/process`; tujuan setelah autentikasi tetap mengikuti role pengguna.

## Deployment dan penggunaan

1. Deploy ketiga file aplikasi yang berubah: config, routes, dan view login.
2. Untuk production, set environment variable PHP `APP_BASE_URL` ke URL publik instalasi, misalnya `https://opname.example.com/` untuk root atau `https://example.com/stockopname/` untuk subfolder. Pastikan variable tersedia pada proses PHP web; reload service jika diperlukan.
3. Muat ulang halaman login agar action form memakai konfigurasi baru.
4. Periksa POST login: root harus menuju `/process`; subfolder harus menuju `/stockopname/process`. Jika `index_page` digunakan, URL menyertakan `index.php`.
5. Uji akun valid untuk memastikan halaman tujuan sesuai role, serta akun invalid untuk memastikan kembali ke login.
6. Pastikan rewrite web server mengarah ke `index.php` untuk URL tanpa index.php. Pada Apache, aktifkan mod_rewrite dan izinkan `.htaccess`. Jika instalasi memang berada di subfolder, `/stockopname/process` adalah URL yang benar; 404 yang masih terjadi perlu diperiksa pada rewrite dan file routes yang terdeploy.

## Validasi

Lint PHP dan simulasi konfigurasi URL root/subfolder dijalankan secara lokal. Login production dengan akun nyata belum diuji; akses production tidak tersedia pada pekerjaan ini.

## Rollback

Kembalikan ketiga file aplikasi ke versi sebelumnya dan hapus `APP_BASE_URL` bila baru ditambahkan untuk perubahan ini.
