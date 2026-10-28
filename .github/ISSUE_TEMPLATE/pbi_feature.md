---
name: "Task / PBI Feature"
about: "Template untuk pengerjaan Product Backlog Item (PBI) individu atau fitur bersama"
title: "[PBI-XX] Judul Fitur / PBI"
labels: ["enhancement", "sprint-1"]
assignees: ""
---

### 📋 Deskripsi PBI
Jelaskan secara ringkas fitur atau modul yang akan dikerjakan sesuai dokumen proposal RuangAman.

- **Nomor PBI**: PBI-XX (misal: PBI-16, PBI-04, PBI-01, dll)
- **PIC / Penanggung Jawab**: (Pilih: Ghalib / Glen / Haqi / Niky / Altaf / Fauzi / Seluruh Tim)
- **Sprint**: Sprint 1 / Sprint 2

### 🗄️ Entitas Basis Data & Migrasi Terkait
- **Tabel Utama Milik PBI**: `nama_tabel`
- **Tabel Referensi Hanya-Baca (Seeder)**: `nama_tabel_referensi`
*(Pastikan tidak mengubah struktur tabel di luar kepemilikan PBI ini)*

### ✅ Kriteria Penerimaan (Acceptance Criteria)
Sebutkan kriteria fungsional CRUD / logic yang harus dipenuhi:
- [ ] Formulir input tervalidasi dengan Form Request.
- [ ] Hak akses diatur melalui Policy/Permission.
- [ ] Operasi Create, Read, Update, Delete berjalan sesuai aturan bisnis.
- [ ] Tampilan responsif di layar mobile (min. 360px).
- [ ] Seeder data contoh untuk pengujian mandiri telah tersedia.

### 🧪 Rencana Pengujian
- [ ] Unit Test / Feature Test (Pest/PHPUnit).
- [ ] Pengujian manual antarmuka.

### 📌 Catatan Khusus
(Contoh: Penanganan privasi, audit log, atau pembatasan permission).
