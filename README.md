# RuangAman 🛡️

> **Aplikasi Web untuk Pelaporan, Konseling, dan Penanganan Kekerasan Berbasis Gender (KBG)**  
> Dibangun dengan prinsip *Privacy by Design*, kepatuhan terhadap amanat **UU No. 12 Tahun 2022 (UU TPKS)**, **UU No. 27 Tahun 2022 (UU PDP)**, serta mendukung pencapaian **SDG 5.2** (Penghapusan Kekerasan terhadap Perempuan dan Anak).

[![Framework: Laravel 13.x](https://img.shields.io/badge/Backend-Laravel%2013.x-red.svg)](https://laravel.com)
[![PHP: ^8.3](https://img.shields.io/badge/PHP-%5E8.3-blue.svg)](https://php.net)
[![Frontend: TailwindCSS & Alpine.js](https://img.shields.io/badge/Frontend-TailwindCSS%20%2B%20Alpine.js-38bdf8.svg)](https://tailwindcss.com)
[![Database: MySQL 8.x](https://img.shields.io/badge/Database-MySQL%208.x-orange.svg)](https://mysql.com)
[![License: MIT](https://img.shields.io/badge/License-MIT-green.svg)](LICENSE)

---

## 📌 Daftar Isi
1. [Tentang RuangAman](#-tentang-ruangaman)
2. [Fitur Utama Sistem](#-fitur-utama-sistem)
3. [Arsitektur & Tech Stack](#-arsitektur--tech-stack)
4. [Tim Pengembang & Pembagian Tugas (PBI)](#-tim-pengembang--pembagian-tugas-pbi)
5. [Jadwal & Roadmap Sprint](#-jadwal--roadmap-sprint)
6. [Struktur Basis Data (26 Tabel Unik)](#-struktur-basis-data-26-tabel-unik)
7. [Panduan Instalasi & Menjalankan Lokal](#-panduan-instalasi--menjalankan-lokal)
8. [Standar Kolaborasi Git & Repository](#-standar-kolaborasi-git--repository)
9. [Dokumentasi Lengkap](#-dokumentasi-lengkap)

---

## 📖 Tentang RuangAman

Kekerasan Berbasis Gender (KBG) di Indonesia masih menghadapi tantangan besar: korban takut melapor karena kekhawatiran identitas bocor, stigma sosial, ketidaktahuan saluran pengaduan resmi, serta proses penanganan manual yang lambat dan minim transparansi.

**RuangAman** hadir sebagai solusi berbasis teknologi terpadu yang memfasilitasi:
- **Pelaporan Aman**: Akun menggunakan alias/pseudonim dengan opsi **Mode Anonim** per pelaporan (menyembunyikan identitas profil bahkan dari konselor penangan).
- **Pelacakan Transparan Tanpa Kompromi Privasi**: Pelapor mendapatkan kode pelacakan unik untuk memantau transisi status (`Pending` $\rightarrow$ `Proses` $\rightarrow$ `Selesai` / `Ditolak`) tanpa perlu mempublikasikan data kronologi kasus.
- **Akuntabilitas & Audit Trail**: Setiap perubahan status kasus diverifikasi Admin dan dicatat secara terperinci dalam log aktivitas penanganan.
- **Konseling Terjadwal**: Layanan pemesanan konseling online maupun offline beserta pencatatan sesi dan riwayat pemulihan.
- **Ekosistem Edukasi & Bantuan**: Artikel edukasi terverifikasi, forum umpan balik, glosarium istilah KBG, kegiatan/webinar edukasi, FAQ, serta direktori instansi dan nomor kontak darurat terpusat.
- **Keamanan Rentan**: Fitur tombol **Keluar Cepat (*Quick Exit*)** yang langsung mengalihkan browser ke halaman aman jika pengguna dalam kondisi darurat fisik.

---

## 🚀 Fitur Utama Sistem

| Domain | Fitur & Deskripsi |
| :--- | :--- |
| **Keamanan & Akun** | Registrasi dengan alias/pseudonim, email terenkripsi untuk notifikasi, Role-Based Access Control (Admin, Konselor, Pelapor), dan tombol Keluar Cepat. |
| **Pelaporan Kasus** | Form pengaduan terstruktur, kategori kekerasan tetap, lokasi teks bebas, upload berkas bukti ke penyimpanan privat, dan pembuatan kode lacak unik otomatis. |
| **Penanganan Kasus** | Alur verifikasi status laporan terpusat, penugasan kasus ke konselor terdaftar, dan audit trail log penanganan (terkunci otomatis). |
| **Layanan Konseling** | Direktori profil konselor/psikolog, manajemen slot jadwal online/offline, pendaftaran jadwal, dan rekap hasil sesi konseling. |
| **Edukasi & Informasi** | Publikasi artikel KBG berkategori, sistem moderasi feedback artikel, glosarium istilah KBG, serta manajemen pengumuman publik. |
| **Kegiatan Edukasi** | Penyelenggaraan webinar/workshop, kuota peserta, pendaftaran peserta mandiri, dan verifikasi kehadiran oleh Admin. |
| **Pusat Bantuan** | Direktori instansi/lembaga rujukan, daftar kontak darurat & jenis layanan bantuan, serta Pusat Bantuan/FAQ interaktif. |
| **Dashboard Terpadu** | Visualisasi statistik kasus (Chart.js), pemantauan beban kasus, jadwal konseling terdekat, dan notifikasi in-app. |

---

## 🛠️ Arsitektur & Tech Stack

Aplikasi RuangAman dikembangkan dengan arsitektur **Monolit Modular MVC (Model-View-Controller)** yang terbagi dalam 3 lapisan:

```
[ Klien / Browser ] (Blade View + Tailwind CSS + Alpine.js + Chart.js)
        │
        ▼ HTTPS
[ Web Server ] (Nginx / Apache + PHP-FPM)
        │
        ▼
[ Laravel 13.x Framework ]
  ├── Routing & Middleware (Auth, Role/Permission RBAC, CSRF, Secure Headers)
  ├── Controllers & Form Request Validation
  ├── Domain Service Layer (CaseApprovalService, NotificationService)
  ├── Eloquent ORM & Policies
  └── Asynchronous Jobs & Queue (Email Mailtrap/SMTP)
        │
        ▼
[ Data Layer ]
  ├── MySQL 8.x (InnoDB Engine, Foreign Key Strict Constraints)
  └── Private File Storage (`storage/app/private/` untuk bukti laporan)
```

- **Backend**: PHP >= 8.3, Laravel 13.x
- **Frontend**: Blade Templating, Tailwind CSS v3/v4, Alpine.js, Chart.js
- **Database**: MySQL 8.x (InnoDB)
- **Queue & Mailer**: Database Queue Driver, SMTP (Mailtrap di environment lokal/testing)
- **Testing**: PHPUnit / Pest Framework

---

## 👥 Tim Pengembang & Pembagian Tugas (PBI)

Tim beranggotakan enam pengembang dengan pembagian tugas independen berbasis modul (3 PBI individu per anggota + modul fondasi dan integrasi bersama):

| No | Anggota | NIM | Peran Utama | PBI Individu | Tabel Utama yang Dimiliki |
| :---: | :--- | :---: | :--- | :--- | :--- |
| 1 | **Ghalib Safaraz Harimurti** | `102022430041` | Scrum Master, PM & Auth | **PBI-01**: Halaman Informasi Statis<br>**PBI-02**: Glosarium Istilah KBG<br>**PBI-03**: Pengumuman Publik | `halaman_informasi`<br>`glosarium_kbg`<br>`pengumuman` |
| 2 | **Glen Vian Agus Cahya Tarigan** | `102022400308` | Backend Dev - Kasus | **PBI-04**: Status & Penugasan Kasus<br>**PBI-05**: Kegiatan/Webinar Edukasi<br>**PBI-06**: Log Penanganan Kasus | `status_kasus`, `penanganan_kasus`<br>`kegiatan_edukasi`, `pendaftaran_kegiatan`<br>`log_penanganan` |
| 3 | **Muhammad Haqi Aqilla** | `102022400092` | Full-Stack Dev - Bantuan | **PBI-07**: Instansi Bantuan<br>**PBI-08**: Layanan & Kontak Bantuan<br>**PBI-09**: FAQ / Pusat Bantuan | `instansi_bantuan`<br>`layanan_bantuan`, `kontak_bantuan`<br>`faq` |
| 4 | **Niky Setiawan** | `102022400155` | Frontend/Content Dev | **PBI-10**: Artikel Edukasi<br>**PBI-11**: Kategori Artikel<br>**PBI-12**: Feedback Artikel | `artikel`<br>`kategori_artikel`<br>`feedback_artikel` |
| 5 | **Altaf Maulana** | `102022400049` | Full-Stack Dev - Konseling | **PBI-13**: Data Konselor/Psikolog<br>**PBI-14**: Jadwal Konseling<br>**PBI-15**: Sesi Konseling | `konselor`<br>`jadwal_konseling`<br>`sesi_konseling` |
| 6 | **Fauzi Abdurrahman Musyaffa** | `102022400078` | Backend Dev - Pelaporan | **PBI-16**: Laporan Kasus & Lampiran Bukti<br>**PBI-17**: Profil Pelapor & Mode Anonim<br>**PBI-18**: Notifikasi Sistem | `laporan_kasus`, `lampiran_laporan`<br>`profil_pelapor`<br>`notifikasi` |
| - | **Seluruh Tim (Bersama)** | - | Fondasi & Integrasi | **Fondasi Bersama**: Auth, User, Role, Permissions<br>**Integrasi Bersama**: Dashboard, Tracking, Alur Status, Quick Exit, UAT | `users`, `roles`, `permissions`, `role_permissions`<br>*(Semua tabel relasional)* |

---

## 📅 Jadwal & Roadmap Sprint

Pengembangan mengikuti metodologi **Scrum** dalam 2 Sprint sesuai kalender perkuliahan:

```
[ Week 6 - Week 10 ]  ──────────────►  [ Week 11 - Week 16 ]
      SPRINT 1                                SPRINT 2
(19 Okt - 22 Nov 2026)                 (23 Nov 2026 - 3 Jan 2027)
* Fondasi Auth & RBAC                  * Fitur Lanjutan (Webinar, Sesi Konseling,
* Modul Inti Pelaporan & Bukti           Layanan Bantuan, Feedback, Notifikasi)
* Profil Anonim & Glosarium            * Integrasi Dashboard Terpusat
* Instansi Bantuan & Artikel Edukasi   * Tombol Quick Exit & Tracking Publik
* Penugasan & Status Kasus Inti        * Pengujian E2E, UAT, Deployment & Demo
```

- **Sprint 1 (Week 6 – 10: 19 Oktober – 22 November 2026)**:
  - Fokus: PBI Fondasi Bersama, PBI-01, PBI-02, PBI-03, PBI-04, PBI-07, PBI-10, PBI-11, PBI-13, PBI-16, PBI-17.
- **Sprint 2 (Week 11 – 16: 23 November 2026 – 3 Januari 2027)**:
  - Fokus: PBI-05, PBI-06, PBI-08, PBI-09, PBI-12, PBI-14, PBI-15, PBI-18, dan PBI Tim Integrasi Bersama.

---

## 🗄️ Struktur Basis Data (26 Tabel Unik)

Basis data RuangAman dirancang dengan prinsip **independensi modul**: *setiap PBI memiliki tabelnya sendiri dan tidak mengubah struktur tabel modul lain*.

1. **Akses Pengguna & Notifikasi**: `users`, `roles`, `permissions`, `role_permissions`, `profil_pelapor`, `notifikasi`.
2. **Pelaporan & Penanganan Kasus**: `status_kasus`, `laporan_kasus`, `lampiran_laporan`, `penanganan_kasus`, `log_penanganan`.
3. **Layanan Konseling**: `konselor`, `jadwal_konseling`, `sesi_konseling`.
4. **Edukasi & Publikasi**: `kategori_artikel`, `artikel`, `feedback_artikel`, `glosarium_kbg`, `halaman_informasi`, `pengumuman`.
5. **Kegiatan & Instansi Bantuan**: `kegiatan_edukasi`, `pendaftaran_kegiatan`, `instansi_bantuan`, `layanan_bantuan`, `kontak_bantuan`, `faq`.

*Kamus data lengkap dan relasi foreign key dapat dilihat di [docs/database/data-dictionary.md](docs/database/data-dictionary.md).*

---

## 💻 Panduan Instalasi & Menjalankan Lokal

### Prasyarat Sistem
- PHP >= 8.3 (dengan ekstensi `pdo_mysql`, `mbstring`, `openssl`, `tokenizer`, `xml`, `ctype`, `curl`, `fileinfo`)
- Composer >= 2.x
- Node.js >= 20.x & NPM
- MySQL >= 8.0

### Langkah Instalasi
1. **Clone Repository**:
   ```bash
   git clone https://github.com/<org-name>/RuangAman.git
   cd RuangAman
   ```

2. **Setup File Environment**:
   ```bash
   copy .env.example .env
   ```
   Sesuaikan konfigurasi database (`DB_DATABASE=ruangaman`, `DB_USERNAME`, `DB_PASSWORD`) dan driver email di `.env`.

3. **Install Dependensi Backend & Frontend**:
   ```bash
   composer install
   npm install
   ```

4. **Generate Application Key**:
   ```bash
   php artisan key:generate
   ```

5. **Migrasi Database & Seeding Data Awal**:
   ```bash
   php artisan migrate --seed
   ```
   *Seeder akan membuat akun default untuk Admin Kasus, Konselor, dan Pelapor contoh beserta data referensi awal.*

6. **Tautkan Storage Berkas**:
   ```bash
   php artisan storage:link
   ```

7. **Jalankan Aplikasi**:
   Jalankan server aplikasi di satu terminal:
   ```bash
   php artisan serve
   ```
   Dan jalankan kompilasi asset frontend di terminal lainnya:
   ```bash
   npm run dev
   ```
   Buka browser di `http://127.0.0.1:8000`.

---

## 🤝 Standar Kolaborasi Git & Repository

Untuk mencegah konflik merge antar enam anggota:
1. **Branching Model**:
   - `main`: Kode stabil siap produksi.
   - `develop`: Tempat integrasi sprint aktif.
   - `feature/PBI-<nomor>-<nama-fitur>`: Branch pengerjaan fitur individu (contoh: `feature/PBI-16-laporan-kasus`).
   - `bugfix/<nama-perbaikan>`: Branch perbaikan issue.
2. **Commit Convention**: Mengikuti *Conventional Commits* (contoh: `feat(PBI-16): implement case report submission form with anonymous toggle`).
3. **Pull Request (PR)**:
   - Setiap PR wajib mencantumkan referensi PBI, deskripsi perubahan, checklist pengujian, dan disetujui minimal 1 reviewer sebelum di-merge ke `develop`.
   - Lihat panduan lengkap di [CONTRIBUTING.md](CONTRIBUTING.md).

---

## 📚 Dokumentasi Lengkap

- [Panduan Kontribusi & Alur Git (CONTRIBUTING.md)](CONTRIBUTING.md)
- [Kebijakan Keamanan & Privasi (SECURITY.md)](SECURITY.md)
- [Kode Etik Kolaborasi (CODE_OF_CONDUCT.md)](CODE_OF_CONDUCT.md)
- [Arsitektur Sistem & Workflow Persetujuan (docs/architecture/)](docs/architecture/system-architecture.md)
- [Kamus Data & Skema ERD 26 Tabel (docs/database/)](docs/database/data-dictionary.md)
- [Detail Pembagian PBI & Jadwal Sprint (docs/sprints/)](docs/sprints/sprint-backlog.md)

---
*Proyek ini dikembangkan untuk tujuan akademis dan sosial dalam pencegahan serta penanganan Kekerasan Berbasis Gender.*
