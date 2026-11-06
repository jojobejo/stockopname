# Instalasi pintasan dari sidebar — 6 November 2026

## Perubahan aplikasi

- Tombol pemasangan dihapus dari navbar utama, header Mobile ERP, dan halaman login. Setelah login, tombol **Download pintasan aplikasi** tersedia pada sidebar utama dan sidebar Mobile ERP. Ikon menu pada header Mobile ERP membuka sidebar tersebut; filter cepat tetap berada di sana.
- JavaScript memeriksa jenis browser saat halaman berjalan. Ponsel/tablet mendapat label **Download pintasan aplikasi** dan petunjuk pemasangan layar utama. PC/laptop mendapat label **Pasang aplikasi di komputer** dan petunjuk browser desktop. Keterangan perangkat tampil di sidebar. Deteksi memakai User-Agent Client Hints bila tersedia, lalu User-Agent dan ciri iPad mode desktop; hasilnya merupakan klasifikasi browser, bukan identifikasi perangkat keras yang pasti.
- Bila browser menyediakan dialog PWA, tombol memanggil dialog tersebut. Bila tidak, tombol menunjukkan langkah pemasangan dari menu browser. Saat aplikasi dibuka dalam mode `fullscreen` atau `standalone`, tombol disembunyikan.
- `start_url` dan `scope` manifest memakai path relatif terhadap manifest. Pintasan yang dipasang dari alamat aplikasi yang sedang dipakai akan membuka path `/stockopname/` pada origin yang sama. Manifest meminta mode `fullscreen`; browser yang tidak mendukungnya dapat memakai mode tampilan lain. Hasil akhir mengikuti dukungan browser dan sistem operasi. Data aplikasi tetap memerlukan koneksi server.
- Tombol download adalah alur pemasangan PWA/pintasan, bukan unduhan APK. Service worker tetap memakai jaringan langsung dan tidak menyimpan data stok untuk offline.

## Cara penggunaan

1. Masuk ke aplikasi melalui HTTPS yang sertifikatnya dipercaya perangkat.
2. Buka sidebar. Pada halaman utama, gunakan sidebar di kiri. Pada Mobile ERP, ketuk ikon menu di header untuk membuka sidebar.
3. Ketuk **Download pintasan aplikasi** di ponsel/tablet. Ikuti dialog instalasi bila muncul. Pada iPhone/iPad, gunakan menu **Bagikan → Tambahkan ke Layar Utama** bila dialog tidak muncul. Pada Android, gunakan menu browser **Instal aplikasi** atau **Tambahkan ke layar utama** bila dialog tidak muncul.
4. Buka ikon **Stock Opname** dari layar utama. Bila browser mendukung mode PWA fullscreen, situs terbuka memenuhi layar. Browser lain dapat menggunakan mode standalone tanpa bilah alamat. Login tetap diperlukan.
5. Di PC/laptop, gunakan **Pasang aplikasi di komputer** pada sidebar dan ikuti dialog atau petunjuk browser yang tersedia.

## Verifikasi dan batasan

- Pemeriksaan sintaks PHP, JavaScript, dan JSON manifest dilakukan pada berkas yang diubah. Simulasi runtime JavaScript memeriksa klasifikasi ponsel dan desktop, dialog pemasangan, serta penyembunyian tombol pada mode terpasang. Endpoint HTTPS manifest dan service worker mengembalikan HTTP 200; pemeriksaan ini mengabaikan validasi sertifikat klien.
- Instalasi aktual, tampilan layar penuh, dan kepercayaan sertifikat masih perlu diuji pada browser serta perangkat tujuan. Tombol tidak dapat memaksa browser memasang pintasan jika browser atau koneksi tidak memenuhi syarat PWA.
