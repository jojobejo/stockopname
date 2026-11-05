# Data uji opname dan diagnosis instalasi — 5 November 2026

## Skenario uji aplikasi

Data dummy tersedia di **Data Master Opname** untuk kode `AALUF01` dan `AAMOL01`. `AALUF01` menguji perhitungan pending 1 box + 7 pcs: qty master 210, pending 49, qty sistem 259. `AAMOL01` menguji barang tanpa pending: qty master dan qty sistem sama-sama 315. Nomor lot kedua barang `-`. Belum ada hasil input fisik pada `stockopname_opname`, sehingga uji selisih fisik tetap memerlukan input dari pengguna.

Tidak ada perubahan kode aplikasi pada seed ini. Script penyiapan data, snapshot, dan rollback dicatat di dokumentasi database terpisah.

## Hasil percobaan pemasangan langsung ke ponsel

- Ponsel belum dapat dipasang dari komputer ini: `adb devices -l` tidak menampilkan perangkat, dan inventaris antarmuka hanya menampilkan browser Chrome desktop. Pemeriksaan perangkat portabel Windows juga tidak menemukan ponsel aktif. Ini adalah kendala akses perangkat dari lingkungan kerja, bukan error browser ponsel yang teramati.
- Endpoint HTTPS `https://10.10.10.26/stockopname/`, manifest, service worker, dan kedua ikon masing-masing mengembalikan HTTP 200 dengan tipe konten yang sesuai. Sertifikat server mencantumkan IP `10.10.10.26` pada SAN dan diterbitkan oleh `KarismaERP Local Development CA 2026`.
- Belum ada akses ke ponsel atau browser ponsel untuk memeriksa apakah CA internal dipercaya pada perangkat itu, apakah prompt instalasi muncul, atau apakah pintasan benar-benar tercipta. Karena itu penyebab khusus di sisi ponsel/browser tidak dapat dinyatakan sebagai fakta.
- PWA dipasang melalui tindakan browser pada perangkat tujuan; berkas aplikasi `.apk` atau paket unduhan langsung tidak dibuat oleh implementasi PWA ini.
