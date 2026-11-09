# Database pada push GitHub - 10 November 2026

Tidak ada perubahan skema database (no schema change) atau eksekusi migration untuk proses push. SQL migration fitur disertakan sebagai source; dump/backup data baru dan perubahan konfigurasi koneksi berkredensial tetap lokal. Artefak backup migration yang sudah dibuat di luar web root tetap tersimpan di lokasi semula.

Pembaruan atas instruksi pengguna: dump db/kiucoid_karismaerp(1).sql ikut dipush sebagai artefak database. Pengecualian eksplisit ditambahkan ke .gitignore untuk file tersebut. Konfigurasi koneksi berkredensial, gambar QR/upload baru dan backup lain tetap lokal. Proses ini hanya versioning file, tidak mengimpor SQL atau mengubah database aplikasi/production.
