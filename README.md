# SIAKAD — Sistem Informasi Akademik

Web-based academic information system for schools built on **CodeIgniter 3.1.5** (PHP 8.1). Manage students, teachers, subjects, class schedules, grades, and generate **e-raport** with school identity & signatures.

## Features

- Multi-level login (admin, guru, siswa, keuangan — dynamic & extendable)
- Session-based authentication
- Dashboard with statistics & charts (siswa per tingkatan/jurusan)
- CRUD: Siswa, Guru, Mapel, Ruangan Kelas, Tingkatan Kelas, Jurusan, Tahun Akademik, Kelas, Kurikulum, User, Menu
- Import data siswa via CSV, export data siswa & nilai (e-raport)
- Rule/menu permission per user level
- Jadwal pelajaran generator with print support
- Input nilai siswa & export e-raport
- **Rapor** with school identity, header, and TTD kepala/wali sekolah
- **Server-side DataTables** for fast filtering/pagination on large tables
- Modern UI built with **Tailwind CSS v4**
- **Multi-mode metadata-driven** (KAMPUS / SMA / SMP / SD / TK): label & menu menyesuaikan mode aktif
- **KRS kampus**: penawaran mata kuliah, pengambilan SKS, IP/IPK, KHS & Transkrip

## Multi-Mode (Sekolah / Kampus)

Satu instalasi bisa dipakai sekolah (SMP/SMA/SD/TK) maupun kampus (KAMPUS). Hanya **satu mode aktif** yang dipilih lewat menu **Pengaturan**; seluruh modul (siswa, guru, mapel, kelas, jadwal, nilai, pembayaran, laporan, dashboard) otomatis mengikuti mode tersebut.

- **Label dinamis** disimpan di `tbl_mode` (mis. Mahasiswa/Siswa/Anak Didik, Dosen/Guru, Mata Kuliah/Mata Pelajaran, Rektor/Kepala Sekolah, dll).
- **Menu per mode**: kolom `berlaku_mode` di `tabel_menu` (`ALL`, atau daftar kode dipisah koma). Contoh: menu Nilai & Jadwal hanya untuk mode sekolah; menu KRS hanya untuk KAMPUS.
- **Data per mode**: tabel transaksi (siswa, guru, kelas, jadwal, nilai, pembayaran, tahun akademik) memiliki kolom `kd_mode`; query selalu difilter mode aktif.
- **Fitur kampus**: rombongan memakai Prodi + Angkatan (referensi `PRODI`), nilai berbobot SKS (referensi `MAPEL.atribut_json.sks`), KRS otomatis membuat penawaran mata kuliah, dan laporan **KHS + Transkrip** (IP/IPK + predikat).

### Setup

Fresh install (Docker): `docker compose up -d --build` — `database/meta_schema.sql` & `database/meta_alter.sql` ikut dijalankan sebagai init script.

Instalasi existing (bukan init-Docker): jalankan

```bash
bash scripts/migrate_metadata.sh        # schema + seed SKS (idempotent)
bash scripts/ensure_db_views.sh --force # view v_jadwal_nilai & v_krs_mahasiswa
```

## Requirements

- Docker (recommended) **or**
- PHP 8.x + MySQL 8.x running natively

## Quick Start (Docker)

```bash
docker compose up -d --build
```

Then open `http://localhost:8081`.

On first boot, the database `pis_akademik` is created and seeded automatically from `pis_akademik.sql`.

| Service | Container | Port |
|---|---|---|
| Web (Apache + PHP 8.1) | `siakad-web` | 8081 |
| Database (MySQL 8.0) | `siakad-db` | 3306 |

DB config lives in `application/config/database.php` (`hostname: db`, `user: root`, `password: root`).

## Login

| Role | Username | Password |
|---|---|---|
| Admin | `zuhri` | `123456` |
| Admin | `mulvi` | `123456` |
| Keuangan | `ika` | `123456` |
| Guru | `fajri` | `123456` |

## Frontend (Tailwind CSS v4)

The project uses a buildless Tailwind v4 pipeline. Source is `build/app.css`, output is `assets/custom/css/app.css`.

```bash
npm install
npm run css:build   # one-time build
npm run css:watch   # watch + rebuild while developing
```

Tailwind scans `application/` and `assets/custom/` for class names automatically.

## Project Structure

```
application/controllers/   Auth, Tampilan_utama, CRUD modules, Krs, Pengaturan, Referensi
application/models/        Model_user, Model_guru, ...
application/views/         dashboard, jadwal, rapor, krs, laporan_nilai, ...
application/libraries/     Meta (baca metadata mode & referensi)
application/views/common/  _eav_fields (form metadata-driven)
build/app.css              Tailwind source (imports + legacy/DataTables styles)
assets/custom/css/app.css  Compiled CSS output
pis_akademik.sql           Database dump (used as init script in Docker)
database/meta_schema.sql   Skema & seed metadata (tbl_mode, tbl_field, tbl_referensi, ...)
database/meta_alter.sql    ALTER pada schema lama (mode-spesifik) untuk fresh install
scripts/migrate_metadata.sh    Migrasi install existing (idempotent)
scripts/ensure_db_views.sh     Rebuild view v_jadwal_nilai & v_krs_mahasiswa
```

## Support

If you have any question, feel free to open an issue or contact the repository owner.