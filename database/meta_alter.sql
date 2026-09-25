-- =============================================================
-- Migrasi ALTER tabel mode-spesifik (untuk Fresh Install).
-- Hanya dipakai Docker-entrypoint (skema lama pasti baru dibuat
-- oleh 01-pis_akademik.sql, jadi kolom belum ada -> ALTER aman).
-- Instalasi existing: gunakan scripts/migrate_metadata.sh.
-- =============================================================

-- peserta & guru: mode pemilik data
ALTER TABLE `tbl_siswa` ADD COLUMN `kd_mode` varchar(10) NOT NULL DEFAULT 'SMP' AFTER `kd_kelas`;
ALTER TABLE `tbl_siswa` ADD COLUMN `nisn` varchar(20) NOT NULL DEFAULT '' AFTER `nim`;
ALTER TABLE `tbl_guru` ADD COLUMN `kd_mode` varchar(10) NOT NULL DEFAULT 'SMP' AFTER `password`;

-- rombongan belajar
ALTER TABLE `tbl_kelas` ADD COLUMN `kd_mode` varchar(10) NOT NULL DEFAULT 'SMP' AFTER `kd_jurusan`;
ALTER TABLE `tbl_kelas` ADD COLUMN `kd_prodi` varchar(20) NOT NULL DEFAULT '' AFTER `kd_jurusan`;
ALTER TABLE `tbl_kelas` ADD COLUMN `angkatan` varchar(10) NOT NULL DEFAULT '' AFTER `kd_prodi`;

-- jadwal & nilai (nilai juga menyimpan sks per pemain)
ALTER TABLE `tbl_jadwal` ADD COLUMN `kd_mode` varchar(10) NOT NULL DEFAULT 'SMP' AFTER `hari`;
ALTER TABLE `tbl_nilai` ADD COLUMN `kd_mode` varchar(10) NOT NULL DEFAULT 'SMP' AFTER `nilai`;
ALTER TABLE `tbl_nilai` ADD COLUMN `sks` int(11) NOT NULL DEFAULT 0 AFTER `nilai`;

ALTER TABLE `tbl_pembayaran` ADD COLUMN `kd_mode` varchar(10) NOT NULL DEFAULT 'SMP' AFTER `keterangan`;
ALTER TABLE `tbl_riwayat_kelas` ADD COLUMN `kd_mode` varchar(10) NOT NULL DEFAULT 'SMP';
ALTER TABLE `tbl_walikelas` ADD COLUMN `kd_mode` varchar(10) NOT NULL DEFAULT 'SMP';
ALTER TABLE `tbl_tahun_akademik` ADD COLUMN `kd_mode` varchar(10) NOT NULL DEFAULT 'SMP' AFTER `semester`;
ALTER TABLE `tabel_menu` ADD COLUMN `berlaku_mode` varchar(255) NOT NULL DEFAULT 'ALL' AFTER `is_main_menu`;
-- menu baru multi-mode
INSERT INTO `tabel_menu` (`id`, `nama_menu`, `link`, `icon`, `is_main_menu`, `berlaku_mode`) VALUES
(19, 'Referensi', 'referensi', 'fa fa-database', 0, 'ALL'),
(20, 'KRS', 'krs', 'fa fa-list-alt', 0, 'KAMPUS'),
(21, 'Pengaturan', 'pengaturan', 'fa fa-cog', 0, 'ALL')
ON DUPLICATE KEY UPDATE `berlaku_mode` = VALUES(`berlaku_mode`);
INSERT INTO `tbl_user_rule` (`id_menu`, `id_level_user`) VALUES (19, 1), (20, 1), (21, 1)
ON DUPLICATE KEY UPDATE `id_level_user` = VALUES(`id_level_user`);
-- Modul khas sekolah disembunyikan di mode KAMPUS (pengisian nilai kampus via KRS);
-- TK/PAUD (sekolah) tetap mendapat Jadwal & Nilai.
UPDATE `tabel_menu` SET `berlaku_mode` = 'SMA,SMP,SD,TK' WHERE `link` IN ('jadwal', 'nilai');
-- Admin level 1 ikut melihat menu Nilai & Laporan Nilai (sebelumnya hanya level guru)
INSERT INTO `tbl_user_rule` (`id_menu`, `id_level_user`) VALUES (17, 1), (18, 1)
ON DUPLICATE KEY UPDATE `id_level_user` = VALUES(`id_level_user`);

-- Identitas instansi per mode (KAMPUS/SMA/SMP/SD/TK punya kop sendiri)
ALTER TABLE `tbl_identitas` ADD COLUMN `kd_mode` varchar(10) NOT NULL DEFAULT 'SMP' AFTER `id`;
UPDATE `tbl_identitas` SET `kd_mode` = 'SMP' WHERE `kd_mode` = '';

-- Ruangan: data kelas per mode (data lama default SMP supaya tetap tampil)
ALTER TABLE `tbl_ruangan` ADD COLUMN `kd_mode` varchar(10) NOT NULL DEFAULT 'SMP' AFTER `nama_ruangan`;
INSERT INTO `tbl_ruangan` (`kd_ruangan`, `nama_ruangan`, `kd_mode`) VALUES
('SD1A','Ruang Kelas 1A','SD'),('SD1B','Ruang Kelas 1B','SD'),
('SD2A','Ruang Kelas 2A','SD'),('SD2B','Ruang Kelas 2B','SD'),
('SD3A','Ruang Kelas 3A','SD'),('SD3B','Ruang Kelas 3B','SD'),
('SD4A','Ruang Kelas 4A','SD'),('SD4B','Ruang Kelas 4B','SD'),
('SD5A','Ruang Kelas 5A','SD'),('SD5B','Ruang Kelas 5B','SD'),
('SD6A','Ruang Kelas 6A','SD'),('SD6B','Ruang Kelas 6B','SD'),
('SX1A','Ruang Kelas X-1','SMA'),('SX2A','Ruang Kelas X-2','SMA'),
('SX3A','Ruang Kelas XI-1','SMA'),('SX4A','Ruang Kelas XI-2','SMA'),
('SX5A','Ruang Kelas XII-1','SMA'),('SX6A','Ruang Kelas XII-2','SMA'),
('SLAB','Lab Komputer','SMA'),
('TKA','Taman Kanak-Kanak','TK'),('TKB','Ruang Bermain','TK'),
('RUA1','Ruang Kuliah A','KAMPUS'),('RUA2','Ruang Kuliah B','KAMPUS'),
('RSID','Ruang Sidang','KAMPUS'),('RLAB','Lab Komputer','KAMPUS')
ON DUPLICATE KEY UPDATE `nama_ruangan` = VALUES(`nama_ruangan`), `kd_mode` = VALUES(`kd_mode`);

-- Mapel: nama pelajaran per mode (data lama default SMP)
ALTER TABLE `tbl_mapel` ADD COLUMN `kd_mode` varchar(10) NOT NULL DEFAULT 'SMP' AFTER `nama_mapel`;
INSERT INTO `tbl_mapel` (`kd_mapel`, `nama_mapel`, `kd_mode`) VALUES
('BID1','Bahasa Indonesia 1','SMP'),('BID2','Bahasa Indonesia 2','SMP'),('BID3','Bahasa Indonesia 3','SMP'),
('BIO1','Biologi 1','SMP'),('BIO2','Biologi 2','SMP'),('BIO3','Biologi 3','SMP'),
('MTK1','Matematika 1','SMP'),('MTK2','Matematika 2','SMP'),('MTK3','Matematika 3','SMP'),
('PAI1','PAI 1','SMP'),('PAI2','PAI 2','SMP'),('PAI3','PAI 3','SMP'),
('SDBIN','Bahasa Indonesia','SD'),('SDMTK','Matematika','SD'),
('SDIPA','Ilmu Pengetahuan Alam','SD'),('SDIPS','Ilmu Pengetahuan Sosial','SD'),
('SDPAI','Pendidikan Agama Islam','SD'),('SDPJO','PJOK','SD'),('SDSEN','Seni Budaya','SD'),('SDBIG','Bahasa Inggris','SD'),
('SABIN','Bahasa Indonesia','SMA'),('SAMTK','Matematika','SMA'),('SABIG','Bahasa Inggris','SMA'),
('SAFIS','Fisika','SMA'),('SAKIM','Kimia','SMA'),('SABIO','Biologi','SMA'),
('SASEJ','Sejarah','SMA'),('SAGEO','Geografi','SMA'),('SAEKO','Ekonomi','SMA'),
('TKBHS','Bahasa & Bercerita','TK'),('TKMTK','Berhitung','TK'),
('TKAGM','Agama & Moral','TK'),('TKSEN','Seni & Motorik','TK'),
('KABIN','Bahasa Indonesia','KAMPUS'),('KAMTK','Matematika','KAMPUS'),('KABIG','Bahasa Inggris','KAMPUS'),
('KAPRG','Pemrograman Web','KAMPUS'),('KADB','Basis Data','KAMPUS'),('KAJAR','Jaringan Komputer','KAMPUS'),('KAAK','Akuntansi Keuangan','KAMPUS')
ON DUPLICATE KEY UPDATE `nama_mapel` = VALUES(`nama_mapel`), `kd_mode` = VALUES(`kd_mode`);