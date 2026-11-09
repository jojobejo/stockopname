# Push GitHub tanpa aset runtime - 10 November 2026

.gitignore mengecualikan aset QR hasil generate di assets/qrcode/, upload runtime, dump SQL baru di db/ dan backup database. File yang sudah tracked sebelumnya tidak dihapus atau ditulis ulang oleh aturan ignore. Kode, migration SQL terkurasi, tests dan dokumentasi disertakan pada rilis fitur CSV Zahir/QR.

Perubahan lokal application/config/database.php tidak disertakan karena memuat konfigurasi berkredensial. Dump database baru dan gambar QR tetap berada di komputer pengguna. Tidak menghapus file lokal, membersihkan histori Git, mengubah database atau menjalankan deploy ketika melakukan push source.
