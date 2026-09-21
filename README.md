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
application/controllers/   Auth, Tampilan_utama, CRUD modules
application/models/        Model_user, Model_guru, ...
application/views/         dashboard, jadwal, rapor, ...
build/app.css              Tailwind source (imports + legacy/DataTables styles)
assets/custom/css/app.css  Compiled CSS output
pis_akademik.sql           Database dump (used as init script in Docker)
```

## Support

If you have any question, feel free to open an issue or contact the repository owner.