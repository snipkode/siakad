-- =============================================================
-- SIAKAD Metadata-Driven Core Schema
-- Dibuat untuk branch feat/metadata-driven-modes
-- Penambahan kolom/atribut baru TIDAK perlu ALTER TABLE:
--   - definisi field   -> INSERT tbl_field
--   - nilai atribut    -> INSERT tbl_entitas_atribut (EAV)
--   - data taksonomi   -> INSERT tbl_referensi
-- Mode aktif          -> INSERT/UPDATE tbl_pengaturan (mode_aktif)
-- =============================================================

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

-- ---------------------------------------------------------------
-- 1. MASTER MODE (KAMPUS / SMA / SMP / SD / TK)
-- ---------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `tbl_mode` (
  `kd_mode` varchar(10) NOT NULL,
  `nama_mode` varchar(50) NOT NULL,
  `label_instansi` varchar(50) NOT NULL,
  `label_kepala` varchar(50) NOT NULL,
  `label_staf` varchar(50) NOT NULL,
  `label_peserta` varchar(50) NOT NULL,
  `label_nomor_induk` varchar(50) NOT NULL,
  `label_rombongan` varchar(50) NOT NULL,
  `label_mata_ajar` varchar(50) NOT NULL,
  `label_laporan` varchar(50) NOT NULL,
  `label_tahun` varchar(50) NOT NULL,
  `sistem_nilai` varchar(20) NOT NULL COMMENT 'ANGKA | KUALITATIF | GABUNGAN',
  `punya_jurusan` enum('Y','N') NOT NULL DEFAULT 'Y',
  `punya_prodi` enum('Y','N') NOT NULL DEFAULT 'N',
  `punya_tingkatan` enum('Y','N') NOT NULL DEFAULT 'Y',
  `punya_krs` enum('Y','N') NOT NULL DEFAULT 'N',
  `punya_sks` enum('Y','N') NOT NULL DEFAULT 'N',
  `punya_walikelas` enum('Y','N') NOT NULL DEFAULT 'Y',
  `punya_naik_kelas` enum('Y','N') NOT NULL DEFAULT 'Y',
  `punya_kkm` enum('Y','N') NOT NULL DEFAULT 'Y',
  `kkm_default` int(11) NOT NULL DEFAULT 75,
  `urutan` int(11) NOT NULL DEFAULT 0,
  `is_aktif` enum('Y','N') NOT NULL DEFAULT 'Y',
  PRIMARY KEY (`kd_mode`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `tbl_mode` (`kd_mode`,`nama_mode`,`label_instansi`,`label_kepala`,`label_staf`,`label_peserta`,`label_nomor_induk`,`label_rombongan`,`label_mata_ajar`,`label_laporan`,`label_tahun`,`sistem_nilai`,`punya_jurusan`,`punya_prodi`,`punya_tingkatan`,`punya_krs`,`punya_sks`,`punya_walikelas`,`punya_naik_kelas`,`punya_kkm`,`kkm_default`,`urutan`,`is_aktif`) VALUES
('KAMPUS','Perguruan Tinggi','Kampus','Rektor','Dosen','Mahasiswa','NIM','Prodi / Angkatan','Mata Kuliah','KHS & Transkrip','Tahun Akademik','ANGKA','N','Y','N','Y','Y','N','N','N',0,1,'Y'),
('SMA','SMA / Sederajat','Sekolah','Kepala Sekolah','Guru','Siswa','NIS','Tingkat & Jurusan','Mata Pelajaran','Rapor','Tahun Pelajaran','ANGKA','Y','N','Y','N','N','Y','Y','Y',75,2,'Y'),
('SMP','SMP / Sederajat','Sekolah','Kepala Sekolah','Guru','Siswa','NIS','Tingkat & Jurusan','Mata Pelajaran','Rapor','Tahun Pelajaran','ANGKA','Y','N','Y','N','N','Y','Y','Y',75,3,'Y'),
('SD','SD / Sederajat','Sekolah','Kepala Sekolah','Guru','Siswa','NISN','Tingkat','Mata Pelajaran','Rapor','Tahun Pelajaran','GABUNGAN','N','N','Y','N','N','Y','Y','Y',70,4,'Y'),
('TK','TK / PAUD','PAUD','Kepala PAUD','Guru','Anak Didik','NISN','Kelompok','Area Pengembangan','Laporan Perkembangan','Tahun Ajaran','KUALITATIF','N','N','N','N','N','Y','N','N',0,5,'Y');

-- ---------------------------------------------------------------
-- 2. PENGATURAN APLIKASI (key-value)
-- ---------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `tbl_pengaturan` (
  `kunci` varchar(50) NOT NULL,
  `nilai` text NOT NULL,
  `deskripsi` varchar(255) NOT NULL DEFAULT '',
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`kunci`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `tbl_pengaturan` (`kunci`,`nilai`,`deskripsi`) VALUES
('mode_aktif','KAMPUS','Mode berjalan: KAMPUS/SMA/SMP/SD/TK'),
('kkm_default','75','KKM global (dipakai bila mode menyetel punya_kkm=Y)'),
('judul_aplikasi','SIAKAD','Judul aplikasi pada header/login');

-- ---------------------------------------------------------------
-- 3. DEFINISI FIELD (metadata antar-entitas x mode)
-- sumber_kolom: kolom fisik tabel terstruktur (kosong => EAV)
-- ref_kategori: sumber opsi dropdown dari tbl_referensi
-- berlaku_mode: 'ALL' atau daftar kd_mode dipisah koma (mis. KAMPUS,SMA)
-- ---------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `tbl_field` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `kd_entitas` varchar(30) NOT NULL,
  `kd_field` varchar(40) NOT NULL,
  `label` varchar(80) NOT NULL,
  `tipe_data` varchar(20) NOT NULL DEFAULT 'text',
  `sumber_kolom` varchar(40) DEFAULT NULL,
  `ref_kategori` varchar(30) DEFAULT NULL,
  `is_eav` enum('Y','N') NOT NULL DEFAULT 'Y',
  `wajib` enum('Y','N') NOT NULL DEFAULT 'N',
  `unik` enum('Y','N') NOT NULL DEFAULT 'N',
  `urut` int(11) NOT NULL DEFAULT 0,
  `is_list` enum('Y','N') NOT NULL DEFAULT 'Y',
  `berlaku_mode` varchar(255) NOT NULL DEFAULT 'ALL',
  `is_aktif` enum('Y','N') NOT NULL DEFAULT 'Y',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_ent_field` (`kd_entitas`,`kd_field`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `tbl_field` (`kd_entitas`,`kd_field`,`label`,`tipe_data`,`sumber_kolom`,`ref_kategori`,`is_eav`,`wajib`,`unik`,`urut`,`is_list`,`berlaku_mode`,`is_aktif`) VALUES
-- Peserta/Mahasiswa/Siswa/Anak Didik
('peserta','nomor_induk','Nomor Induk','text','nim',NULL,'N','Y','Y',1,'Y','ALL','Y'),
('peserta','nama','Nama Lengkap','text','nama',NULL,'N','Y','N',2,'Y','ALL','Y'),
('peserta','gender','Jenis Kelamin','select','gender',NULL,'N','Y','N',3,'Y','ALL','Y'),
('peserta','tempat_lahir','Tempat Lahir','text','tempat_lahir',NULL,'N','N','N',4,'Y','ALL','Y'),
('peserta','tanggal_lahir','Tanggal Lahir','date','tanggal_lahir',NULL,'N','N','N',5,'Y','ALL','Y'),
('peserta','agama','Agama','select','kd_agama','AGAMA','N','N','N',6,'Y','ALL','Y'),
('peserta','rombel','Rombongan Belajar','select','kd_kelas',NULL,'N','N','N',7,'Y','ALL','Y'),
('peserta','foto','Foto','file','foto',NULL,'N','N','N',8,'N','ALL','Y'),
('peserta','nisn','NISN','text','nisn',NULL,'Y','N','Y',9,'Y','ALL','Y'),
('peserta','prodi','Program Studi','select',NULL,'PRODI','Y','N','N',10,'Y','KAMPUS','Y'),
('peserta','angkatan','Angkatan','text',NULL,NULL,'Y','N','N',11,'N','KAMPUS','Y'),
('peserta','jalur_masuk','Jalur Masuk','select',NULL,'JALUR_MASUK','Y','N','N',12,'N','KAMPUS','Y'),
('peserta','status_kampus','Status','select',NULL,'STATUS_KAMPUS','Y','Y','N',13,'Y','KAMPUS','Y'),
('peserta','nama_ayah','Nama Ayah','text',NULL,NULL,'Y','N','N',14,'N','TK','Y'),
('peserta','nama_ibu','Nama Ibu','text',NULL,NULL,'Y','N','N',15,'N','TK','Y'),
('peserta','alamat_ortu','Alamat Orang Tua','textarea',NULL,NULL,'Y','N','N',16,'N','ALL','Y'),
-- Guru / Dosen
('guru','id_guru','ID Guru','text','id_guru',NULL,'N','Y','Y',1,'N','ALL','Y'),
('guru','nomor_induk','NUPTK/NIDN','text','nuptk',NULL,'N','Y','N',2,'Y','ALL','Y'),
('guru','nama','Nama Lengkap','text','nama_guru',NULL,'N','Y','N',3,'Y','ALL','Y'),
('guru','gender','Jenis Kelamin','select','gender',NULL,'N','Y','N',4,'Y','ALL','Y'),
('guru','username','Username','text','username',NULL,'N','N','N',5,'N','ALL','Y'),
('guru','password','Password','text','password',NULL,'N','N','N',6,'N','ALL','Y'),
('guru','jabatan_akademik','Jabatan Akademik','select',NULL,'JABATAN_AKADEMIK','Y','N','N',7,'Y','KAMPUS','Y'),
('guru','bidang_keahlian','Bidang Keahlian','text',NULL,NULL,'Y','N','N',8,'N','KAMPUS','Y'),
-- Kelas / Rombongan (/Prodi)
('kelas','kd_tingkatan','Tingkatan','select','kd_tingkatan','TINGKATAN','N','N','N',1,'Y','ALL','Y'),
('kelas','kd_prodi','Program Studi','select',NULL,'PRODI','Y','N','N',2,'Y','KAMPUS','Y'),
('kelas','angkatan','Angkatan','text',NULL,NULL,'Y','N','N',3,'Y','KAMPUS','Y'),
-- Jadwal
('jadwal','kd_mapel','Mata Ajar','select','kd_mapel','MAPEL','N','Y','N',1,'Y','ALL','Y'),
('jadwal','id_guru','Pengajar','select','id_guru',NULL,'N','Y','N',2,'Y','ALL','Y'),
('jadwal','hari','Hari','select','hari',NULL,'N','Y','N',3,'Y','ALL','Y'),
('jadwal','jam','Jam','text','jam',NULL,'N','N','N',4,'N','ALL','Y'),
('jadwal','kd_ruangan','Ruangan','select','kd_ruangan','RUANGAN','N','N','N',5,'Y','ALL','Y'),
('jadwal','kd_kelas','Rombongan','select','kd_kelas',NULL,'N','Y','N',6,'Y','ALL','Y');

-- ---------------------------------------------------------------
-- 4. EAV: NILAI ATRIBUT ENTITAS (data fleksibel per mode)
-- id_induk = nilai PK entitas terstruktur (nim / id_guru / dst.)
-- ---------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `tbl_entitas_atribut` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `kd_entitas` varchar(30) NOT NULL,
  `id_induk` varchar(40) NOT NULL,
  `kd_field` varchar(40) NOT NULL,
  `nilai` text DEFAULT NULL,
  `updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_induk_field` (`kd_entitas`,`id_induk`,`kd_field`),
  KEY `ix_entitas` (`kd_entitas`),
  KEY `ix_induk` (`id_induk`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ---------------------------------------------------------------
-- 5. REFERENSI TERPADU (agama, tingkatan, jurusan, prodi, mapel,
--    ruangan, kurikulum, jenis bayar, jalur masuk, status, jabatan)
-- atribut_json contoh: {"sks":"3","akreditasi":"A","jenjang":"S1"}
-- ---------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `tbl_referensi` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `kategori` varchar(30) NOT NULL,
  `kode` varchar(20) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `atribut_json` text DEFAULT NULL,
  `urutan` int(11) NOT NULL DEFAULT 0,
  `is_aktif` enum('Y','N') NOT NULL DEFAULT 'Y',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_kat_kode` (`kategori`,`kode`),
  KEY `ix_kategori` (`kategori`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- =============================================================
-- SEED REFERENSI
-- =============================================================
-- Agama (universal)
INSERT INTO `tbl_referensi` (`kategori`,`kode`,`nama`,`urutan`,`is_aktif`) VALUES
('AGAMA','1','ISLAM',1,'Y'),
('AGAMA','2','KRISTEN / PROTESTAN',2,'Y'),
('AGAMA','3','KATHOLIK',3,'Y'),
('AGAMA','4','HINDU',4,'Y'),
('AGAMA','5','BUDHA',5,'Y'),
('AGAMA','6','KHONG HU CHU',6,'Y'),
('AGAMA','99','LAIN LAIN',7,'Y');

-- Tingkatan (per mode)
INSERT INTO `tbl_referensi` (`kategori`,`kode`,`nama`,`urutan`,`is_aktif`) VALUES
('TINGKATAN','7','Tingkat Kelas 7 (VII)',1,'Y'),
('TINGKATAN','8','Tingkat Kelas 8 (VIII)',2,'Y'),
('TINGKATAN','9','Tingkat Kelas 9 (IX)',3,'Y'),
('TINGKATAN','10','Tingkat Kelas 10 (X)',1,'Y'),
('TINGKATAN','11','Tingkat Kelas 11 (XI)',2,'Y'),
('TINGKATAN','12','Tingkat Kelas 12 (XII)',3,'Y'),
('TINGKATAN','1','Kelas 1 (I)',1,'Y'),
('TINGKATAN','2','Kelas 2 (II)',2,'Y'),
('TINGKATAN','3','Kelas 3 (III)',3,'Y'),
('TINGKATAN','4','Kelas 4 (IV)',4,'Y'),
('TINGKATAN','5','Kelas 5 (V)',5,'Y'),
('TINGKATAN','6','Kelas 6 (VI)',6,'Y'),
('TINGKATAN','A','Kelompok A (4-5 th)',1,'Y'),
('TINGKATAN','B','Kelompok B (5-6 th)',2,'Y');

-- Jurusan (SMP/SMA) & Prodi (KAMPUS)
INSERT INTO `tbl_referensi` (`kategori`,`kode`,`nama`,`urutan`,`is_aktif`) VALUES
('JURUSAN','IPA','IPA',1,'Y'),
('JURUSAN','IPS','IPS',2,'Y'),
('JURUSAN','IA','Ilmu Alam',1,'Y'),
('JURUSAN','IS','Ilmu Sosial',2,'Y'),
('JURUSAN','BB','Bahasa Budaya',3,'Y'),
('PRODI','SI','S1 Sistem Informasi',1,'Y'),
('PRODI','TI','D3 Teknik Informatika',2,'Y'),
('PRODI','MI','D3 Manajemen Informatika',3,'Y');

-- Mata Ajar / Mata Kuliah (contoh per mode; sks utk KAMPUS)
INSERT INTO `tbl_referensi` (`kategori`,`kode`,`nama`,`atribut_json`,`urutan`,`is_aktif`) VALUES
('MAPEL','BID','Bahasa Indonesia','{"sks":"2"}',1,'Y'),
('MAPEL','MTK','Matematika','{"sks":"4"}',2,'Y'),
('MAPEL','PAI','Pendidikan Agama Islam','{"sks":"2"}',3,'Y'),
('MAPEL','BIO','Biologi','{"sks":"3"}',4,'Y'),
('MAPEL','AKI','Akuntansi Keuangan','{"sks":"3"}',5,'Y'),
('MAPEL','PEM','Pemrograman Web','{"sks":"3"}',6,'Y'),
('MAPEL','JAR','Jaringan Komputer','{"sks":"3"}',7,'Y'),
('MAPEL','BUS','Bhs Inggris','{"sks":"2"}',8,'Y');

-- Ruangan
INSERT INTO `tbl_referensi` (`kategori`,`kode`,`nama`,`urutan`,`is_aktif`) VALUES
('RUANGAN','000','Default',1,'Y'),
('RUANGAN','A1','Ruang Kelas A1',2,'Y'),
('RUANGAN','A2','Ruang Kelas A2',3,'Y'),
('RUANGAN','LAB','Lab Komputer',4,'Y');

-- Kurikulum
INSERT INTO `tbl_referensi` (`kategori`,`kode`,`nama`,`urutan`,`is_aktif`) VALUES
('KURIKULUM','K13','Kurikulum 2013 (K13)',1,'Y'),
('KURIKULUM','KMER','Kurikulum Merdeka',2,'Y'),
('KURIKULUM','KAMPUS','Kurikulum Perguruan Tinggi',1,'Y');

-- Jenis Pembayaran
INSERT INTO `tbl_referensi` (`kategori`,`kode`,`nama`,`urutan`,`is_aktif`) VALUES
('JENIS_BAYAR','SPP','SPP / Uang Sekolah',1,'Y'),
('JENIS_BAYAR','UKT','UKT (Kampus)',2,'Y'),
('JENIS_BAYAR','DSP','Dana Awal Masuk',3,'Y'),
('JENIS_BAYAR','SERAGAM','Seragam',4,'Y'),
('JENIS_BAYAR','OSIS','OSIS',5,'Y');

-- Kategori khusus KAMPUS
INSERT INTO `tbl_referensi` (`kategori`,`kode`,`nama`,`urutan`,`is_aktif`) VALUES
('JALUR_MASUK','SNMPTN','SNMPTN',1,'Y'),
('JALUR_MASUK','SBMPTN','SBMPTN',2,'Y'),
('JALUR_MASUK','MANDIRI','Mandiri',3,'Y'),
('STATUS_KAMPUS','AKTIF','Aktif',1,'Y'),
('STATUS_KAMPUS','CUTI','Cuti',2,'Y'),
('STATUS_KAMPUS','LULUS','Lulus',3,'Y'),
('STATUS_KAMPUS','MUNDUR','Mengundurkan Diri',4,'Y'),
('JABATAN_AKADEMIK','AA','Asisten Ahli',1,'Y'),
('JABATAN_AKADEMIK','LEKTOR','Lektor',2,'Y'),
('JABATAN_AKADEMIK','LK','Lektor Kepala',3,'Y'),
('JABATAN_AKADEMIK','PROF','Profesor',4,'Y');

-- =============================================================
-- 6. KRS (KARTU RENCANA STUDI) - khusus KAMPUS
-- =============================================================
CREATE TABLE IF NOT EXISTS `tbl_krs` (
  `id_krs` int(11) NOT NULL AUTO_INCREMENT,
  `nim` varchar(11) NOT NULL,
  `kd_mapel` varchar(5) NOT NULL,
  `id_tahun_akademik` int(11) NOT NULL,
  `semester` varchar(10) NOT NULL,
  `sks` int(11) NOT NULL DEFAULT 0,
  `status` enum('DRAFT','DISETUJUI') NOT NULL DEFAULT 'DRAFT',
  `tanggal_krs` date DEFAULT NULL,
  PRIMARY KEY (`id_krs`),
  UNIQUE KEY `uq_krs` (`nim`,`kd_mapel`,`id_tahun_akademik`,`semester`),
  KEY `ix_krs_ta` (`id_tahun_akademik`),
  KEY `ix_krs_nim` (`nim`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- =============================================================
-- 7. IDENTITAS INSTANSI (baru; dipakai helper identitas())
-- =============================================================
CREATE TABLE IF NOT EXISTS `tbl_identitas` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nama_sekolah` varchar(100) NOT NULL DEFAULT '',
  `npsn` varchar(20) NOT NULL DEFAULT '',
  `alamat` varchar(255) NOT NULL DEFAULT '',
  `no_telp` varchar(30) NOT NULL DEFAULT '',
  `email` varchar(100) NOT NULL DEFAULT '',
  `website` varchar(100) NOT NULL DEFAULT '',
  `kepala_sekolah` varchar(100) NOT NULL DEFAULT '',
  `nip_kepala` varchar(40) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `tbl_identitas` (`id`,`nama_sekolah`,`npsn`,`alamat`,`no_telp`,`email`,`website`,`kepala_sekolah`,`nip_kepala`) VALUES
(1,'PASANTREN IMAM SYAFI\'I','','Jl Pesantren Km 2, Sibreh, Aceh Besar, Telpon : 0651-23462','0651-23462','','','','');