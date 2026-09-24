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