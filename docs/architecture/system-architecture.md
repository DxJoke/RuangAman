# Arsitektur Sistem RuangAman 🏗️

Dokumen ini merangkum arsitektur teknis sistem **RuangAman** sebagaimana dispesifikasikan dalam Bab 4 Proposal Proyek.

---

## 1. Ikhtisar Arsitektur Tiga Lapisan (3-Tier Monolith MVC)

Sistem RuangAman dirancang menggunakan arsitektur **Monolit Modular Berpola MVC (Model-View-Controller)** yang menggabungkan kemudahan deployment, kohesi data, serta kemudahan isolasi tanggung jawab kerja antar enam pengembang.

```
+-------------------------------------------------------------------------+
|                         LAPISAN KLIEN (Client)                          |
|  - Web Browser Desktop & Mobile (Responsive min. 360px)                 |
|  - Blade Templates + Tailwind CSS + Alpine.js (Reaktivitas UI)          |
|  - Chart.js (Visualisasi Statistik Dashboard)                           |
|  - Emergency Action: Tombol "Keluar Cepat" (Quick Exit)                 |
+-------------------------------------------------------------------------+
                                     |
                                     | HTTPS (TLS / Let's Encrypt)
                                     v
+-------------------------------------------------------------------------+
|                       LAPISAN APLIKASI (Backend)                        |
|  - Web Server: Nginx / Apache + PHP-FPM                                 |
|  - Framework: Laravel 13.x (PHP 8.3+)                                   |
|                                                                         |
|  [Middleware Pipeline]                                                  |
|    * EncryptCookies, VerifyCsrfToken, Authenticate                      |
|    * CheckRoleAndPermission (RBAC Policy Enforcement)                   |
|    * SecurityHeadersMiddleware                                          |
|                                                                         |
|  [Controllers & Form Requests]                                          |
|    * Menangani HTTP Request, Sanitasi & Validasi Ketat                  |
|                                                                         |
|  [Domain Services & Workflows]                                          |
|    * CaseApprovalService (Satu-satunya service mutasi status kasus)     |
|    * NotificationService (Pemicu in-app notifikasi otomatis)            |
|                                                                         |
|  [Eloquent ORM & Business Policies]                                     |
|    * Model Domain, Hubungan Relasional, & Policy Akses Privat           |
|                                                                         |
|  [Asynchronous Jobs & Queue]                                            |
|    * Pengiriman Email Notifikasi & Reset Password via Database Queue    |
+-------------------------------------------------------------------------+
                                     |
                                     | TCP / PDO
                                     v
+-------------------------------------------------------------------------+
|                          LAPISAN DATA (Storage)                         |
|  - MySQL 8.x Database (InnoDB Engine, Foreign Key Strict Constraints)   |
|  - Private File Storage (storage/app/private/ untuk lampiran bukti)     |
+-------------------------------------------------------------------------+
```

---

## 2. Prinsip Penting & Pola Desain (Design Principles)

### A. Alur Kerja Perubahan Status Kasus Terpusat (`CaseApprovalService`)
Sesuai rancangan proposal:
- Modul `status_kasus` (PBI-04) hanya bertindak sebagai CRUD data master.
- **Perubahan status aktual laporan kasus TIDAK dilakukan melalui CRUD master**, melainkan melalui satu alur workflow terpusat (**FR-15**) yang dikelola oleh service bersama: `CaseApprovalService`.
- Alur perubahan status:
  1. Status awal laporan saat diajukan pelapor: `Pending`.
  2. Admin memeriksa kelayakan dan memvalidasi transisi status ke `Proses` atau `Ditolak`.
  3. Saat status `Proses`, Admin menugaskan konselor pendamping (`penanganan_kasus`).
  4. Konselor melakukan pendampingan dan mencatat hasil hingga penanganan tuntas (`Selesai`).
  5. Setiap perubahan status menulis ke tiga entitas sekaligus secara atomik (*Database Transaction*):
     - Memperbarui `status_id` di `laporan_kasus`.
     - Memperbarui catatan di `penanganan_kasus`.
     - Menulis catatan audit permanen di `log_penanganan` yang terkunci dari penghapusan (*immutable*).

### B. Isolasi Berkas Bukti Sensitif (*Private Storage*)
- Bukti lampiran laporan kasus KBG (foto luka, tangkapan layar chat, dokumen medis) adalah data sangat sensitif.
- Berkas disimpan di storage privat: `storage/app/private/bukti_laporan/{laporan_id}/{filename}`.
- Pengunduhan berkas dilindungi oleh `LaporanKasusPolicy@viewAttachment`:
  ```php
  // Hanya Pelapor pemilik kasus, Konselor yang ditugaskan, dan Admin Kasus yang dapat mengakses
  if ($user->id === $laporan->user_id || $user->isAssignedCounselor($laporan->id) || $user->hasRole('Admin Kasus')) {
      return response()->file($path);
  }
  abort(403, 'Akses lampiran ditolak.');
  ```

### C. Mode Anonim & Proteksi Privasi Pelapor
- Pelapor dapat mengaktifkan flag `mode_anonim = true` pada formulir laporan.
- Saat flag aktif:
  - Tampilan dashboard dan detail laporan untuk Konselor menyembunyikan profil asli pelapor dan hanya menampilkan alias unik yang digenerate sistem.
  - Alamat email pengguna tidak pernah di-query atau dirender di halaman manapun di luar profil akun pribadi pelapor.

### D. Fitur Tombol "Keluar Cepat" (*Quick Exit*)
- Tombol permanen di navbar/header aplikasi.
- Saat diklik (atau menekan tombol `Esc` ganda):
  - Browser seketika me-redirect URL ke halaman umum yang aman (contoh: `https://www.google.com`).
  - History session lokal dan cached tab dibersihkan untuk melindungi keselamatan fisik penyintas jika pelaku mendekat secara tiba-tiba.
