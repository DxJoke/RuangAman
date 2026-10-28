# Kamus Data Basis Data RuangAman (26 Tabel Unik) 🗄️

Sesuai dengan **Subbab 4.3 (Tabel 4.3.1)** dan **Tabel 5.2.2** dokumen Proposal RuangAman, basis data dirancang menggunakan MySQL 8.x (InnoDB Engine) dengan total **26 tabel unik**.

---

## 1. Aturan Independensi & Kepemilikan Tabel (Module Independence)
Untuk memastikan enam pengembang dapat bekerja secara paralel tanpa saling memicu konflik merge atau dependensi kode yang memblokir:
1. **Satu tabel hanya dimiliki oleh satu PBI**.
2. Tidak ada PBI individu yang boleh mengubah struktur kolom atau menghapus data tabel milik PBI lain.
3. Keterkaitan antar-PBI hanya melalui *Foreign Key* ke tabel referensi yang disediakan melalui **Database Seeder**.
4. PBI Tim (Integrasi Bersama) hanya membaca seluruh tabel, dan satu-satunya mutasi lintas tabel dilakukan secara terisolasi melalui `CaseApprovalService` (menulis atomik ke `laporan_kasus`, `penanganan_kasus`, `log_penanganan`).

---

## 2. Rincian Kamus Data per Domain

### Domain 1: Pengguna, Akses & Notifikasi

#### 1. `users` (Pemilik: Fondasi Bersama - Seluruh Tim)
Menyimpan kredensial autentikasi pengguna sistem.
- `id`: BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY
- `role_id`: BIGINT UNSIGNED NOT NULL (FK ke `roles.id`)
- `nama_tampilan`: VARCHAR(100) NOT NULL (alias / nama panggilan)
- `email`: VARCHAR(150) UNIQUE NOT NULL (hanya untuk login & reset password, privat)
- `password`: VARCHAR(255) NOT NULL (password hash bcrypt)
- `status`: ENUM('aktif', 'nonaktif') DEFAULT 'aktif'
- `remember_token`: VARCHAR(100) NULL
- `created_at`, `updated_at`: TIMESTAMP NULL

#### 2. `roles` (Pemilik: Fondasi Bersama - Seluruh Tim)
Daftar peran sistem (Admin Kasus, Konselor, Pelapor).
- `id`: BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY
- `nama_role`: VARCHAR(50) UNIQUE NOT NULL
- `deskripsi`: VARCHAR(255) NULL
- `created_at`, `updated_at`: TIMESTAMP NULL

#### 3. `permissions` (Pemilik: Fondasi Bersama - Seluruh Tim)
Hak akses granular (contoh: `buat-laporan`, `kelola-artikel`, `verifikasi-status`).
- `id`: BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY
- `nama_permission`: VARCHAR(100) UNIQUE NOT NULL
- `deskripsi`: VARCHAR(255) NULL
- `created_at`, `updated_at`: TIMESTAMP NULL

#### 4. `role_permissions` (Pemilik: Fondasi Bersama - Seluruh Tim)
Tabel pivot many-to-many antara role dan permission.
- `role_id`: BIGINT UNSIGNED NOT NULL (FK ke `roles.id` ON DELETE CASCADE)
- `permission_id`: BIGINT UNSIGNED NOT NULL (FK ke `permissions.id` ON DELETE CASCADE)
- *PRIMARY KEY (`role_id`, `permission_id`)*

#### 5. `profil_pelapor` (Pemilik: PBI-17 - Fauzi Abdurrahman Musyaffa)
Menyimpan profil pelapor berbasis alias dan preferensi privasi bawaan.
- `id`: BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY
- `user_id`: BIGINT UNSIGNED UNIQUE NOT NULL (FK ke `users.id` ON DELETE CASCADE)
- `alias`: VARCHAR(100) NOT NULL
- `kontak_opsional`: VARCHAR(100) NULL (opsional, jika ingin dihubungi tim pendamping)
- `preferensi_anonim`: BOOLEAN DEFAULT TRUE (bawaan mode anonim saat buat laporan baru)
- `created_at`, `updated_at`: TIMESTAMP NULL

#### 6. `notifikasi` (Pemilik: PBI-18 - Fauzi Abdurrahman Musyaffa)
Pemberitahuan in-app untuk kejadian spesifik (bukan broadcast pengumuman).
- `id`: BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY
- `user_id`: BIGINT UNSIGNED NOT NULL (FK ke `users.id` ON DELETE CASCADE)
- `judul`: VARCHAR(150) NOT NULL
- `pesan`: TEXT NOT NULL
- `tipe`: VARCHAR(50) NOT NULL (contoh: `status_berubah`, `jadwal_konseling`, `kegiatan_diterima`)
- `read_at`: TIMESTAMP NULL
- `created_at`: TIMESTAMP NULL

---

### Domain 2: Pelaporan dan Penanganan Kasus

#### 7. `status_kasus` (Pemilik: PBI-04 - Glen Vian Agus Cahya Tarigan)
Master status laporan kasus (`Pending`, `Proses`, `Selesai`, `Ditolak`).
- `id`: BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY
- `nama_status`: VARCHAR(50) UNIQUE NOT NULL
- `deskripsi`: VARCHAR(255) NULL
- `created_at`, `updated_at`: TIMESTAMP NULL

#### 8. `laporan_kasus` (Pemilik: PBI-16 - Fauzi Abdurrahman Musyaffa)
Pusat pencatatan aduan kekerasan berbasis gender.
- `id`: BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY
- `user_id`: BIGINT UNSIGNED NOT NULL (FK ke `users.id`)
- `status_id`: BIGINT UNSIGNED NOT NULL (FK ke `status_kasus.id`)
- `kode_laporan`: VARCHAR(50) UNIQUE NOT NULL (token lacak unik acak sistem)
- `kategori_kekerasan`: VARCHAR(100) NOT NULL (opsi statis: Fisik, Psikis, Seksual, Ekonomi, KBGO)
- `lokasi_kejadian`: TEXT NOT NULL (teks bebas, bukan tabel master wilayah)
- `kronologi`: LONGTEXT NOT NULL
- `waktu_kejadian`: DATETIME NOT NULL
- `tingkat_urgensi`: ENUM('rendah', 'sedang', 'tinggi', 'darurat') DEFAULT 'sedang'
- `mode_anonim`: BOOLEAN DEFAULT TRUE (sembunyikan profil dari konselor)
- `created_at`, `updated_at`: TIMESTAMP NULL

#### 9. `lampiran_laporan` (Pemilik: PBI-16 - Fauzi Abdurrahman Musyaffa)
Berkas bukti laporan yang tersimpan di storage privat.
- `id`: BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY
- `laporan_id`: BIGINT UNSIGNED NOT NULL (FK ke `laporan_kasus.id` ON DELETE CASCADE)
- `nama_file`: VARCHAR(255) NOT NULL
- `path_privat`: VARCHAR(500) NOT NULL (lokasi di `storage/app/private/`)
- `tipe_file`: VARCHAR(100) NOT NULL (mimetype)
- `ukuran_file`: BIGINT UNSIGNED NOT NULL (dalam bytes)
- `created_at`: TIMESTAMP NULL

#### 10. `penanganan_kasus` (Pemilik: PBI-04 - Glen Vian Agus Cahya Tarigan)
Penugasan penanganan laporan kepada petugas/konselor.
- `id`: BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY
- `laporan_id`: BIGINT UNSIGNED UNIQUE NOT NULL (FK ke `laporan_kasus.id` ON DELETE CASCADE)
- `petugas_id`: BIGINT UNSIGNED NOT NULL (FK ke `users.id` - konselor/petugas pendamping)
- `tanggal_mulai`: DATE NOT NULL
- `tanggal_selesai`: DATE NULL
- `catatan`: TEXT NULL
- `created_at`, `updated_at`: TIMESTAMP NULL

#### 11. `log_penanganan` (Pemilik: PBI-06 - Glen Vian Agus Cahya Tarigan)
Audit trail log aktivitas penanganan (terkunci dari pengubahan/penghapusan).
- `id`: BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY
- `penanganan_id`: BIGINT UNSIGNED NOT NULL (FK ke `penanganan_kasus.id` ON DELETE CASCADE)
- `user_id`: BIGINT UNSIGNED NOT NULL (FK ke `users.id` - aktor pencatat log)
- `jenis_log`: ENUM('otomatis_status', 'manual_catatan', 'rujukan_eksternal') NOT NULL
- `aktivitas`: TEXT NOT NULL
- `waktu`: DATETIME NOT NULL
- `created_at`: TIMESTAMP NULL

---

### Domain 3: Layanan Konseling

#### 12. `konselor` (Pemilik: PBI-13 - Altaf Maulana)
Data profil tenaga konselor atau psikolog profesional terdaftar.
- `id`: BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY
- `user_id`: BIGINT UNSIGNED UNIQUE NOT NULL (FK ke `users.id` ON DELETE CASCADE)
- `nama`: VARCHAR(150) NOT NULL
- `spesialisasi`: VARCHAR(200) NOT NULL
- `bio`: TEXT NULL
- `status`: ENUM('aktif', 'cuti', 'nonaktif') DEFAULT 'aktif'
- `created_at`, `updated_at`: TIMESTAMP NULL

#### 13. `jadwal_konseling` (Pemilik: PBI-14 - Altaf Maulana)
Slot ketersediaan dan pemesanan janji konseling (online maupun offline).
- `id`: BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY
- `konselor_id`: BIGINT UNSIGNED NOT NULL (FK ke `konselor.id`)
- `user_id`: BIGINT UNSIGNED NULL (FK ke `users.id` - pelapor yang memesan slot)
- `tanggal`: DATE NOT NULL
- `waktu_mulai`: TIME NOT NULL
- `waktu_selesai`: TIME NOT NULL
- `metode`: ENUM('online', 'offline') NOT NULL
- `lokasi_atau_tautan`: VARCHAR(500) NULL (ruang tatap muka / link video conference)
- `status`: ENUM('tersedia', 'dipesan', 'selesai', 'dibatalkan') DEFAULT 'tersedia'
- `created_at`, `updated_at`: TIMESTAMP NULL

#### 14. `sesi_konseling` (Pemilik: PBI-15 - Altaf Maulana)
Pencatatan rekap pelaksanaan sesi dan catatan hasil konseling.
- `id`: BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY
- `jadwal_id`: BIGINT UNSIGNED UNIQUE NOT NULL (FK ke `jadwal_konseling.id` ON DELETE CASCADE)
- `catatan`: TEXT NOT NULL
- `hasil`: TEXT NOT NULL (rekomendasi tindak lanjut pemulihan)
- `created_at`, `updated_at`: TIMESTAMP NULL

---

### Domain 4: Edukasi, Informasi Publik & Publikasi

#### 15. `halaman_informasi` (Pemilik: PBI-01 - Ghalib Safaraz Harimurti)
Konten halaman statis umum (Tentang Kami, Visi Misi, Panduan, Kebijakan Privasi).
- `id`: BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY
- `judul`: VARCHAR(200) NOT NULL
- `slug`: VARCHAR(200) UNIQUE NOT NULL
- `isi`: LONGTEXT NOT NULL
- `status_publikasi`: ENUM('draft', 'published') DEFAULT 'draft'
- `created_at`, `updated_at`: TIMESTAMP NULL

#### 16. `glosarium_kbg` (Pemilik: PBI-02 - Ghalib Safaraz Harimurti)
Kamus istilah dan definisi seputar Kekerasan Berbasis Gender.
- `id`: BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY
- `istilah`: VARCHAR(150) NOT NULL
- `definisi`: TEXT NOT NULL
- `kategori`: VARCHAR(100) NULL (kategori teks bebas opsional)
- `status_publikasi`: ENUM('draft', 'published') DEFAULT 'published'
- `created_at`, `updated_at`: TIMESTAMP NULL

#### 17. `pengumuman` (Pemilik: PBI-03 - Ghalib Safaraz Harimurti)
Pengumuman publik pada portal sistem (tidak mengirim notifikasi otomatis).
- `id`: BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY
- `dibuat_oleh`: BIGINT UNSIGNED NOT NULL (FK ke `users.id`)
- `judul`: VARCHAR(200) NOT NULL
- `isi`: TEXT NOT NULL
- `mulai_tayang`: DATE NOT NULL
- `akhir_tayang`: DATE NULL
- `status`: ENUM('aktif', 'arsip') DEFAULT 'aktif'
- `created_at`, `updated_at`: TIMESTAMP NULL

#### 18. `kategori_artikel` (Pemilik: PBI-11 - Niky Setiawan)
Kategori untuk pengelompokan artikel edukasi.
- `id`: BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY
- `nama_kategori`: VARCHAR(100) UNIQUE NOT NULL
- `deskripsi`: VARCHAR(255) NULL
- `created_at`, `updated_at`: TIMESTAMP NULL

#### 19. `artikel` (Pemilik: PBI-10 - Niky Setiawan)
Konten artikel edukasi pencegahan dan wawasan KBG.
- `id`: BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY
- `kategori_id`: BIGINT UNSIGNED NOT NULL (FK ke `kategori_artikel.id`)
- `penulis_id`: BIGINT UNSIGNED NOT NULL (FK ke `users.id`)
- `judul`: VARCHAR(255) NOT NULL
- `slug`: VARCHAR(255) UNIQUE NOT NULL
- `isi`: LONGTEXT NOT NULL
- `status_publikasi`: ENUM('draft', 'published', 'archived') DEFAULT 'draft'
- `created_at`, `updated_at`: TIMESTAMP NULL

#### 20. `feedback_artikel` (Pemilik: PBI-12 - Niky Setiawan)
Ulasan atau umpan balik pengguna terhadap materi edukasi.
- `id`: BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY
- `artikel_id`: BIGINT UNSIGNED NOT NULL (FK ke `artikel.id` ON DELETE CASCADE)
- `user_id`: BIGINT UNSIGNED NOT NULL (FK ke `users.id`)
- `isi_feedback`: TEXT NOT NULL
- `status_moderasi`: ENUM('pending', 'disetujui', 'ditolak') DEFAULT 'pending'
- `created_at`, `updated_at`: TIMESTAMP NULL

---

### Domain 5: Kegiatan Edukasi & Direktori Instansi Bantuan

#### 21. `kegiatan_edukasi` (Pemilik: PBI-05 - Glen Vian Agus Cahya Tarigan)
Penyelenggaraan webinar atau lokakarya edukasi anti-kekerasan.
- `id`: BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY
- `judul`: VARCHAR(255) NOT NULL
- `deskripsi`: TEXT NOT NULL
- `narasumber`: VARCHAR(200) NOT NULL
- `tanggal_mulai`: DATE NOT NULL
- `waktu_mulai`: TIME NOT NULL
- `tanggal_selesai`: DATE NOT NULL
- `waktu_selesai`: TIME NOT NULL
- `metode`: ENUM('online', 'offline') NOT NULL
- `lokasi_atau_tautan`: VARCHAR(500) NULL
- `kuota`: INT UNSIGNED NOT NULL
- `status`: ENUM('buka', 'tutup', 'selesai', 'dibatalkan') DEFAULT 'buka'
- `created_at`, `updated_at`: TIMESTAMP NULL

#### 22. `pendaftaran_kegiatan` (Pemilik: PBI-05 - Glen Vian Agus Cahya Tarigan)
Pendaftaran peserta ke dalam agenda kegiatan edukasi.
- `id`: BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY
- `kegiatan_id`: BIGINT UNSIGNED NOT NULL (FK ke `kegiatan_edukasi.id` ON DELETE CASCADE)
- `user_id`: BIGINT UNSIGNED NOT NULL (FK ke `users.id` ON DELETE CASCADE)
- `status_pendaftaran`: ENUM('terdaftar', 'hadir', 'batal') DEFAULT 'terdaftar'
- `created_at`, `updated_at`: TIMESTAMP NULL
- *UNIQUE (`kegiatan_id`, `user_id`)*

#### 23. `instansi_bantuan` (Pemilik: PBI-07 - Muhammad Haqi Aqilla)
Direktori lembaga penyedia layanan rujukan, hukum, atau psikososial.
- `id`: BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY
- `nama_instansi`: VARCHAR(200) NOT NULL
- `alamat`: TEXT NOT NULL
- `deskripsi`: TEXT NULL
- `website`: VARCHAR(255) NULL
- `status`: ENUM('aktif', 'nonaktif') DEFAULT 'aktif'
- `created_at`, `updated_at`: TIMESTAMP NULL

#### 24. `layanan_bantuan` (Pemilik: PBI-08 - Muhammad Haqi Aqilla)
Daftar tipe layanan yang disediakan instansi (hukum, medis, rumah aman, dll).
- `id`: BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY
- `instansi_id`: BIGINT UNSIGNED NOT NULL (FK ke `instansi_bantuan.id` ON DELETE CASCADE)
- `nama_layanan`: VARCHAR(150) NOT NULL
- `deskripsi`: TEXT NULL
- `created_at`, `updated_at`: TIMESTAMP NULL

#### 25. `kontak_bantuan` (Pemilik: PBI-08 - Muhammad Haqi Aqilla)
Nomor hotline, WhatsApp darurat, atau kontak telepon instansi.
- `id`: BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY
- `instansi_id`: BIGINT UNSIGNED NOT NULL (FK ke `instansi_bantuan.id` ON DELETE CASCADE)
- `jenis_kontak`: ENUM('telepon', 'whatsapp', 'email', 'hotline') NOT NULL
- `nilai_kontak`: VARCHAR(150) NOT NULL
- `keterangan`: VARCHAR(200) NULL
- `created_at`, `updated_at`: TIMESTAMP NULL

#### 26. `faq` (Pemilik: PBI-09 - Muhammad Haqi Aqilla)
Pertanyaan umum dan panduan bantuan (*Frequently Asked Questions*).
- `id`: BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY
- `pertanyaan`: TEXT NOT NULL
- `jawaban`: TEXT NOT NULL
- `status_publikasi`: ENUM('draft', 'published') DEFAULT 'published'
- `urutan`: INT UNSIGNED DEFAULT 1
- `created_at`, `updated_at`: TIMESTAMP NULL
