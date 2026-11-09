# Push GitHub tanpa aset runtime - 10 November 2026

.gitignore mengecualikan aset QR hasil generate di assets/qrcode/, upload runtime, dump SQL baru di db/ dan backup database. File yang sudah tracked sebelumnya tidak dihapus atau ditulis ulang oleh aturan ignore. Kode, migration SQL terkurasi, tests dan dokumentasi disertakan pada rilis fitur CSV Zahir/QR.

Perubahan lokal application/config/database.php tidak disertakan karena memuat konfigurasi berkredensial. Dump database baru dan gambar QR tetap berada di komputer pengguna. Tidak menghapus file lokal, membersihkan histori Git, mengubah database atau menjalankan deploy ketika melakukan push source.

Instruksi lanjutan pengguna mengizinkan dump db/kiucoid_karismaerp(1).sql ikut versioning. File tersebut dikecualikan secara eksplisit dari ignore dump umum dan dipush terpisah. Gambar QR tetap tidak disertakan; application/config/database.php tetap lokal. Tidak ada deploy atau eksekusi SQL pada langkah push artefak ini.
