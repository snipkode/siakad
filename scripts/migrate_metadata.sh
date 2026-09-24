#!/usr/bin/env bash
#
# Migrasi basis data ke arsitektur metadata-driven (multi-mode).
# Idempotent: aman dijalankan berulang.
#
# Yang dilakukan:
#   1. Membuat tabel metadata (tbl_mode, tbl_pengaturan, tbl_field,
#      tbl_entitas_atribut, tbl_referensi, tbl_krs, tbl_identitas).
#   2. Menambahkan kolom kd_mode pada tabel mode-spesifik & mengisi
#      default 'SMP' untuk data lama supaya tetap tampil.
#   3. Menyambungkan tabel Kurikulum dengan referensi (opsional).
#
# Penggunaan:
#   ./scripts/migrate_metadata.sh            # terapkan skema jika belum ada
#   ./scripts/migrate_metadata.sh --force    # selalu jalankan seluruh DDL
#
# Environment (sama seperti ensure_db_views.sh):
#   DB_CONTAINER  nama container MySQL   (default: siakad-db)
#   DB_NAME       nama database          (default: pis_akademik)
#   DB_USER       user MySQL             (default: root)
#   DB_PASS       password MySQL         (default: root)
#   DB_HOST       host mysql saat tanpa docker (default: 127.0.0.1)
set -euo pipefail

DB_CONTAINER="${DB_CONTAINER:-siakad-db}"
DB_NAME="${DB_NAME:-pis_akademik}"
DB_USER="${DB_USER:-root}"
DB_PASS="${DB_PASS:-root}"
DB_HOST="${DB_HOST:-127.0.0.1}"
FORCE="${1:-}"

SCRIPT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
SCHEMA_FILE="$SCRIPT_DIR/../database/meta_schema.sql"

run_sql() {
  if [ -n "$DB_CONTAINER" ] && docker ps --format '{{.Names}}' 2>/dev/null | grep -qx "$DB_CONTAINER"; then
    docker exec -i "$DB_CONTAINER" mysql -u"$DB_USER" -p"$DB_PASS" -N -B "$DB_NAME"
  else
    mysql -h"$DB_HOST" -u"$DB_USER" -p"$DB_PASS" -N -B "$DB_NAME"
  fi
}

table_exists() {
  local t
  t=$(printf "SELECT COUNT(*) FROM information_schema.tables WHERE table_schema='%s' AND table_name='%s';" "$DB_NAME" "$1" | run_sql)
  [ "$t" = "1" ]
}

column_exists() {
  local c
  c=$(printf "SELECT COUNT(*) FROM information_schema.columns WHERE table_schema='%s' AND table_name='%s' AND column_name='%s';" "$DB_NAME" "$1" "$2" | run_sql)
  [ "$c" = "1" ]
}

q() { printf "%s" "$1" | run_sql >/dev/null; }

echo "==> Migrasi schema metadata-driven untuk database '$DB_NAME'..."

# --- 1. Tabel metadata ---
if ! table_exists "tbl_mode" || [ "$FORCE" = "--force" ]; then
  # Pipe isi file langsung ke klien mysql (file tidak selalu ter-mount di container).
  cat "$SCHEMA_FILE" | run_sql >/dev/null
  echo "==> OK: tabel metadata dibuat/di-seed dari meta_schema.sql."
else
  echo "==> tbl_mode sudah ada; lewati pembuatan metadata."
fi

# --- 2. Tambahkan kolom kd_mode pada tabel mode-spesifik ---
# peserta & guru: mode pemilik data
for tbl in tbl_siswa tbl_guru tbl_kelas tbl_jadwal tbl_nilai tbl_pembayaran; do
  if table_exists "$tbl" && ! column_exists "$tbl" "kd_mode"; then
    q "ALTER TABLE $tbl ADD COLUMN kd_mode varchar(10) NOT NULL DEFAULT 'SMP' AFTER $(case $tbl in tbl_siswa) echo "kd_kelas";; tbl_guru) echo "password";; tbl_kelas) echo "kd_jurusan";; tbl_jadwal) echo "hari";; tbl_nilai) echo "nilai";; tbl_pembayaran) echo "keterangan";; esac);"
    echo "==> $tbl: kolom kd_mode ditambahkan (default SMP)."
  fi
done

# riwayat kelas & walikelas (referensi rombel lama)
for tbl in tbl_riwayat_kelas tbl_walikelas; do
  if table_exists "$tbl" && ! column_exists "$tbl" "kd_mode"; then
    q "ALTER TABLE $tbl ADD COLUMN kd_mode varchar(10) NOT NULL DEFAULT 'SMP';"
    echo "==> $tbl: kolom kd_mode ditambahkan (default SMP)."
  fi
done

# tahun akademik: masing-masing mode punya tahun sendiri
if table_exists "tbl_tahun_akademik" && ! column_exists "tbl_tahun_akademik" "kd_mode"; then
  q "ALTER TABLE tbl_tahun_akademik ADD COLUMN kd_mode varchar(10) NOT NULL DEFAULT 'SMP' AFTER semester;"
  echo "==> tbl_tahun_akademik: kolom kd_mode ditambahkan (default SMP)."
fi

# menu: filter menu per mode
if table_exists "tabel_menu" && ! column_exists "tabel_menu" "berlaku_mode"; then
  q "ALTER TABLE tabel_menu ADD COLUMN berlaku_mode varchar(255) NOT NULL DEFAULT 'ALL' AFTER is_main_menu;"
  echo "==> tabel_menu: kolom berlaku_mode ditambahkan."
fi

# --- 3. Tambahkan kolom baru yang dibutuhkan fitur kampus ---
# nilai: kolom sks agar KAMPUS bisa simpan bobot per siswa
if table_exists "tbl_nilai" && ! column_exists "tbl_nilai" "sks"; then
  q "ALTER TABLE tbl_nilai ADD COLUMN sks int(11) NOT NULL DEFAULT 0 AFTER nilai;"
fi
# siswa: kolom nisn (opsional, umum di sekolah) -- hanya bila belum ada
if table_exists "tbl_siswa" && ! column_exists "tbl_siswa" "nisn" && ! column_exists "tbl_siswa" "status"; then
  q "ALTER TABLE tbl_siswa ADD COLUMN nisn varchar(20) NOT NULL DEFAULT '' AFTER nim;"
fi

# kelas: kolom khusus KAMPUS (prodi & angkatan pada rombel)
if table_exists "tbl_kelas" && ! column_exists "tbl_kelas" "kd_prodi"; then
  q "ALTER TABLE tbl_kelas ADD COLUMN kd_prodi varchar(20) NOT NULL DEFAULT '' AFTER kd_jurusan;"
fi
if table_exists "tbl_kelas" && ! column_exists "tbl_kelas" "angkatan"; then
  q "ALTER TABLE tbl_kelas ADD COLUMN angkatan varchar(10) NOT NULL DEFAULT '' AFTER kd_prodi;"
fi

# index performa referensi & eav
q "SELECT 1;" # pastikan koneksi hidup

# --- 4. Seed atribut SKS untuk MAPEL (dipakai KRS/IP/IPK kampus) ---
if table_exists "tbl_referensi"; then
  cat <<'SQL' | run_sql >/dev/null
UPDATE tbl_referensi
SET atribut_json = CASE kode
  WHEN 'PAI' THEN '{"sks":2}'
  WHEN 'BUS' THEN '{"sks":2}'
  WHEN 'MTK' THEN '{"sks":3}'
  WHEN 'BID' THEN '{"sks":3}'
  WHEN 'BIO' THEN '{"sks":3}'
  WHEN 'AKI' THEN '{"sks":3}'
  WHEN 'JAR' THEN '{"sks":3}'
  WHEN 'PEM' THEN '{"sks":3}'
END
WHERE kategori = 'MAPEL';
SQL
  echo "==> MAPEL: atribut sks di-seed."
fi

echo "==> SELESAI: database '$DB_NAME' siap untuk arsitektur metadata-driven."