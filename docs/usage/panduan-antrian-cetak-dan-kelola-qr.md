# Panduan Penggunaan Fitur Baru: Antrian Cetak & Pengelolaan QR Code

Panduan operasional untuk Superadmin, Admin, dan Admin1 pada modul Stock Opname KARISMA ERP.

---

## 1. Alur Masuk (Login)
1. Buka halaman login aplikasi.
2. Masukkan username dan password Anda (`superadmin`, `admin`, atau `admin1`).
3. Sistem secara otomatis langsung mengarahkan Anda ke dashboard Stock Opname di alamat: `admin/stockopname`.

---

## 2. Navigasi Halaman Data Master Barang
1. Dari dashboard Stock Opname, pilih menu **Master Opname** atau klik tombol **Master Barang**.
2. **Bagi Admin dan Admin1**:
   - Anda langsung melihat halaman **Data Master Barang** (katalog barang).
   - Tab Data Master Opname lokal disembunyikan demi keamanan data.
3. **Bagi Superadmin**:
   - Tersedia dua tab di bagian atas: **Data Master Opname** dan **Data Master Barang**.
   - Tab Data Master Opname memiliki tampilan card rapi dengan margin 2px yang serasi.

---

## 3. Cara Menggunakan Antrian Cetak Label QR Code

Fitur Antrian Cetak memungkinkan Anda mengumpulkan barang-barang yang ingin dicetak dan mengatur jumlah lembar/label yang dibutuhkan untuk masing-masing barang. Antrian Anda tersimpan secara pribadi di akun Anda.

### A. Memasukkan Barang ke Antrian
1. Pada tabel Data Master Barang, centang kotak checkbox di samping kiri barang yang ingin dicetak.
2. Anda dapat mencentang beberapa barang sekaligus atau menggunakan centang di judul tabel untuk memilih seluruh barang di halaman aktif.
3. Klik tombol **Masukkan Antrian (<jumlah>)** di atas tabel.
4. Barang yang dipilih akan langsung masuk ke card **Antrian Cetak** di panel sebelah kanan.

### B. Mengatur Jumlah Salinan (Copies)
1. Pada card **Antrian Cetak** (panel kanan atas), temukan barang yang ingin disesuaikan salinannya.
2. Gunakan tombol **[ - ]** atau **[ + ]**, atau ketik langsung angka jumlah salinan pada kotak angka (misalnya diisi `3`).
3. Sistem otomatis menyimpan perubahan jumlah salinan ke server.
4. Total label yang akan dicetak akan tertera pada tombol **Cetak Antrian (<total>)**.

### C. Menghapus Barang dari Antrian
- **Hapus satu item**: Klik tombol tanda silang **[ × ]** merah pada baris barang terkait di card antrian.
- **Kosongkan antrian**: Klik tombol **Bersihkan** di bagian header card antrian untuk mengosongkan seluruh isi antrian Anda.

### D. Melihat Preview Label
- Klik salah satu baris barang di dalam card Antrian Cetak.
- Tampilan kartu label QR Code barang tersebut akan langsung muncul di card bawah bertajuk **Preview Cetak QRCODE**.

### E. Mencetak Seluruh Antrian
1. Klik tombol hijau **Cetak Antrian** pada header card Antrian Cetak.
2. Tab baru browser akan terbuka menampilkan lembaran cetak siap print (kertas A4 format grid kartu stock).
3. Hasil cetak akan menampilkan jumlah kartu sesuai salinan yang telah Anda atur (misal Barang A dicetak 3 label, Barang B dicetak 1 label).
4. Klik tombol **Cetak** pada toolbar atas atau tekan kombinasi tombol `Ctrl + P` untuk mencetak ke printer label/kertas.

---

## 4. Pengelolaan QR Code (Khusus Superadmin)

Akun Superadmin memiliki akses administratif tambahan untuk mengelola siklus hidup QR Code barang:

### A. Menghapus QR Code Barang
1. Pada kolom **Aksi QR** di tabel Data Master Barang, barang yang sudah memiliki QR Code memiliki tombol **Hapus QR** (merah outline).
2. Klik tombol **Hapus QR** dan konfirmasi tindakan Anda.
3. File QR fisik dan status penerbitan QR pada sistem akan dihapus.

### B. Melakukan Generate Ulang Satuan
1. Pada kolom **Aksi QR**, klik tombol **Generate Ulang** (kuning outline).
2. Sistem akan menghapus QR lama dan otomatis menerbitkan file QR Code baru yang sah.

### C. Melakukan Generate Ulang Batch (Semua QR)
1. Di toolbar atas tabel Data Master Barang, klik tombol **Generate Ulang Semua QR**.
2. Konfirmasikan proses pada dialog yang muncul.
3. Sistem akan memproses generate ulang dalam batch maksimal 100 QR Code per tahapan.
4. Perhatikan indikator progres di layar:
   - Untuk batch berisi 100 item, progres akan berjalan mulai dari `1/100`, `2/100`, ..., hingga `100/100`.
   - Untuk batch terakhir yang berukuran lebih kecil, progres akan menyesuaikan jumlah aktual batch tersebut (misal `1/35`, `2/35`, ..., `35/35`).
5. Setelah seluruh proses rampung, notifikasi ringkasan sukses akan ditampilkan.
