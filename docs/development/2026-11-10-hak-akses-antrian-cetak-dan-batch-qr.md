# Dokumentasi Teknis Development Aplikasi

Tanggal: 10 November 2026  
Topik: Hak Akses, Tampilan Dashboard, Pengelolaan QR Code, dan Antrian Cetak

---

## 1. Login dan Hak Akses

### A. Penonaktifan Tombol "Pasang Aplikasi di Komputer"
- Tombol dan menu pemasangan aplikasi disembunyikan sementara untuk seluruh pengguna.
- File terdampak:
  - `application/views/partial/main/sidebar.php`: Blok navigasi pemasangan dibungkus kondisi `if (false)`.
  - `application/views/partial/mobile/sidebar.php`: Tombol pemasangan dibungkus kondisi `if (false)`.
  - `assets/js/install-app.js`: Fungsi `updateInstallControls()` memastikan semua elemen selector `[data-install-app]` diatur `hidden = true` dan `style.display = 'none'`.

### B. Pengalihan Otomatis Setelah Login
- Pengguna dengan username `superadmin`, `admin`, dan `admin1` (atau jobdesk `SUPERADMIN`) setelah berhasil login langsung diarahkan ke dashboard Stock Opname pada rute `admin/stockopname`.
- File terdampak:
  - `application/controllers/Auth.php`: Pengecekan username dan jobdesk diletakkan pada prioritas utama sebelum redirect role lainnya.

### C. Pembatasan Menu Admin & User Management
- Modul User Management, Jobdesk, Akses Level, dan Menu hanya diizinkan untuk akun **Superadmin**.
- Akun `admin` dan `admin1` tidak lagi melihat menu tersebut di sidebar.
- Akses langsung via URL/endpoint ke controller `master/C_Usermanagement`, `master/C_Jobdesk`, `master/C_Akseslevel`, `master/C_Menu`, dan `user/Admin` ditolak secara ketat dengan status HTTP `403 Forbidden` (`Akses Ditolak`).

### D. Isolasi Tampilan Master Opname vs Master Barang
- Pada halaman `admin/stockopname/master_opname`:
  - Akun **Admin** dan **Admin1** hanya melihat tampilan **Data Master Barang** (katalog). Tab `Data Master Opname` disembunyikan.
  - Akses endpoint data master opname lokal (`list`, `detail`, `update`, `create`, `import`, `delete`, `qty-zero`, dan `print-sebagian`) dibentengi dengan fungsi `require_superadmin_master_opname()`. Akses langsung via HTTP/AJAX akan ditolak.
  - Akun **Superadmin** memiliki akses penuh ke kedua tab (`Data Master Opname` dan `Data Master Barang`).

---

## 2. Tampilan Dashboard dan Tabel Barang

### A. Margin Card Tab Data Master Opname
- Margin card dan panel pada tab Data Master Opname diatur menjadi `2px` pada kontainer `#data-master-opname`, menyesuaikan kerapatan dan estetika tampilan Data Master Barang.

### B. Penyederhanaan Kolom Data Master Barang
- Kolom **P**, **L**, **T**, dan **Dimensi** dihilangkan dari tabel Data Master Barang (`tableMasterBarangZahir`).
- Kolom yang aktif kini lebih ringkas dan fokus:
  1. Checkbox Pilih
  2. Kode Barang
  3. Nama Barang
  4. Expired Date
  5. Aksi QR
  6. Status QR

### C. Pembersihan Kolom Aksi QR dan Status QR
- Tombol **Riwayat** dihilangkan dari kolom Aksi QR.
- Keterangan teks **Data Sesuai** dan **File QR Tersedia** dihilangkan dari kolom Status QR, sehingga kolom hanya menampilkan badge indikator status yang bersih (seperti "QR siap" atau status error jika ada kendala).

---

## 3. Hapus dan Generate Ulang QR Code (Khusus Superadmin)

- Akun **Superadmin** diberikan akses khusus untuk:
  1. **Hapus QR Code**: Menghapus file PNG QR Code fisik dari server dan menghapus baris terkait di tabel `stockopname_catalog_qrcode`.
  2. **Generate Ulang QR Code**: Menghapus QR lama dan menerbitkan file QR baru.
- **Generate Ulang Batch (Maksimal 100 QR Code)**:
  - Endpoint: `admin/stockopname/master_barang/regenerate-all-qrcode`.
  - Ukuran batch maksimal 100 item.
  - Progres batch ditampilkan secara dinamis mulai dari `1/100`, `2/100`, hingga `100/100`.
  - Jika batch terakhir berisi sisa item (misal 35 item), progres menampilkan `1/35`, `2/35`, hingga `35/35`.

---

## 4. Antrian Cetak dan Jumlah Salinan Label QR

### A. Penyimpanan Terisolasi Per Akun
- Menggunakan model `M_Stockopname_print_queue` dengan acuan kolom `username`.
- Akun `superadmin`, `admin`, dan `admin1` masing-masing memiliki antrian cetak tersendiri tanpa bercampur satu sama lain.

### B. Pengaturan Jumlah Salinan (Copies)
- Pengguna dapat mengatur jumlah salinan per barang (default: 1 salinan).
- Contoh: Barang A diatur 3 salinan, Barang B diatur 1 salinan.
- Saat dicetak, sistem akan mereplikasi kartu Barang A sebanyak 3 lembar dan Barang B sebanyak 1 lembar pada lembar cetak A4.

### C. Tata Letak Antrian dan Preview (Rasio 50:50)
- Pada sisi kanan halaman Data Master Barang:
  - **Card Atas (50% tinggi)**: Card **Antrian Cetak** (daftar antrian, kontrol tambah/kurang salinan, hapus item, bersihkan, dan tombol Cetak Antrian).
  - **Card Bawah (50% tinggi)**: Card **Preview Cetak QRCODE** (judul diperbarui dari sebelumnya *Preview Cetak Kartu Stock*).
