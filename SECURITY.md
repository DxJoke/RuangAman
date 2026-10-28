# Kebijakan Keamanan & Privasi Data RuangAman 🔒

Keamanan informasi dan kerahasiaan data korban/pelapor merupakan pilar fundamental dalam pengembangan sistem **RuangAman**. Aplikasi ini dirancang sesuai prinsip kepatuhan terhadap amanat hukum:
- **UU No. 12 Tahun 2022** tentang Tindak Pidana Kekerasan Seksual (UU TPKS).
- **UU No. 27 Tahun 2022** tentang Pelindungan Data Pribadi (UU PDP).

---

## 1. Prinsip Keamanan & Privasi Data

1. **Pengumpulan Data Minimum (*Data Minimization*)**:
   - Pelapor **tidak diwajibkan** mencantumkan nama lengkap, nomor KTP/NIK, atau alamat rumah fisik saat pendaftaran akun.
   - Pendaftaran hanya memerlukan alias unik dan email aktif untuk autentikasi dan reset kata sandi.
   - Alamat email pengguna tidak pernah ditampilkan secara publik maupun kepada sesama pengguna biasa.

2. **Mode Anonim Per Laporan**:
   - Pada setiap pengajuan laporan, pelapor dapat memilih untuk mencentang opsi **Mode Anonim**.
   - Ketika Mode Anonim aktif, identitas profil pelapor disembunyikan bahkan dari konselor yang menangani kasus, sehingga konselor hanya melihat kode kasus, kronologi, bukti, dan alias acak sistem.

3. **Perlindungan Berkas Lampiran/Bukti (Private Storage)**:
   - File bukti yang diunggah pelapor **tidak disimpan di direktori publik** (`public/storage`).
   - Semua lampiran disimpan di direktori privat terisolasi (`storage/app/private/laporan/{laporan_id}/`) dan hanya dapat diunduh/dilihat melalui controller yang memvalidasi *Policy Authorization* (hanya pelapor pemilik laporan, konselor yang ditugaskan, dan Admin Kasus yang berhak).

4. **Kerahasiaan Kode Pelacakan Kasus**:
   - Pelacakan status menggunakan kode acak alfanumerik unik (misal: `RA-2026-X89KJ2`).
   - Halaman pelacakan publik tanpa login hanya menampilkan status saat ini (`Pending`, `Proses`, `Selesai`, `Ditolak`) dan timestamp pembaruan terakhir. Halaman ini **tidak pernah menampilkan** kronologi kasus, identitas pelapor, bukti lampiran, maupun catatan internal penanganan.

5. **Tombol Keluar Cepat (*Quick Exit*)**:
   - Diimplementasikan di setiap halaman antarmuka.
   - Ketika ditekan atau pintasan darurat keyboard diaktifkan (tombol `Esc` ganda), sistem seketika mengalihkan browser pengguna ke situs netral (seperti Google Search atau Berita Umum) dan menghapus jejak session tab sensitif.

6. **Pencatatan Audit Trail (Log Penanganan Kasus)**:
   - Setiap perubahan status, penugasan konselor, dan verifikasi admin dicatat dalam tabel `log_penanganan` yang terkunci (*immutable*) dari pengubahan atau penghapusan manual.

---

## 2. Pelaporan Kerentanan Keamanan (Security Vulnerability)

Jika Anda menemukan celah keamanan (*vulnerability*) atau potensi kebocoran data pada platform RuangAman:

1. **Jangan membuat issue publik di GitHub**.
2. Kirimkan laporan secara langsung kepada tim pengembang / Repository Administrator melalui:
   - **Email Keamanan**: `security@ruangaman.org` (atau hubungi Scrum Master Ghalib Safaraz Harimurti).
3. Cantumkan informasi berikut dalam laporan:
   - Deskripsi kerentanan dan potensi dampaknya.
   - Langkah-langkah reproduksi (*proof of concept*).
   - Saran mitigasi jika tersedia.
4. Tim kami akan merespons dalam waktu maksimal **48 jam** dan merilis perbaikan (*patch*) melalui branch khusus sebelum mempublikasikannya.

Terima kasih atas kontribusi Anda dalam menjaga keamanan ruang digital bagi para penyintas kekerasan.
