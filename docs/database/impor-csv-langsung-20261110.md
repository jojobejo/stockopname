# Database: impor CSV langsung - 10 November 2026

Tidak ada perubahan skema database (no schema change). Tidak ada migration atau perubahan data aplikasi/production yang dijalankan.

Alur baru memanggil validasi dan transaksi impor existing dalam satu request. Rencana perubahan/fingerprint tetap dihitung di server lalu diperiksa lagi saat commit dalam lock; error satu row atau konflik membatalkan seluruh aktivasi. Audit jalur baru memakai result validated sebelum committed/noop/rejected; tidak memakai result dry_run untuk impor langsung. Data source/batch/master/QR dan backup/rollback tetap mengikuti docs/database/zahir-csv-dan-resign-20261109.md.

Tidak menyimpan staging preview baru untuk operation=import. Upload memakai temporary upload PHP dan hanya disimpan ke referensi database setelah seluruh validasi lulus. Pemulihan histori tetap tindakan admin terpisah. Tidak mengubah hasil opname, pending, approval, saldo transaksi atau database Zahir. Pengujian mutasi hanya memakai database uji acak yang dibuang setelah pengujian.
