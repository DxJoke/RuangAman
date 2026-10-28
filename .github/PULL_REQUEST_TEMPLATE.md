## 📌 Ringkasan Perubahan (Pull Request Summary)
Jelaskan apa yang ditambahkan, diubah, atau diperbaiki dalam PR ini.

- **Nomor PBI / Issue Terkait**: Fixes # (atau PBI-XX)
- **PIC / Author**: @username
- **Target Branch**: `develop`

---

## 🗄️ Dampak Basis Data & Migrasi
- [ ] Ada penambahan file migrasi baru (Hanya untuk tabel milik PBI ini).
- [ ] Ada penambahan Seeder baru.
- [ ] **TIDAK** memodifikasi tabel milik PBI lain (Menjaga independensi skema database).

---

## 🔍 Checklist Kelayakan & Kualitas Kode
Pastikan seluruh poin berikut telah dipenuhi sebelum meminta review:
- [ ] Kode mematuhi standar PSR-12 dan konvensi penamaan Laravel.
- [ ] Masukan form tervalidasi menggunakan `FormRequest`.
- [ ] Autentikasi dan otorisasi dibatasi oleh `Policy` / `Gate` / Middleware yang sesuai.
- [ ] File lampiran sensitif disimpan di storage privat (`storage/app/private`), bukan publik.
- [ ] Desain antarmuka Blade telah diuji responsif pada lebar layar mobile minimum 360 px.
- [ ] Tidak ada kredensial atau API key sensitif yang ter-commit ke git.
- [ ] Feature Test / Unit Test telah dibuat dan berhasil lolos (`php artisan test`).

---

## 📸 Tangkapan Layar (Screenshots / Evidence)
*Lampirkan screenshot tampilan UI atau hasil pengujian terminal di sini jika relevan.*

---

## 👥 Reviewer yang Diminta
- [ ] Reviewer 1: @username
- [ ] Scrum Master / Lead Reviewer: @username
