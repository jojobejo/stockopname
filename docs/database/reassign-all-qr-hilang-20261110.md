# Database Reassign All QR Hilang - 10 November 2026

Tidak ada perubahan skema database (no schema change), migration atau modifikasi data operasional yang dijalankan oleh development fitur ini. Pengujian mutasi hanya di database uji terpisah.

Saat tombol dipakai admin, setiap QR missing yang berhasil dipulihkan memperbarui stockopname_catalog_qrcode.qrcode_file/generated_at, mempertahankan payload dan pasangan catalog_id+expired_date, memperbarui path qrcode master, dan menambah event stockopname_catalog_qr_issue melalui transaksi existing. Event operation=resign, reason='Reassign massal: file QR hilang', actor dan timestamp UTC. Tidak menambah record QR untuk item yang belum pernah diterbitkan. Tidak mengubah hasil opname, pending, approval, ukuran master, saldo transaksi atau Zahir.

Lock stockopname_master_barang_kiubr menyelaraskan penerbitan ulang dengan impor/master/QR lain. Missing diperiksa lagi dalam lock, sehingga file yang kembali tersedia tidak ditimpa. Failed generation/DB mempertahankan pointer lama dan membersihkan aset baru yang gagal dipublikasikan. Batch dapat berhasil sebagian; audit memberi jejak peritem dan rerun melewati yang telah tersedia. File/aset historis tidak dihapus. Fingerprint katalog hanya pengaman konsistensi antar-request, bukan credential atau tanda tangan file.

Sebelum pemakaian massal, cadangkan database dan direktori assets/qrcode/stockopname sesuai prosedur existing. Jangan restore seluruh master/hasil opname untuk membatalkan perbaikan aset. Pertahankan audit/payload/ID dan rekonsiliasi pointer berdasarkan riwayat bila diperlukan. Tidak ada rollback/destruksi tabel baru karena tidak ada perubahan skema.
