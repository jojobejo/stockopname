# Penyederhanaan teks katalog dan Master Opname - 10 November 2026

Sesuai permintaan pengguna, banner sukses impor yang berisi nama file/cakupan/pelaku/usia tidak lagi ditampilkan melalui partial status bersama. Empty state dan error operasional tetap ditampilkan jika data belum ada atau sumber gagal dibaca. Setelah impor berhasil, banner empty state sebelumnya ikut dihapus saat status dimuat ulang.

Teks penjelasan grouping qty/nama/kode Q pada katalog, status sukses pembacaan CSV saat daftar/refresh, toast sukses refresh CSV, pemberitahuan preview QR beserta tautan tab, dan petunjuk format CSV Master Opname dihapus. Indikator proses/error serta pesan hasil aksi impor/generate/reassign tetap tersedia. Perubahan hanya presentasi, bukan aturan grouping, format upload, sumber CSV, QR atau transaksi.

Berkas: stockopname_zahir_status.php, stockopname_master_barang_catalog.php, stockopname_master_barang.php, serta penyesuaian ekspektasi test view existing. Lint PHP dan regresi view diperiksa; tidak menambahkan test baru untuk perubahan teks ini. UAT browser belum dilakukan.
