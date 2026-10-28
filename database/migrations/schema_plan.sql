-- SQL Schema Migration Plan for RuangAman (26 Unique Tables)
-- Engine: InnoDB, Charset: utf8mb4, Collation: utf8mb4_unicode_ci

-- DOMAIN 1: USERS, ACCESS & NOTIFICATIONS
-- 1. roles
-- 2. permissions
-- 3. role_permissions
-- 4. users
-- 5. profil_pelapor (PBI-17 Fauzi)
-- 6. notifikasi (PBI-18 Fauzi)

-- DOMAIN 2: PELAPORAN & PENANGANAN KASUS
-- 7. status_kasus (PBI-04 Glen)
-- 8. laporan_kasus (PBI-16 Fauzi)
-- 9. lampiran_laporan (PBI-16 Fauzi)
-- 10. penanganan_kasus (PBI-04 Glen)
-- 11. log_penanganan (PBI-06 Glen)

-- DOMAIN 3: KONSELING
-- 12. konselor (PBI-13 Altaf)
-- 13. jadwal_konseling (PBI-14 Altaf)
-- 14. sesi_konseling (PBI-15 Altaf)

-- DOMAIN 4: EDUKASI & PUBLIKASI
-- 15. halaman_informasi (PBI-01 Ghalib)
-- 16. glosarium_kbg (PBI-02 Ghalib)
-- 17. pengumuman (PBI-03 Ghalib)
-- 18. kategori_artikel (PBI-11 Niky)
-- 19. artikel (PBI-10 Niky)
-- 20. feedback_artikel (PBI-12 Niky)

-- DOMAIN 5: KEGIATAN & INSTANSI BANTUAN
-- 21. kegiatan_edukasi (PBI-05 Glen)
-- 22. pendaftaran_kegiatan (PBI-05 Glen)
-- 23. instansi_bantuan (PBI-07 Haqi)
-- 24. layanan_bantuan (PBI-08 Haqi)
-- 25. kontak_bantuan (PBI-08 Haqi)
-- 26. faq (PBI-09 Haqi)
