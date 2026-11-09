# Impor CSV Zahir langsung

1. Buka card **Impor CSV** pada katalog.
2. Pilih CSV. Nama file dan keterangan **siap diimpor** tampil pada picker.
3. Klik **Impor sekarang**. Tidak ada tahap preview atau konfirmasi tambahan.
4. Tunggu proses selesai. Katalog dan status diperbarui otomatis; hasil impor tampil ringkas dalam modal.

Gunakan ekspor lengkap karena file mengganti katalog aktif. Maksimum5MiB/10000rows. P/L/T tetap diabaikan; hasil opname tidak berubah. Jika file tidak valid, tombol belum dapat dipakai. Jika data baris bermasalah, seluruh impor dibatalkan dan daftar perbaikannya ditampilkan. Pilih file yang sudah diperbaiki lalu impor kembali.

Jika koneksi terputus, muat ulang status sebelum mengulang. File identik aktif tidak menggandakan data. Pemulihan impor lama tersedia terpisah di halaman admin/stockopname/zahir-csv. UAT browser/progres upload nyata belum dilakukan pada pekerjaan ini.
